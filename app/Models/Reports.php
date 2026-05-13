<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Reports extends Model
{
    use HasFactory;

    // PR Monitoring Report
    public static function getPRMonitoringReport($criteria = [])
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

    // Abstract Monitoring Report
    public static function getAbstractMonitoringReport($criteria = [])
    {
        $query = DB::table('Abstract_Header')
            ->leftJoin('Divisions', 'Abstract_Header.DivCode', '=', 'Divisions.DivCode')
            ->leftJoin('Suppliers', 'Abstract_Header.SupplierID', '=', 'Suppliers.SupplierID')
            ->select(
                'Abstract_Header.*',
                'Divisions.DivName',
                'Suppliers.SupplierName'
            );

        // Apply criteria
        if (isset($criteria['DateFrom']) && $criteria['DateFrom']) {
            $query->whereDate('Abstract_Header.DateCreated', '>=', $criteria['DateFrom']);
        }
        if (isset($criteria['DateTo']) && $criteria['DateTo']) {
            $query->whereDate('Abstract_Header.DateCreated', '<=', $criteria['DateTo']);
        }
        if (isset($criteria['PPMPYear']) && $criteria['PPMPYear']) {
            $query->whereYear('Abstract_Header.DateCreated', $criteria['PPMPYear']);
        }

        return $query->get()->toArray();
    }

    // PO Monitoring Report
    public static function getPOMonitoringReport($criteria = [])
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

    // IAR Details Extraction
    public static function extractIARDetails($criteria = [])
    {
        $query = DB::table('IAR_Details')
            ->leftJoin('IAR_Header', 'IAR_Details.IARHeaderID', '=', 'IAR_Header.IARHeaderID')
            ->leftJoin('StockCard_Header', 'IAR_Details.StockHeaderID', '=', 'StockCard_Header.StockHeaderID')
            ->leftJoin('LIB_Suppliers', 'IAR_Header.SupplierID', '=', 'LIB_Suppliers.SupplierID')
            ->leftJoin('LIB_Items', 'StockCard_Header.ItemID', '=', 'LIB_Items.ItemID')
            ->leftJoin('PR_Details', 'IAR_Details.PRDetailsID', '=', 'PR_Details.PRDetailsID')
            ->leftJoin('PO_Details', 'IAR_Details.PODetailsID', '=', 'PO_Details.PODetailsID')
            ->leftJoin('PO_Header', 'PO_Details.POHeaderID', '=', 'PO_Header.POHeaderID')
            ->leftJoin('PPMP_Details', 'PR_Details.PPMPDetailsID', '=', 'PPMP_Details.PPMPDetailsID')
            ->leftJoin('PPMP_Header', 'PPMP_Details.PPMPHeaderID', '=', 'PPMP_Header.PPMPHeaderID')
            ->select(
                'StockCard_Header.StockCode',
                'StockCard_Header.StockFullDesc',
                'IAR_Details.IARSpecs',
                'IAR_Details.IARUnitName',
                'IAR_Details.IARQuantity',
                'IAR_Details.IARUnitPrice',
                'IAR_Header.IARControlNo',
                'IAR_Header.IARNo',
                'PR_Details.PRNo',
                'PO_Header.PONo',
                'IAR_Header.ORsControlNo',
                'IAR_Header.InvoiceDate',
                'IAR_Header.DeliveryDate',
                'Divisions.DivCode',
                'PPMP_Header.ProjectCode',
                'LIB_Suppliers.SupplierName',
                'IAR_Header.Remarks',
                'IAR_Header.PreparedBy',
                'IAR_Header.DateCreated',
                'IAR_Header.AcceptedBy',
                'IAR_Header.DateReceived',
                'IAR_Header.DateNumbered',
                'IAR_Header.Reimbursement',
                'PPMP_Header.ProCode'
            )
            ->where('IAR_Details.Earmarked', 1)
            ->where('IAR_Details.Cancelled', 0);

        // Apply criteria
        if (isset($criteria['DateFrom']) && isset($criteria['DateTo'])) {
            if ($criteria['DateFrom'] != '' && $criteria['DateTo'] != '') {
                $query->whereBetween('IAR_Header.DateCreated', [$criteria['DateFrom'], $criteria['DateTo']]);
            }
        }

        if (isset($criteria['Reimbursement'])) {
            $query->where('IAR_Header.Reimbursement', $criteria['Reimbursement']);
        }

        if (isset($criteria['PPMPYear']) && $criteria['PPMPYear'] != "") {
            $query->where('PPMP_Header.PPMPYear', $criteria['PPMPYear']);
        }

        return $query->get()->toArray();
    }

    // PO Headers Extraction
    public static function extractPOHeaders($criteria = [])
    {
        $query = DB::table('POHeaders')
            ->select('POHeaders.*');

        // Apply criteria
        if (isset($criteria['DateFrom']) && isset($criteria['DateTo'])) {
            if ($criteria['DateFrom'] != '' && $criteria['DateTo'] != '') {
                $query->whereBetween('POHeaders.DateCreated', [$criteria['DateFrom'], $criteria['DateTo']]);
            }
        }

        if (isset($criteria['DivCode'])) {
            $query->where('POHeaders.DivCode', $criteria['DivCode']);
        }

        if (isset($criteria['Status'])) {
            $query->where('POHeaders.Status', $criteria['Status']);
        }

        return $query->get()->toArray();
    }

    // Helper methods
    public static function getEmployeeName($employeeId)
    {
        $employee = DB::table('Employees')
            ->where('EmployeeID', $employeeId)
            ->first();
        
        return $employee ? $employee->EmployeeName : '';
    }

    public static function getResponsibilityCenter($rcCode)
    {
        $rc = DB::table('ResponsibilityCenters')
            ->where('RCCD', $rcCode)
            ->first();
        
        return $rc ? (array) $rc : [];
    }

    public static function getSupplierName($supplierId)
    {
        $supplier = DB::table('Suppliers')
            ->where('SupplierID', $supplierId)
            ->first();
        
        return $supplier ? $supplier->SupplierName : '';
    }

    public static function getLastPRHistory($prHeaderId)
    {
        $history = DB::table('PRHistory')
            ->where('PRHeaderID', $prHeaderId)
            ->orderBy('DateCreated', 'desc')
            ->first();
        
        return $history ? $history->Remarks : '';
    }
}
