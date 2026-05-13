<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;

class PPMPController extends Controller
{
    /**
     * Determine the active PSIS fund-class database connection.
     * PPMP data lives in fund-class DBs (CI default/default_bdd/default_trust/default_rcep).
     */
    private function psisConn(): string
    {
        return match (session('FundClass')) {
            'BDD' => 'psis_bdd',
            'TRUST' => 'psis_trust',
            'RCEP' => 'psis_rcep',
            default => 'psis_corporate',
        };
    }

    /**
     * Show the PPMP dashboard (index).
     */
    public function index()
    {
        $data = [
            'date' => [
                'Year'  => date('Y'),
                'Month' => date('m'),
            ],
            'elementActive' => 'PPMP',
            'ppmp'          => [],
        ];

        // Check if SQL Server is reachable before querying
        $dbHost = env('DB_HOST', 'localhost');
        $dbPort = env('DB_PORT', '1433');
        $conn = @fsockopen($dbHost, $dbPort, $errno, $errstr, 2);

        if ($conn) {
            fclose($conn);
            try {
                $criteria = [
                    'Status'   => ['I'],
                    'PPMPYear' => date('Y'),
                ];
                $data['ppmp'] = \Illuminate\Support\Facades\DB::connection($this->psisConn())
                    ->table('PPMP_Header')
                    ->where('PPMPYear', date('Y'))
                    ->whereIn('Status', ['I'])
                    ->get()
                    ->map(function ($item) {
                        return (array) $item;
                    })
                    ->toArray();
            } catch (\Exception $e) {
                $data['ppmp'] = [];
            }
        }

        // Sort by ProjectCode
        usort($data['ppmp'], function ($a, $b) {
            return ($a['ProjectCode'] ?? '') <=> ($b['ProjectCode'] ?? '');
        });

        return view('pages.ppmp.index', $data);
    }

    /**
     * Preparation Inbox.
     */
    public function preparationInbox()
    {
        if (!hasAccess(2)) {
            return view('pages.access_denied');
        }

        $data = [
            'elementActive' => 'PPMP',
            'inbox_title'   => 'Preparation Inbox',
            'headers'       => $this->fetchHeaders([
                'Status'           => ['N', 'R'],
                'PPMPYear_current' => date('Y') + 1,
                'EncodedBy'        => session('EmployeeID'),
            ]),
        ];

        return view('pages.ppmp.inbox', $data);
    }

    /**
     * Evaluation Inbox.
     */
    public function evaluationInbox()
    {
        if (!hasAccess(3)) {
            return view('pages.access_denied');
        }

        $data = [
            'elementActive' => 'PPMP',
            'inbox_title'   => 'Evaluation Inbox',
            'headers'       => $this->fetchHeaders(['Status' => ['D'], 'PPMPYear_current' => date('Y') + 1]),
        ];

        return view('pages.ppmp.inbox', $data);
    }

    /**
     * Approval Inbox.
     */
    public function approvalInbox()
    {
        if (!hasAccess(3)) {
            return view('pages.access_denied');
        }

        $data = [
            'elementActive' => 'PPMP',
            'inbox_title'   => 'Approval Inbox',
            'headers'       => $this->fetchHeaders([
                'Status'             => ['E'],
                'ApprovedBy'         => session('EmployeeID'),
                'PPMPYear_current'   => date('Y') + 1,
            ]),
        ];

        return view('pages.ppmp.inbox', $data);
    }

    /**
     * Certification Inbox.
     */
    public function certificationInbox()
    {
        if (!hasAccess(6)) {
            return view('pages.access_denied');
        }

        $data = [
            'elementActive' => 'PPMP',
            'inbox_title'   => 'Certification Inbox',
            'headers'       => $this->fetchHeaders([
                'Status'             => ['A'],
                'CertifiedBy'        => session('EmployeeID'),
                'PPMPYear_current'   => date('Y') + 1,
            ]),
        ];

        return view('pages.ppmp.inbox', $data);
    }

