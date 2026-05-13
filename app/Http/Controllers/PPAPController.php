<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PPAPHeader;
use Illuminate\Support\Facades\DB;

class PPAPController extends Controller
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
            'elementActive' => 'PPAP',
            'headers' => [],
        ];

        $dbHost = env('DB_HOST', 'localhost');
        $dbPort = env('DB_PORT', '1433');
        $conn = @fsockopen($dbHost, $dbPort, $errno, $errstr, 2);

        if ($conn) {
            fclose($conn);
            try {
                $data['headers'] = PPAPHeader::getHeaders(['Status' => ['I', 'A', 'D']]);
            } catch (\Exception $e) {
                $data['headers'] = [];
            }
        }

        return view('pages.ppap.index', $data);
    }

    public function preparationInbox()
    {
        if (!hasAccess(2)) {
            return view('pages.access_denied');
        }

        $data = [
            'elementActive' => 'PPAP',
            'headers' => [],
        ];

        $dbHost = env('DB_HOST', 'localhost');
        $dbPort = env('DB_PORT', '1433');
        $conn = @fsockopen($dbHost, $dbPort, $errno, $errstr, 2);

        if ($conn) {
            fclose($conn);
            try {
                $data['headers'] = PPAPHeader::getHeaders(['Status' => ['N', 'R']]);
            } catch (\Exception $e) {
                $data['headers'] = [];
            }
        }

        return view('pages.ppap.preparation_inbox', $data);
    }

    public function certificationInbox()
    {
        if (!hasAccess(4)) {
            return view('pages.access_denied');
        }

        $data = [
            'elementActive' => 'PPAP',
            'inbox_title' => 'Certification Inbox',
            'headers' => PPAPHeader::getHeaders([
                'Status' => ['E'],
                'CertifiedBy' => session('EmployeeID'),
            ]),
        ];

        return view('pages.ppap.inbox', $data);
    }

    public function approvalInbox()
    {
        if (!hasAccess(3)) {
            return view('pages.access_denied');
        }

        $data = [
            'elementActive' => 'PPAP',
            'inbox_title' => 'Approval Inbox',
            'headers' => PPAPHeader::getHeaders(['Status' => ['D']]),
        ];

        return view('pages.ppap.inbox', $data);
    }

    public function receivingInbox()
    {
        if (!hasAccess(5)) {
            return view('pages.access_denied');
        }

        $data = [
            'elementActive' => 'PPAP',
            'inbox_title' => 'Receiving Inbox',
            'headers' => PPAPHeader::getHeaders(['Status' => ['A']]),
        ];

        return view('pages.ppap.inbox', $data);
    }

    public function query()
    {
        if (!hasAccess(7)) {
            return view('pages.access_denied');
        }

        $data = [
            'elementActive' => 'PPAP',
        ];

        return view('pages.ppap.query', $data);
    }

    public function preparation(Request $request)
    {
        $data = [
            'elementActive' => 'PPAP',
            'default' => [
                'EncodedBy' => session('EmployeeID', ''),
                'EncodedBy_Name' => session('EmployeeName', ''),
            ],
        ];

        return view('pages.ppap.preparation', $data);
    }

    public function getHeaders(Request $request)
    {
        $criteria = [
            'Status' => $request->input('status', []),
            'PPAPYear' => $request->input('ppapYear', date('Y')),
        ];

        $headers = PPAPHeader::getHeaders($criteria);
        return response()->json($headers);
    }

    public function getDetails(Request $request)
    {
        $ppapHeaderID = $request->input('ppapHeaderID');
        $details = PPAPHeader::getDetails(['PPAPHeaderID' => $ppapHeaderID]);
        return response()->json($details);
    }

    public function save(Request $request)
    {
        try {
            $header = $request->input('header');
            $details = $request->input('details', []);
            $summary = $request->input('summary', []);

            $ppapHeaderID = PPAPHeader::savePPAP($header, $details, $summary);

            return response()->json([
                'success' => true,
                'message' => 'PPAP saved successfully',
                'ppapHeaderID' => $ppapHeaderID,
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
            $ppapHeaderID = $request->input('ppapHeaderID');
            $remarks = $request->input('remarks', '');

            PPAPHeader::approvePPAP($ppapHeaderID, $remarks);

            return response()->json([
                'success' => true,
                'message' => 'PPAP approved successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
