<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SPBIHeader;
use App\Models\Employee;
use App\Models\Status;
use App\Models\LibParameter;

class SPBIController extends Controller
{
    private function psisConn(): string
    {
        return match (session('FundClass')) {
            'BDD' => 'psis_bdd',
            'TRUST' => 'psis_trust',
            'RCEP' => 'psis_rcep',
            default => 'psis_corporate',
        };
    }

    public function index()
    {
        $data = [
            'elementActive' => 'SPBI',
            'headers' => [],
        ];

        $dbHost = env('DB_HOST', 'localhost');
        $dbPort = env('DB_PORT', '1433');
        $conn = @fsockopen($dbHost, $dbPort, $errno, $errstr, 2);

        if ($conn) {
            fclose($conn);
            try {
                $data['headers'] = SPBIHeader::getHeaders(['Status' => ['I', 'A', 'D']]);
            } catch (\Exception $e) {
                $data['headers'] = [];
            }
        }

        return view('pages.spbi.index', $data);
    }

    public function preparationInbox()
    {
        if (!hasAccess(2)) {
            return view('pages.access_denied');
        }

        $data = [
            'elementActive' => 'SPBI',
            'inbox_title' => 'Preparation Inbox',
            'headers' => SPBIHeader::getHeaders([
                'Status' => ['N', 'R'],
                'EncodedBy' => session('EmployeeID'),
            ]),
        ];

        return view('pages.spbi.inbox', $data);
    }

    public function approvalInbox()
    {
        if (!hasAccess(3)) {
            return view('pages.access_denied');
        }

        $data = [
            'elementActive' => 'SPBI',
            'inbox_title' => 'Approval Inbox',
            'headers' => SPBIHeader::getHeaders(['Status' => ['D']]),
        ];

        return view('pages.spbi.inbox', $data);
    }

    public function query()
    {
        if (!hasAccess(7)) {
            return view('pages.access_denied');
        }

        $data = [
            'elementActive' => 'SPBI',
        ];

        return view('pages.spbi.query', $data);
    }

    public function preparation(Request $request)
    {
        $data = [
            'elementActive' => 'SPBI',
            'default' => [
                'EncodedBy' => session('EmployeeID', ''),
                'EncodedBy_Name' => session('EmployeeName', ''),
            ],
        ];

        return view('pages.spbi.preparation', $data);
    }

    public function getHeaders(Request $request)
    {
        $criteria = [
            'Status' => $request->input('status', []),
        ];

        $headers = SPBIHeader::getHeaders($criteria);
        
        $result = [];
        foreach ($headers as $row) {
            $rowArray = (array) $row;
            $rowArray['EncodedBy_Name'] = Employee::getEmployeeName($rowArray['EncodedBy']);
            $rowArray['ApprovedBy_Name'] = Employee::getEmployeeName($rowArray['ApprovedBy'] ?? '');
            $rowArray['StatusName'] = Status::getStatusName($rowArray['Status']);
            $result[] = $rowArray;
        }
        
        return response()->json($result);
    }

    public function getDetails(Request $request)
    {
        $spbiHeaderID = $request->input('spbiHeaderID');
        $details = SPBIHeader::getDetails(['SPBIHeaderID' => $spbiHeaderID]);
        return response()->json($details);
    }

    public function save(Request $request)
    {
        try {
            $header = $request->input('header');
            $details = $request->input('details', []);

            $spbiHeaderID = SPBIHeader::saveSPBI($header, $details);

            return response()->json([
                'success' => true,
                'message' => 'SPBI saved successfully',
                'spbiHeaderID' => $spbiHeaderID,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function approve(Request $request)
    {
        try {
            $spbiHeaderID = $request->input('spbiHeaderID');
            $remarks = $request->input('remarks', '');

            SPBIHeader::approveSPBI($spbiHeaderID, $remarks);

            return response()->json([
                'success' => true,
                'message' => 'SPBI approved successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