    /**
     * Receiving Inbox.
     */
    public function receivingInbox()
    {
        if (!hasAccess(3)) {
            return view('pages.access_denied');
        }

        $data = [
            'elementActive' => 'PPMP',
            'inbox_title'   => 'Receiving Inbox',
        ];

        return view('pages.ppmp.inbox', $data);
    }

    /**
     * Query page.
     */
    public function query()
    {
        if (!hasAccess(7)) {
            return view('pages.access_denied');
        }

        $data = [
            'elementActive' => 'PPMP',
        ];

        return view('pages.ppmp.query', $data);
    }

    /**
     * Preparation form.
     */
    public function preparation(Request $request)
    {
        $data = [
            'elementActive'     => 'PPMP',
            'PreparatoryFormat' => $request->route('PreparatoryFormat', ''),
            'date'              => [
                'Year'  => date('Y'),
                'Month' => date('m'),
            ],
            'Stations'           => [],
            'Divisions'          => [],
            'ModesOfProcurement' => [],
            'fundclass'          => session('FundClass', ''),
            'default'            => [
                'EncodedBy'      => session('EmployeeID', ''),
                'EncodedBy_Name' => session('EmployeeName', ''),
            ],
        ];

        $dbHost = env('DB_HOST', 'localhost');
        $dbPort = env('DB_PORT', '1433');
        $conn = @fsockopen($dbHost, $dbPort, $errno, $errstr, 2);

        if ($conn) {
            fclose($conn);
            try {
                $data['Stations'] = \Illuminate\Support\Facades\DB::connection($this->psisConn())
                    ->table('FMIS_Stations')
                    ->pluck('StationName')
                    ->toArray();

                $data['Divisions'] = \Illuminate\Support\Facades\DB::connection($this->psisConn())
                    ->table('FMIS_Divisions')
                    ->pluck('DivisionName')
                    ->toArray();
            } catch (\Exception $e) {
                // Keep defaults
            }
        }

        return view('pages.ppmp.preparation', $data);
    }

    /**
     * AJAX: Count preparation inbox items.
     */
    public function countPreparationInbox()
    {
        $headers = $this->fetchHeaders(['Status' => ['N', 'R'], 'PPMPYear_current' => date('Y') + 1]);
        return response()->json(['count' => count($headers)]);
    }

    /**
     * AJAX: Count evaluation inbox items.
     */
    public function countEvaluationInbox()
    {
        if (!hasAccess(3)) {
            return response()->json(['count' => 0]);
        }
        $headers = $this->fetchHeaders(['Status' => ['D'], 'PPMPYear_current' => date('Y') + 1]);
        return response()->json(['count' => count($headers)]);
    }

    /**
     * AJAX: Count approval inbox items.
     */
    public function countApprovalInbox()
    {
        if (!hasAccess(3)) {
            return response()->json(['count' => 0]);
        }
        $headers = $this->fetchHeaders(['Status' => ['E'], 'PPMPYear_current' => date('Y') + 1]);
        return response()->json(['count' => count($headers)]);
    }

    /**
     * AJAX: Count certification inbox items.
     */
    public function countCertificationInbox()
    {
        if (!hasAccess(6)) {
            return response()->json(['count' => 0]);
        }
        $headers = $this->fetchHeaders(['Status' => ['A'], 'PPMPYear_current' => date('Y') + 1]);
        return response()->json(['count' => count($headers)]);
    }

    /**
     * AJAX: Count receiving inbox items.
     */
    public function countReceivingInbox()
    {
        if (!hasAccess(3)) {
            return response()->json(['count' => 0]);
        }
        $headers = $this->fetchHeaders(['Status' => ['C'], 'PPMPYear_current' => date('Y') + 1]);
        return response()->json(['count' => count($headers)]);
    }

