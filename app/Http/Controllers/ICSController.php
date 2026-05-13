<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ICSHeader;
use App\Models\Employee;
use App\Models\Status;

class ICSController extends Controller
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
            'elementActive' => 'ICS',
            'headers' => [],
        ];

        $dbHost = env('DB_HOST', 'localhost');
        $dbPort = env('DB_PORT', '1433');
        $conn = @fsockopen($dbHost, $dbPort, $errno, $errstr, 2);

        if ($conn) {
            fclose($conn);
            try {
                $data['headers'] = ICSHeader::getHeaders(['Status' => ['I', 'A', 'D']]);
            } catch (\Exception $e) {
                $data['headers'] = [];
            }
        }

        return view('pages.ics.index', $data);
    }

    public function preparationInbox()
    {
        if (!hasAccess(2)) {
            return view('pages.access_denied');
        }

        $data = [
            'elementActive' => 'ICS',
            'inbox_title' => 'Preparation Inbox',
            'headers' => ICSHeader::getHeaders([
                'Status' => ['N', 'R'],
                'PreparedBy' => session('EmployeeID'),
            ]),
        ];

        return view('pages.ics.inbox', $data);
    }

    public function acceptanceInbox()
    {
        if (!hasAccess(80)) {
            return view('pages.access_denied');
        }

        $data = [
            'elementActive' => 'ICS',
            'inbox_title' => 'Acceptance Inbox',
            'headers' => ICSHeader::getHeaders([
                'Status' => ['T'],
                'AccountableOfficer' => session('EmployeeID'),
            ]),
        ];

        return view('pages.ics.inbox', $data);
    }

    public function approvalInbox()
    {
        if (!hasAccess(81)) {
            return view('pages.access_denied');
        }

        $data = [
            'elementActive' => 'ICS',
            'inbox_title' => 'Approval Inbox',
            'headers' => ICSHeader::getHeaders([
                'Status' => ['T'],
                'ApprovedBy' => session('EmployeeID'),
            ]),
        ];

        return view('pages.ics.inbox', $data);
    }

    public function receivingInbox()
    {
        if (!hasAccess(82)) {
            return view('pages.access_denied');
        }

        $data = [
            'elementActive' => 'ICS',
            'inbox_title' => 'Receiving Inbox',
            'headers' => ICSHeader::getHeaders([
                'Status' => ['A'],
            ]),
        ];

        return view('pages.ics.inbox', $data);
    }

    public function query()
    {
        if (!hasAccess(7)) {
            return view('pages.access_denied');
        }

        $data = [
            'elementActive' => 'ICS',
        ];

        return view('pages.ics.query', $data);
    }

    public function preparation(Request $request)
    {
        $data = [
            'elementActive' => 'ICS',
            'default' => [
                'PreparedBy' => session('EmployeeID', ''),
                'PreparedBy_Name' => session('EmployeeName', ''),
            ],
        ];

        return view('pages.ics.preparation', $data);
    }

    public function getHeaders(Request $request)
    {
        $criteria = $request->input('criteria', []);
        $headers = ICSHeader::getHeaders($criteria);
        
        $result = [];
        foreach ($headers as $row) {
            $rowArray = (array) $row;
            $rowArray['PreparedBy_Name'] = Employee::getEmployeeName($rowArray['PreparedBy']);
            $rowArray['StatusName'] = Status::getStatusName($rowArray['Status']);
            $result[] = $rowArray;
        }
        
        return response()->json(['header' => $result]);
    }

    public function getDetails(Request $request)
    {
        $icsHeaderID = $request->input('icsHeaderID');
        $details = ICSHeader::getDetails($icsHeaderID);
        return response()->json($details);
    }

    public function save(Request $request)
    {
        try {
            $header = $request->input('header');
            $details = $request->input('details', []);

            $icsHeaderID = ICSHeader::saveHeader($header);
            ICSHeader::saveDetails($icsHeaderID, $details);

            return response()->json([
                'success' => true,
                'message' => 'ICS saved successfully',
                'icsHeaderID' => $icsHeaderID,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
