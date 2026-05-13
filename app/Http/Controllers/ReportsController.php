<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ReportsController extends Controller
{
    public function __construct()
    {
        // Add middleware if needed
        // $this->middleware('auth');
    }

    public function prMonitoring()
    {
        $data = [];
        $data['elementActive'] = 'REPORTS';
        return view('pages.reports.pr_monitoring', $data);
    }

    public function prMonitoringV2()
    {
        $data = [];
        $data['elementActive'] = 'REPORTS';
        return view('pages.reports.pr_monitoring_v2', $data);
    }

    public function getExtractPrMonitoring(Request $request)
    {
        $criteria = $request->get('criteria', []);
        
        // Get PR monitoring data
        $data = $this->getPRMonitoringReport($criteria);
        
        foreach ($data as &$row) {
            $row['PreparedBy'] = $this->getEmployeeName($row['PreparedBy']);
            $row['RequestedBy'] = $this->getEmployeeName($row['RequestedBy']);
            $row['ApprovedBy'] = $this->getEmployeeName($row['ApprovedBy']);
            $row['Canvasser_Name'] = $this->getEmployeeName($row['CanvasserID']);

            $respoCenter = $this->getResponsibilityCenter($row['RespoCenter']);
            $row['RespoCenterName'] = $respoCenter['RCDesc'] ?? '';

            // Get PO numbers
            $poNumbers = DB::table('PODetails')
                ->where('PRDetailsID', $row['PRDetailsID'])
                ->where('Cancelled', 0)
                ->pluck('PONo')
                ->toArray();
            $row['PONo'] = implode(', ', $poNumbers);

            // Get IAR numbers
            $iarNumbers = DB::table('IARDetails')
                ->where('PRDetailsID', $row['PRDetailsID'])
                ->where('Cancelled', 0)
                ->pluck('IARNo')
                ->toArray();
            $row['IARNo'] = implode(', ', $iarNumbers);

            $row['Remarks'] = $this->getLastPRHistory($row['PRHeaderID']);
            
            // Remove sensitive fields
            unset($row['RFQDetailsID']);
            unset($row['PRDetailsID']);
            unset($row['PRHeaderID']);
            unset($row['CanvasserID']);
        }
        
        return response()->json($data);
    }

    public function extractPrMonitoring(Request $request)
    {
        $criteria = $request->get('criteria', []);
        
        // Get data for export
        $data = $this->getPRMonitoringReport($criteria);
        
        foreach ($data as &$row) {
            $row['PreparedBy'] = $this->getEmployeeName($row['PreparedBy']);
            $row['RequestedBy'] = $this->getEmployeeName($row['RequestedBy']);
            $row['ApprovedBy'] = $this->getEmployeeName($row['ApprovedBy']);
            $row['Canvasser_Name'] = $this->getEmployeeName($row['CanvasserID']);

            $respoCenter = $this->getResponsibilityCenter($row['RespoCenter']);
            $row['RespoCenterName'] = $respoCenter['RCDesc'] ?? '';

            // Get PO numbers
            $poNumbers = DB::table('PODetails')
                ->where('PRDetailsID', $row['PRDetailsID'])
                ->where('Cancelled', 0)
                ->pluck('PONo')
                ->toArray();
            $row['PONo'] = implode(', ', $poNumbers);

            // Get IAR numbers
            $iarNumbers = DB::table('IARDetails')
                ->where('PRDetailsID', $row['PRDetailsID'])
                ->where('Cancelled', 0)
                ->pluck('IARNo')
                ->toArray();
            $row['IARNo'] = implode(', ', $iarNumbers);

            $row['Remarks'] = $this->getLastPRHistory($row['PRHeaderID']);
        }
        
        // Generate Excel file (implementation depends on your Excel library)
        return $this->generateExcelReport($data, 'PR_Monitoring_Report');
    }

    public function abstractMonitoring()
    {
        $data = [];
        $data['elementActive'] = 'REPORTS';
        return view('pages.reports.abstract_monitoring', $data);
    }

    public function getExtractAbstractMonitoring(Request $request)
    {
        $criteria = $request->get('criteria', []);
        
        // Get abstract monitoring data
        $data = $this->getAbstractMonitoringReport($criteria);
        
        return response()->json($data);
    }

    public function extractAbstractMonitoring(Request $request)
    {
        $criteria = $request->get('criteria', []);
        
        // Get data for export
        $data = $this->getAbstractMonitoringReport($criteria);
        
        // Generate Excel file
        return $this->generateExcelReport($data, 'Abstract_Monitoring_Report');
    }

    public function poMonitoring()
    {
        $data = [];
        $data['elementActive'] = 'REPORTS';
        return view('pages.reports.po_monitoring', $data);
    }

    public function getExtractPoMonitoring(Request $request)
    {
        $criteria = $request->get('criteria', []);
        
        // Get PO monitoring data
        $data = $this->getPOMonitoringReport($criteria);
        
        foreach ($data as &$row) {
            $row['PreparedBy'] = $this->getEmployeeName($row['PreparedBy']);
            $row['ApprovedBy'] = $this->getEmployeeName($row['ApprovedBy']);
            $row['SupplierName'] = $this->getSupplierName($row['SupplierID']);
        }
        
        return response()->json($data);
    }

    public function extractPoMonitoring(Request $request)
    {
        $criteria = $request->get('criteria', []);
        
        // Get data for export
        $data = $this->getPOMonitoringReport($criteria);
        
        foreach ($data as &$row) {
            $row['PreparedBy'] = $this->getEmployeeName($row['PreparedBy']);
            $row['ApprovedBy'] = $this->getEmployeeName($row['ApprovedBy']);
            $row['SupplierName'] = $this->getSupplierName($row['SupplierID']);
        }
        
        // Generate Excel file
        return $this->generateExcelReport($data, 'PO_Monitoring_Report');
    }

    // Report views for different modules
    public function ics()
    {
        $data = [];
        $data['elementActive'] = 'REPORTS';
        return view('pages.reports.ics', $data);
    }

    public function iar()
    {
        $data = [];
        $data['elementActive'] = 'REPORTS';
        return view('pages.reports.iar', $data);
    }

    public function pal()
    {
        $data = [];
        $data['elementActive'] = 'REPORTS';
        return view('pages.reports.pal', $data);
    }

    public function par()
    {
        $data = [];
        $data['elementActive'] = 'REPORTS';
        return view('pages.reports.par', $data);
    }

    public function pmr()
    {
        $data = [];
        $data['elementActive'] = 'REPORTS';
        return view('pages.reports.pmr', $data);
    }

    public function po()
    {
        $data = [];
        $data['elementActive'] = 'REPORTS';
        return view('pages.reports.po', $data);
    }

    public function ris()
    {
        $data = [];
        $data['elementActive'] = 'REPORTS';
        return view('pages.reports.ris', $data);
    }

    // Helper methods
    private function getPRMonitoringReport($criteria)
    {
        $query = DB::table('PRHeaders')
            ->leftJoin('PRDetails', 'PRHeaders.PRHeaderID', '=', 'PRDetails.PRHeaderID')
            ->leftJoin('Employees', 'PRHeaders.PreparedBy', '=', 'Employees.EmployeeID')
            ->leftJoin('Divisions', 'PRHeaders.DivCode', '=', 'Divisions.DivCode')
            ->select(
                'PRHeaders.*',
                'PRDetails.*',
                'PRHeaders.DateCreated as PRDate',
                'Employees.EmployeeName as PreparedByName'
            );

        // Apply criteria
        if (isset($criteria['DateFrom']) && $criteria['DateFrom']) {
            $query->whereDate('PRHeaders.DateCreated', '>=', $criteria['DateFrom']);
        }
        if (isset($criteria['DateTo']) && $criteria['DateTo']) {
            $query->whereDate('PRHeaders.DateCreated', '<=', $criteria['DateTo']);
        }
        if (isset($criteria['DivCode']) && $criteria['DivCode']) {
            $query->where('PRHeaders.DivCode', $criteria['DivCode']);
        }
        if (isset($criteria['Status']) && $criteria['Status']) {
            $query->where('PRHeaders.Status', $criteria['Status']);
        }

        return $query->get()->toArray();
    }

    private function getAbstractMonitoringReport($criteria)
    {
        $query = DB::table('AbstractHeaders')
            ->leftJoin('Divisions', 'AbstractHeaders.DivCode', '=', 'Divisions.DivCode')
            ->leftJoin('Suppliers', 'AbstractHeaders.SupplierID', '=', 'Suppliers.SupplierID')
            ->select(
                'AbstractHeaders.*',
                'Divisions.DivName',
                'Suppliers.SupplierName'
            );

        // Apply criteria
        if (isset($criteria['DateFrom']) && $criteria['DateFrom']) {
            $query->whereDate('AbstractHeaders.DateCreated', '>=', $criteria['DateFrom']);
        }
        if (isset($criteria['DateTo']) && $criteria['DateTo']) {
            $query->whereDate('AbstractHeaders.DateCreated', '<=', $criteria['DateTo']);
        }
        if (isset($criteria['PPMPYear']) && $criteria['PPMPYear']) {
            $query->whereYear('AbstractHeaders.DateCreated', $criteria['PPMPYear']);
        }

        return $query->get()->toArray();
    }

    private function getPOMonitoringReport($criteria)
    {
        $query = DB::table('POHeaders')
            ->leftJoin('Suppliers', 'POHeaders.SupplierID', '=', 'Suppliers.SupplierID')
            ->leftJoin('Divisions', 'POHeaders.DivCode', '=', 'Divisions.DivCode')
            ->select(
                'POHeaders.*',
                'Suppliers.SupplierName',
                'Divisions.DivName'
            );

        // Apply criteria
        if (isset($criteria['DateFrom']) && $criteria['DateFrom']) {
            $query->whereDate('POHeaders.DateCreated', '>=', $criteria['DateFrom']);
        }
        if (isset($criteria['DateTo']) && $criteria['DateTo']) {
            $query->whereDate('POHeaders.DateCreated', '<=', $criteria['DateTo']);
        }
        if (isset($criteria['DivCode']) && $criteria['DivCode']) {
            $query->where('POHeaders.DivCode', $criteria['DivCode']);
        }
        if (isset($criteria['Status']) && $criteria['Status']) {
            $query->where('POHeaders.Status', $criteria['Status']);
        }

        return $query->get()->toArray();
    }

    private function getEmployeeName($employeeId)
    {
        $employee = DB::table('Employees')
            ->where('EmployeeID', $employeeId)
            ->first();
        
        return $employee ? $employee->EmployeeName : '';
    }

    private function getResponsibilityCenter($rcCode)
    {
        $rc = DB::table('ResponsibilityCenters')
            ->where('RCCD', $rcCode)
            ->first();
        
        return $rc ? (array) $rc : [];
    }

    private function getSupplierName($supplierId)
    {
        $supplier = DB::table('Suppliers')
            ->where('SupplierID', $supplierId)
            ->first();
        
        return $supplier ? $supplier->SupplierName : '';
    }

    private function getLastPRHistory($prHeaderId)
    {
        $history = DB::table('PRHistory')
            ->where('PRHeaderID', $prHeaderId)
            ->orderBy('DateCreated', 'desc')
            ->first();
        
        return $history ? $history->Remarks : '';
    }

    private function generateExcelReport($data, $filename)
    {
        // This would typically use a library like Laravel Excel or PHPSpreadsheet
        // For now, return a JSON response as placeholder
        return response()->json([
            'message' => 'Excel export functionality to be implemented',
            'data_count' => count($data),
            'filename' => $filename
        ]);
    }
}