    /**
     * AJAX: Query PPMP headers.
     */
    public function queryPPMP(Request $request)
    {
        $criteria = (array) $request->input('criteria', []);

        // CI uses POST and returns an HTML <tr> list.
        $headers = [];
        try {
            $q = DB::connection($this->psisConn())->table('PPMP_Header');

            if (!empty($criteria['programCode'])) {
                $q->where('ProgramCode', 'LIKE', '%' . $criteria['programCode'] . '%');
            }
            if (!empty($criteria['projectCode'])) {
                $q->where('ProjectCode', 'LIKE', '%' . $criteria['projectCode'] . '%');
            }
            if (!empty($criteria['ppmpYear'])) {
                $q->where('PPMPYear', (int) $criteria['ppmpYear']);
            }
            if (!empty($criteria['preparatoryFormat'])) {
                $q->where('PreparatoryFormat', 'LIKE', '%' . $criteria['preparatoryFormat'] . '%');
            }
            if (!empty($criteria['trackingNo'])) {
                $q->where('TrackingNo', 'LIKE', '%' . $criteria['trackingNo'] . '%');
            }
            if (!empty($criteria['dateFrom'])) {
                $q->whereDate('DateCreated', '>=', $criteria['dateFrom']);
            }
            if (!empty($criteria['dateTo'])) {
                $q->whereDate('DateCreated', '<=', $criteria['dateTo']);
            }

            $headers = $q->orderByDesc('PPMPHeaderID')->limit(200)->get()->map(function ($row) {
                $h = (array) $row;
                $h['PreparedBy_Name'] = Employee::getEmployeeName($h['PreparedBy'] ?? '');
                $h['ApprovedBy_Name'] = Employee::getEmployeeName($h['ApprovedBy'] ?? '');
                $h['CertifiedBy_Name'] = Employee::getEmployeeName($h['CertifiedBy'] ?? '');
                $h['StatusName'] = DB::connection($this->psisConn())
                    ->table('LIB_Status')
                    ->where('StatusAbbr', $h['Status'] ?? '')
                    ->value('StatusName') ?? ($h['Status'] ?? '');
                return $h;
            })->toArray();
        } catch (\Throwable $e) {
            $headers = [];
        }

        $html = "";
        foreach ($headers as $row) {
            $html .= '<tr data-controlno="' . ($row['PreparatoryFormat'] ?? '') . '">
                    <td>' . ($row['PreparatoryFormat'] ?? '') . '</td>
                    <td>' . ($row['ProjectCode'] ?? '') . '</td>
                    <td>' . ($row['ProgramCode'] ?? '') . '</td>
                    <td>' . ($row['PPMPYear'] ?? '') . '</td>
                    <td>' . number_format((float) ($row['TotalAmount'] ?? $row['TotalBudget'] ?? 0), 2) . '</td>
                    <td>' . ($row['PreparedBy_Name'] ?? '') . '</td>
                    <td>' . ($row['ApprovedBy_Name'] ?? '') . '</td>
                    <td>' . ($row['CertifiedBy_Name'] ?? '') . '</td>
                    <td>' . ($row['StatusName'] ?? '') . '</td>
                    <td>' . ($row['DateCreated'] ?? '') . '</td>
                  </tr>';
        }

        return response($html);
    }

    /**
     * AJAX: Get all PPMP headers as JSON.
     */
    public function ajxGetHeaders()
    {
        try {
            $headers = DB::connection($this->psisConn())
                ->table('PPMP_Header')
                ->orderByDesc('PPMPHeaderID')
                ->limit(500)
                ->get()
                ->map(function ($row) {
                    $h = (array) $row;
                    $h['PreparedBy_Name'] = Employee::getEmployeeName($h['PreparedBy'] ?? '');
                    $h['ApprovedBy_Name'] = Employee::getEmployeeName($h['ApprovedBy'] ?? '');
                    $h['CertifiedBy_Name'] = Employee::getEmployeeName($h['CertifiedBy'] ?? '');
                    $h['StatusName'] = DB::connection($this->psisConn())
                        ->table('LIB_Status')
                        ->where('StatusAbbr', $h['Status'] ?? '')
                        ->value('StatusName') ?? ($h['Status'] ?? '');
                    return $h;
                })
                ->toArray();

            return response()->json(['headers' => $headers]);
        } catch (\Throwable $e) {
            return response()->json(['headers' => []]);
        }
    }

