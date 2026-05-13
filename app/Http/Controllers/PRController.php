<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PRHeader;
use Illuminate\Support\Facades\DB;

class PRController extends Controller
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
            'elementActive' => 'PR',
            'headers' => [],
        ];

        $dbHost = env('DB_HOST', 'localhost');
        $dbPort = env('DB_PORT', '1433');
        $conn = @fsockopen($dbHost, $dbPort, $errno, $errstr, 2);

        if ($conn) {
            fclose($conn);
            try {
                $data['headers'] = PRHeader::getHeaders(['Status' => ['I', 'A', 'D']]);
            } catch (\Exception $e) {
                $data['headers'] = [];
            }
        }

        return view('pages.pr.index', $data);
    }

    public function preparationInbox()
    {
        if (!hasAccess(2)) {
            return view('pages.access_denied');
        }

        $data = [
            'elementActive' => 'PR',
            'inbox_title' => 'Preparation Inbox',
            'headers' => PRHeader::getHeaders([
                'Status' => ['N', 'R'],
                'EncodedBy' => session('EmployeeID'),
            ]),
        ];

        return view('pages.pr.inbox', $data);
    }

    public function approvalInbox()
    {
        if (!hasAccess(3)) {
            return view('pages.access_denied');
        }

        $data = [
            'elementActive' => 'PR',
            'inbox_title' => 'Approval Inbox',
            'headers' => PRHeader::getHeaders(['Status' => ['D']]),
        ];

        return view('pages.pr.inbox', $data);
    }

    public function query()
    {
        if (!hasAccess(7)) {
            return view('pages.access_denied');
        }

        $data = [
            'elementActive' => 'PR',
        ];

        return view('pages.pr.query', $data);
    }

    public function preparation(Request $request)
    {
        $data = [
            'elementActive' => 'PR',
            'default' => [
                'EncodedBy' => session('EmployeeID', ''),
                'EncodedBy_Name' => session('EmployeeName', ''),
            ],
        ];

        return view('pages.pr.preparation', $data);
    }

    public function getHeaders(Request $request)
    {
        $criteria = [
            'Status' => $request->input('status', []),
            'PRControlNo' => $request->input('prControlNo', ''),
        ];

        $headers = PRHeader::getHeaders($criteria);
        return response()->json($headers);
    }

    public function getDetails(Request $request)
    {
        $prHeaderID = $request->input('prHeaderID');
        $details = PRHeader::getDetails(['PRHeaderID' => $prHeaderID]);
        return response()->json($details);
    }

    public function save(Request $request)
    {
        try {
            $header = $request->input('header');
            $details = $request->input('details', []);

            $prHeaderID = PRHeader::savePR($header, $details);

            return response()->json([
                'success' => true,
                'message' => 'PR saved successfully',
                'prHeaderID' => $prHeaderID,
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
            $prHeaderID = $request->input('prHeaderID');
            $remarks = $request->input('remarks', '');

            PRHeader::approvePR($prHeaderID, $remarks);

            return response()->json([
                'success' => true,
                'message' => 'PR approved successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function cancel(Request $request)
    {
        try {
            $prHeaderID = $request->input('prHeaderID');
            $remarks = $request->input('remarks', '');

            PRHeader::cancelPR($prHeaderID, $remarks);

            return response()->json([
                'success' => true,
                'message' => 'PR cancelled successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
