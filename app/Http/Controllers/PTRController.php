<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PTR;
use App\Models\Employee;
use App\Models\Status;

class PTRController extends Controller
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
            'elementActive' => 'PTR',
            'headers' => [],
        ];

        $dbHost = env('DB_HOST', 'localhost');
        $dbPort = env('DB_PORT', '1433');
        $conn = @fsockopen($dbHost, $dbPort, $errno, $errstr, 2);

        if ($conn) {
            fclose($conn);
            try {
                $data['headers'] = PTR::getHeaders(['Status' => ['I', 'A', 'D']]);
            } catch (\Exception $e) {
                $data['headers'] = [];
            }
        }

        return view('pages.ptr.index', $data);
    }

    public function preparationInbox()
    {
        if (!hasAccess(2)) {
            return view('pages.access_denied');
        }

        $data = [
            'elementActive' => 'PTR',
            'inbox_title' => 'Preparation Inbox',
            'headers' => PTR::getHeaders([
                'Status' => ['N', 'R'],
                'PreparedBy' => session('EmployeeID'),
            ]),
        ];

        return view('pages.ptr.inbox', $data);
    }

    public function query()
    {
        if (!hasAccess(7)) {
            return view('pages.access_denied');
        }

        $data = [
            'elementActive' => 'PTR',
        ];

        return view('pages.ptr.query', $data);
    }

    public function preparation(Request $request)
    {
        $data = [
            'elementActive' => 'PTR',
            'default' => [
                'PreparedBy' => session('EmployeeID', ''),
                'PreparedBy_Name' => session('EmployeeName', ''),
            ],
        ];

        return view('pages.ptr.preparation', $data);
    }

    public function getHeaders(Request $request)
    {
        $criteria = $request->input('criteria', []);
        $headers = PTR::getHeaders($criteria);
        
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
        $ptrHeaderID = $request->input('ptrHeaderID');
        $details = PTR::getDetails($ptrHeaderID);
        return response()->json($details);
    }

    public function save(Request $request)
    {
        try {
            $header = $request->input('header');
            $details = $request->input('details', []);

            $ptrHeaderID = PTR::saveHeader($header);
            PTR::saveDetails($ptrHeaderID, $details);

            return response()->json([
                'success' => true,
                'message' => 'PTR saved successfully',
                'ptrHeaderID' => $ptrHeaderID,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