    /**
     * AJAX: Save initial PPMP header.
     */
    public function saveInitialHeader(Request $request)
    {
        $header = json_decode(base64_decode((string) $request->input('header')), true) ?: [];

        $projectCode = trim((string) ($header['ProjectCode'] ?? ''));
        $ppmpYear = (int) ($header['PPMPYear'] ?? 0);
        $totalBudget = (float) ($header['TotalBudget'] ?? 0);

        if ($projectCode === '' || $ppmpYear <= 0) {
            return response()->json(['Status' => 'ROLLBACK', 'Message' => 'Project Code and PPMP Year are required.']);
        }

        $encodedBy = (string) session('EmployeeID', '');
        $fundClass = (string) session('FundClass', '');

        // Try to derive ProgramCode from FMIS projects table if available.
        $programCode = '';
        try {
            $programCode = (string) (DB::connection($this->psisConn())
                ->table('FMIS_Projects')
                ->where('ProjectCode', $projectCode)
                ->value('ProgramCode') ?? '');
        } catch (\Throwable $e) {
            $programCode = '';
        }

        try {
            // Prevent duplicates (CI behavior).
            $exists = DB::connection($this->psisConn())
                ->table('PPMP_Header')
                ->where('ProjectCode', $projectCode)
                ->where('PPMPYear', $ppmpYear)
                ->when($programCode !== '', fn ($q) => $q->where('ProgramCode', $programCode))
                ->exists();

            if ($exists) {
                return response()->json(['Status' => 'ROLLBACK', 'Message' => 'Program, Project Code and PPMP Year already exist.']);
            }

            $prepPrefix = (string) $ppmpYear;
            $certifiedBy = '';
            try {
                $certifiedBy = (string) (DB::connection('sqlsrv')
                    ->table('LIB_Parameters')
                    ->where('ParameterName', 'PPMP_CertifiedBy')
                    ->value('ParameterCode') ?? '');
            } catch (\Throwable $e) {
                $certifiedBy = '';
            }

            $result = DB::connection($this->psisConn())->transaction(function () use ($fundClass, $programCode, $projectCode, $ppmpYear, $totalBudget, $encodedBy, $certifiedBy, $prepPrefix) {
                $series = (int) (DB::connection($this->psisConn())
                    ->table('PPMP_Header')
                    ->where('PPMPYear', $ppmpYear)
                    ->max('Series') ?? 0) + 1;

                $headerId = DB::connection($this->psisConn())->table('PPMP_Header')->insertGetId([
                    'FundCd' => null,
                    'FundClass' => $fundClass,
                    'ProgramCode' => $programCode,
                    'ProjectCode' => $projectCode,
                    'PPMPYear' => $ppmpYear,
                    'Series' => $series,
                    'Status' => 'N',
                    'TotalBudget' => $totalBudget,
                    'PreparedBy' => '',
                    'EncodedBy' => $encodedBy,
                    'CertifiedBy' => $certifiedBy,
                    'DateCreated' => date('Y-m-d'),
                ]);

                $prep = $prepPrefix . '-' . str_pad((string) $series, 4, '0', STR_PAD_LEFT) . '-000';

                DB::connection($this->psisConn())->table('PPMP_Header')
                    ->where('PPMPHeaderID', $headerId)
                    ->update(['PreparatoryFormat' => $prep]);

                // History table exists in CI schema; insert if available.
                try {
                    DB::connection($this->psisConn())->table('PPMP_History')->insert([
                        'PPMPHeaderID' => $headerId,
                        'EmployeeID' => $encodedBy,
                        'Status' => 'N',
                        'Remarks' => 'Initial Save',
                        'StatusDate' => date('Y-m-d'),
                        'StatusTime' => date('H:i:s'),
                    ]);
                } catch (\Throwable $e) {
                    // ignore
                }

                return ['PPMPHeaderID' => $headerId, 'PreparatoryFormat' => $prep];
            });

            return response()->json([
                'Status' => 'OK',
                'Message' => 'Saved successfully.',
                'PreparatoryFormat' => $result['PreparatoryFormat'] ?? '',
            ]);
        } catch (\Throwable $e) {
            return response()->json(['Status' => 'ROLLBACK', 'Message' => $e->getMessage()]);
        }
    }

