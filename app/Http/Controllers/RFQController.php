<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RFQHeader;
use Illuminate\Support\Facades\DB;

class RFQController extends Controller
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
            'elementActive' => 'RFQ',
            'headers' => [],
        ];

        $dbHost = env('DB_HOST', 'localhost');
        $dbPort = env('DB_PORT', '1433');
        $conn = @fsockopen($dbHost, $dbPort, $errno, $errstr, 2);

        if ($conn) {
            fclose($conn);
            try {
                $data['headers'] = RFQHeader::getHeaders(['Status' => ['I', 'A', 'D']]);
            } catch (\Exception $e) {
                $data['headers'] = [];
            }
        }

        return view('pages.rfq.index', $data);
    }

    public function preparationInbox()
    {
        if (!hasAccess(2)) {
            return view('pages.access_denied');
        }

        $data = [
            'elementActive' => 'RFQ',
            'inbox_title' => 'Preparation Inbox',
            'headers' => RFQHeader::getHeaders([
                'Status' => ['N', 'R'],
                'EncodedBy' => session('EmployeeID'),
            ]),
        ];

        return view('pages.rfq.inbox', $data);
    }

    public function approvalInbox()
    {
        if (!hasAccess(3)) {
            return view('pages.access_denied');
        }

        $data = [
            'elementActive' => 'RFQ',
            'inbox_title' => 'Approval Inbox',
            'headers' => RFQHeader::getHeaders(['Status' => ['D']]),
        ];

        return view('pages.rfq.inbox', $data);
    }

    public function query()
    {
        if (!hasAccess(7)) {
            return view('pages.access_denied');
        }

        $data = [
            'elementActive' => 'RFQ',
        ];

        return view('pages.rfq.query', $data);
    }

    public function preparation(Request $request)
    {
        $data = [
            'elementActive' => 'RFQ',
            'default' => [
                'EncodedBy' => session('EmployeeID', ''),
                'EncodedBy_Name' => session('EmployeeName', ''),
            ],
        ];

        return view('pages.rfq.preparation', $data);
    }

    public function getHeaders(Request $request)
    {
        $criteria = [
            'Status' => $request->input('status', []),
        ];

        $headers = RFQHeader::getHeaders($criteria);
        return response()->json($headers);
    }

    public function getDetails(Request $request)
    {
        $rfqHeaderID = $request->input('rfqHeaderID');
        $details = RFQHeader::getDetails(['RFQHeaderID' => $rfqHeaderID]);
        return response()->json($details);
    }

    public function save(Request $request)
    {
        try {
            $header = $request->input('header');
            $details = $request->input('details', []);

            $rfqHeaderID = RFQHeader::saveRFQ($header, $details);

            return response()->json([
                'success' => true,
                'message' => 'RFQ saved successfully',
                'rfqHeaderID' => $rfqHeaderID,
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
            $rfqHeaderID = $request->input('rfqHeaderID');
            $remarks = $request->input('remarks', '');

            RFQHeader::approveRFQ($rfqHeaderID, $remarks);

            return response()->json([
                'success' => true,
                'message' => 'RFQ approved successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