    /**
     * AJAX: Get updated PPMP items for dashboard table.
     */
    public function getUpdatedPPMPItems(Request $request)
    {
        $criteria = $request->input('criteria', []);

        $dbHost = env('DB_HOST', 'localhost');
        $dbPort = env('DB_PORT', '1433');
        $conn = @fsockopen($dbHost, $dbPort, $errno, $errstr, 2);

        $items = [];
        if ($conn) {
            fclose($conn);
            try {
                $query = \Illuminate\Support\Facades\DB::connection($this->psisConn())
                    ->table('PPMP_Details')
                    ->where('PPMPHeaderID', $criteria['PPMPHeaderID'] ?? 0)
                    ->get();

                foreach ($query as $item) {
                    $row = (array) $item;
                    $row['SpecDetails'] = htmlspecialchars($row['SpecDetails'] ?? '', ENT_QUOTES, 'UTF-8', false);
                    $row['MoreSpecs'] = htmlspecialchars($row['MoreSpecs'] ?? '', ENT_QUOTES, 'UTF-8', false);
                    $items[] = $row;
                }
            } catch (\Exception $e) {
                $items = [];
            }
        }

        return response()->json($items);
    }

    /**
     * AJAX: Get one PPMP detail row by PPMPDetailsID.
     * Used by `modal-ppmp-change-item-details`.
     */
    public function getPpmpItem(Request $request)
    {
        $ppmpDetailsId = (int) $request->input('ppmpdetailsid', 0);
        if ($ppmpDetailsId <= 0) {
            return response()->json(['Status' => 'ROLLBACK', 'Message' => 'Invalid PPMP detail id.']);
        }

        try {
            $row = DB::connection($this->psisConn())
                ->table('PPMP_Details')
                ->where('PPMPDetailsID', $ppmpDetailsId)
                ->first();

            if (!$row) {
                return response()->json(['Status' => 'ROLLBACK', 'Message' => 'Record not found.']);
            }

            $data = (array) $row;
            $data['Status'] = 'OK';
            return response()->json($data);
        } catch (\Throwable $e) {
            return response()->json(['Status' => 'ROLLBACK', 'Message' => $e->getMessage()]);
        }
    }

    /**
     * AJAX: Save change-item-details edits for a PPMP detail row.
     * Used by `modal-ppmp-change-item-details`.
     */
    public function saveChangeItemDetails(Request $request)
    {
        $criteria = (array) $request->input('criteria', []);
        $ppmpDetailsId = (int) ($criteria['PPMPDetailsID'] ?? 0);
        if ($ppmpDetailsId <= 0) {
            return response()->json(['Status' => 'ROLLBACK', 'Message' => 'Invalid PPMP detail id.']);
        }

        $update = [
            'ItemID' => (int) ($criteria['ItemID'] ?? 0),
            'MoreSpecs' => (string) ($criteria['MoreSpecs'] ?? ''),
            'OECode' => (string) ($criteria['OECode'] ?? ''),
            'UnitPrice' => (float) ($criteria['UnitPrice'] ?? 0),
            'Qty_Jan' => (int) ($criteria['Qty_Jan'] ?? 0),
            'Qty_Feb' => (int) ($criteria['Qty_Feb'] ?? 0),
            'Qty_Mar' => (int) ($criteria['Qty_Mar'] ?? 0),
            'Qty_Apr' => (int) ($criteria['Qty_Apr'] ?? 0),
            'Qty_May' => (int) ($criteria['Qty_May'] ?? 0),
            'Qty_Jun' => (int) ($criteria['Qty_Jun'] ?? 0),
            'Qty_Jul' => (int) ($criteria['Qty_Jul'] ?? 0),
            'Qty_Aug' => (int) ($criteria['Qty_Aug'] ?? 0),
            'Qty_Sep' => (int) ($criteria['Qty_Sep'] ?? 0),
            'Qty_Oct' => (int) ($criteria['Qty_Oct'] ?? 0),
            'Qty_Nov' => (int) ($criteria['Qty_Nov'] ?? 0),
            'Qty_Dec' => (int) ($criteria['Qty_Dec'] ?? 0),
            'UpdatedBy' => session('EmployeeID'),
            'DateUpdated' => date('Y-m-d'),
        ];

        // Basic guard aligned with UI constraints
        if (strlen($update['MoreSpecs']) > 4000) {
            $update['MoreSpecs'] = substr($update['MoreSpecs'], 0, 4000);
        }

        try {
            DB::connection($this->psisConn())
                ->table('PPMP_Details')
                ->where('PPMPDetailsID', $ppmpDetailsId)
                ->update($update);

            return response()->json(['Status' => 'COMMIT', 'Message' => 'Updated successfully.']);
        } catch (\Throwable $e) {
            return response()->json(['Status' => 'ROLLBACK', 'Message' => $e->getMessage()]);
        }
    }

    /**
     * Export PPMP (placeholder).
     * CI produces an Excel file; Laravel implementation to be completed.
     */
    public function exportPpmp(string $controlno, ?string $updated = null)
    {
        return response("Export not yet implemented for Laravel.\nControlNo: {$controlno}\n", 200)
            ->header('Content-Type', 'text/plain; charset=UTF-8');
    }

    /**
     * PPMP Utilization page (placeholder).
     */
    public function utilization()
    {
        return response('PPMP Utilization not yet implemented in Laravel.', 200);
    }

    /**
     * PPMP Utilization Per Object page (placeholder).
     */
    public function utilizationPerObject()
    {
        return response('PPMP Utilization Per Object not yet implemented in Laravel.', 200);
    }

    /**
     * Helper: fetch PPMP headers with criteria, with DB reachability check.
     */
    private function fetchHeaders(array $criteria = [])
    {
        $dbHost = env('DB_HOST', 'localhost');
        $dbPort = env('DB_PORT', '1433');
        $conn = @fsockopen($dbHost, $dbPort, $errno, $errstr, 2);

        if (!$conn) {
            return [];
        }
        fclose($conn);

        try {
            $query = \Illuminate\Support\Facades\DB::connection($this->psisConn())
                ->table('PPMP_Header')
                ->where('PPMPYear', date('Y') + 1);

            if (!empty($criteria['Status'])) {
                $query->whereIn('Status', (array) $criteria['Status']);
            }
            if (!empty($criteria['PreparedBy'])) {
                $query->where('PreparedBy', $criteria['PreparedBy']);
            }
            if (!empty($criteria['ApprovedBy'])) {
                $query->where('ApprovedBy', $criteria['ApprovedBy']);
            }
            if (!empty($criteria['CertifiedBy'])) {
                $query->where('CertifiedBy', $criteria['CertifiedBy']);
            }
            if (!empty($criteria['EncodedBy'])) {
                $query->where(function ($q) use ($criteria) {
                    $q->where('EncodedBy', $criteria['EncodedBy'])
                      ->orWhere('PreparedBy', $criteria['EncodedBy']);
                });
            }

            $headers = $query->get()->map(function ($item) {
                return (array) $item;
            })->toArray();

            foreach ($headers as &$header) {
                $header['PreparedBy_Name']  = Employee::getEmployeeName($header['PreparedBy'] ?? '');
                $header['ApprovedBy_Name']  = Employee::getEmployeeName($header['ApprovedBy'] ?? '');
                $header['CertifiedBy_Name'] = Employee::getEmployeeName($header['CertifiedBy'] ?? '');

                $status = DB::connection($this->psisConn())
                    ->table('LIB_Status')
                    ->where('StatusAbbr', $header['Status'] ?? '')
                    ->value('StatusName');
                $header['StatusName'] = $status ?: ($header['Status'] ?? '');
            }

            return $headers;
        } catch (\Exception $e) {
            return [];
        }
    }
}
