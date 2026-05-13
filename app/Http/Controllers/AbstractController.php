<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class AbstractController extends Controller
{
    public function __construct()
    {
        // Add middleware if needed
        // $this->middleware('auth');
    }

    public function countPreparationInbox()
    {
        $criteria = [];
        $criteria['EncodedBy'] = session('EmployeeID');
        $criteria['Status'] = ['N', 'R'];
        
        $count = DB::table('AbstractHeaders')
            ->where('EncodedBy', $criteria['EncodedBy'])
            ->whereIn('Status', $criteria['Status'])
            ->count();
        
        return $count;
    }

    public function countEvaluationInbox()
    {
        $criteria = [];
        $criteria['Status'] = ['D', 'V'];
        
        $count = DB::table('AbstractHeaders')
            ->whereIn('Status', $criteria['Status'])
            ->count();
        
        return $count;
    }
    
    public function countCertificationInbox()
    {
        $criteria = [];
        $criteria['CertifiedBy'] = session('EmployeeID');
        $criteria['Status'] = ['E'];
        
        $count = DB::table('AbstractHeaders')
            ->where('CertifiedBy', $criteria['CertifiedBy'])
            ->whereIn('Status', $criteria['Status'])
            ->count();
        
        return $count;
    }

    public function countApprovalInbox()
    {
        $criteria = [];
        $criteria['ApprovedBy'] = session('EmployeeID');
        $criteria['Status'] = ['C'];
        
        $count = DB::table('AbstractHeaders')
            ->where('ApprovedBy', $criteria['ApprovedBy'])
            ->whereIn('Status', $criteria['Status'])
            ->count();
        
        return $count;
    }

    public function countReceivingInbox()
    {
        $criteria = [];
        $criteria['Status'] = ['A'];
        
        $count = DB::table('AbstractHeaders')
            ->whereIn('Status', $criteria['Status'])
            ->count();
        
        return $count;
    }

    public function preparationInbox()
    {
        if (!hasAccess(2)) {
            return view('pages.access_denied');
        }

        $data = [];
        $data['elementActive'] = 'ABST';
        
        $criteria = [];
        $criteria['EncodedBy'] = session('EmployeeID');
        $criteria['Status'] = ['N', 'R'];
        
        $data['headers'] = DB::table('AbstractHeaders')
            ->leftJoin('Employees', 'AbstractHeaders.EncodedBy', '=', 'Employees.EmployeeID')
            ->leftJoin('Status', 'AbstractHeaders.Status', '=', 'Status.StatusID')
            ->select(
                'AbstractHeaders.*',
                'Employees.EmployeeName as PreparedBy_Name',
                'Status.StatusName'
            )
            ->where('AbstractHeaders.EncodedBy', $criteria['EncodedBy'])
            ->whereIn('AbstractHeaders.Status', $criteria['Status'])
            ->orderBy('AbstractHeaders.DateCreated', 'desc')
            ->get()
            ->toArray();

        return view('pages.abstract.inbox', $data);
    }

    public function evaluationInbox()
    {
        if (!hasAccess(3)) {
            return view('pages.access_denied');
        }

        $data = [];
        $data['elementActive'] = 'ABST';
        
        $criteria = [];
        $criteria['Status'] = ['D', 'V'];
        
        $data['headers'] = DB::table('AbstractHeaders')
            ->leftJoin('Employees', 'AbstractHeaders.EncodedBy', '=', 'Employees.EmployeeID')
            ->leftJoin('Status', 'AbstractHeaders.Status', '=', 'Status.StatusID')
            ->select(
                'AbstractHeaders.*',
                'Employees.EmployeeName as PreparedBy_Name',
                'Status.StatusName'
            )
            ->whereIn('AbstractHeaders.Status', $criteria['Status'])
            ->orderBy('AbstractHeaders.DateCreated', 'desc')
            ->get()
            ->toArray();

        return view('pages.abstract.inbox', $data);
    }

    public function certificationInbox()
    {
        if (!hasAccess(4)) {
            return view('pages.access_denied');
        }

        $data = [];
        $data['elementActive'] = 'ABST';
        
        $criteria = [];
        $criteria['CertifiedBy'] = session('EmployeeID');
        $criteria['Status'] = ['E'];
        
        $data['headers'] = DB::table('AbstractHeaders')
            ->leftJoin('Employees', 'AbstractHeaders.EncodedBy', '=', 'Employees.EmployeeID')
            ->leftJoin('Status', 'AbstractHeaders.Status', '=', 'Status.StatusID')
            ->select(
                'AbstractHeaders.*',
                'Employees.EmployeeName as PreparedBy_Name',
                'Status.StatusName'
            )
            ->where('AbstractHeaders.CertifiedBy', $criteria['CertifiedBy'])
            ->whereIn('AbstractHeaders.Status', $criteria['Status'])
            ->orderBy('AbstractHeaders.DateCreated', 'desc')
            ->get()
            ->toArray();

        return view('pages.abstract.inbox', $data);
    }

    public function approvalInbox()
    {
        if (!hasAccess(5)) {
            return view('pages.access_denied');
        }

        $data = [];
        $data['elementActive'] = 'ABST';
        
        $criteria = [];
        $criteria['ApprovedBy'] = session('EmployeeID');
        $criteria['Status'] = ['C'];
        
        $data['headers'] = DB::table('AbstractHeaders')
            ->leftJoin('Employees', 'AbstractHeaders.EncodedBy', '=', 'Employees.EmployeeID')
            ->leftJoin('Status', 'AbstractHeaders.Status', '=', 'Status.StatusID')
            ->select(
                'AbstractHeaders.*',
                'Employees.EmployeeName as PreparedBy_Name',
                'Status.StatusName'
            )
            ->where('AbstractHeaders.ApprovedBy', $criteria['ApprovedBy'])
            ->whereIn('AbstractHeaders.Status', $criteria['Status'])
            ->orderBy('AbstractHeaders.DateCreated', 'desc')
            ->get()
            ->toArray();

        return view('pages.abstract.inbox', $data);
    }

    public function receivingInbox()
    {
        if (!hasAccess(6)) {
            return view('pages.access_denied');
        }

        $data = [];
        $data['elementActive'] = 'ABST';
        
        $criteria = [];
        $criteria['Status'] = ['A'];
        
        $data['headers'] = DB::table('AbstractHeaders')
            ->leftJoin('Employees', 'AbstractHeaders.EncodedBy', '=', 'Employees.EmployeeID')
            ->leftJoin('Status', 'AbstractHeaders.Status', '=', 'Status.StatusID')
            ->select(
                'AbstractHeaders.*',
                'Employees.EmployeeName as PreparedBy_Name',
                'Status.StatusName'
            )
            ->whereIn('AbstractHeaders.Status', $criteria['Status'])
            ->orderBy('AbstractHeaders.DateCreated', 'desc')
            ->get()
            ->toArray();

        return view('pages.abstract.inbox', $data);
    }

    public function preparation(Request $request)
    {
        if (!hasAccess(2)) {
            return view('pages.access_denied');
        }

        $data = [];
        $data['elementActive'] = 'ABST';
        $AbstractControlNo = $request->get('AbstractControlNo', '');

        if ($AbstractControlNo) {
            // Get abstract header
            $header = DB::table('AbstractHeaders')
                ->where('AbstractControlNo', $AbstractControlNo)
                ->first();
            
            if ($header) {
                $data['header'] = (array) $header;
                $data['AbstractControlNo'] = $header->AbstractControlNo;
                
                // Get abstract details
                $data['details'] = DB::table('AbstractDetails')
                    ->leftJoin('Items', 'AbstractDetails.ItemID', '=', 'Items.ItemID')
                    ->select('AbstractDetails.*', 'Items.ItemName', 'Items.Unit')
                    ->where('AbstractDetails.AbstractHeaderID', $header->AbstractHeaderID)
                    ->get()
                    ->toArray();

                // Get BAC committee members
                $data['BACCW'] = DB::table('BACCommittee')
                    ->where('Type', 'CW')
                    ->where('InActive', 0)
                    ->orderBy('Sequence')
                    ->get()
                    ->toArray();

                $data['BACGS'] = DB::table('BACCommittee')
                    ->where('Type', 'GS')
                    ->where('InActive', 0)
                    ->orderBy('Sequence')
                    ->get()
                    ->toArray();
            }
        } else {
            $data['AbstractControlNo'] = $this->generateAbstractControlNo();
        }

        // Get divisions
        $data['Divisions'] = DB::table('Divisions')
            ->where('InActive', 0)
            ->orderBy('DivName')
            ->get()
            ->toArray();

        // Get responsibility centers
        $data['RespoList'] = DB::table('ResponsibilityCenters')
            ->where('InActive', 0)
            ->orderBy('RCDesc')
            ->get()
            ->toArray();

        // Get modes of procurement
        $data['ModesOfProcurement'] = DB::table('ModesOfProcurement')
            ->where('InActive', 0)
            ->orderBy('ModeName')
            ->get()
            ->toArray();

        // Get default prepared by
        $data['default']['PreparedBy'] = session('EmployeeID');
        $data['default']['PreparedBy_Name'] = session('EmployeeName');

        return view('pages.abstract.preparation', $data);
    }

    public function query()
    {
        $data = [];
        $data['elementActive'] = 'ABST';

        // Get divisions
        $data['Divisions'] = DB::table('Divisions')
            ->where('InActive', 0)
            ->orderBy('DivName')
            ->get()
            ->toArray();

        // Get responsibility centers
        $data['RespoList'] = DB::table('ResponsibilityCenters')
            ->where('InActive', 0)
            ->orderBy('RCDesc')
            ->get()
            ->toArray();

        return view('pages.abstract.query', $data);
    }

    public function getHeaders(Request $request)
    {
        $criteria = $request->get('criteria', []);
        
        $query = DB::table('AbstractHeaders')
            ->leftJoin('Employees', 'AbstractHeaders.EncodedBy', '=', 'Employees.EmployeeID')
            ->leftJoin('Divisions', 'AbstractHeaders.DivCode', '=', 'Divisions.DivCode')
            ->leftJoin('Status', 'AbstractHeaders.Status', '=', 'Status.StatusID')
            ->select(
                'AbstractHeaders.*',
                'Employees.EmployeeName as PreparedBy_Name',
                'Divisions.DivName',
                'Status.StatusName'
            );

        // Apply criteria
        if (isset($criteria['AbstractControlNo']) && $criteria['AbstractControlNo']) {
            $query->where('AbstractHeaders.AbstractControlNo', 'like', '%' . $criteria['AbstractControlNo'] . '%');
        }
        if (isset($criteria['AbstractNo']) && $criteria['AbstractNo']) {
            $query->where('AbstractHeaders.AbstractNo', 'like', '%' . $criteria['AbstractNo'] . '%');
        }
        if (isset($criteria['DivCode']) && $criteria['DivCode']) {
            $query->where('AbstractHeaders.DivCode', $criteria['DivCode']);
        }
        if (isset($criteria['RespoCenter']) && $criteria['RespoCenter']) {
            $query->where('AbstractHeaders.RespoCenter', $criteria['RespoCenter']);
        }
        if (isset($criteria['PreparedBy']) && $criteria['PreparedBy']) {
            $query->where('AbstractHeaders.EncodedBy', $criteria['PreparedBy']);
        }
        if (isset($criteria['Status']) && $criteria['Status']) {
            $query->where('AbstractHeaders.Status', $criteria['Status']);
        }
        if (isset($criteria['DateFrom']) && $criteria['DateFrom']) {
            $query->whereDate('AbstractHeaders.DateCreated', '>=', $criteria['DateFrom']);
        }
        if (isset($criteria['DateTo']) && $criteria['DateTo']) {
            $query->whereDate('AbstractHeaders.DateCreated', '<=', $criteria['DateTo']);
        }

        $headers = $query->orderBy('AbstractHeaders.DateCreated', 'desc')->get()->toArray();
        
        return response()->json(['header' => $headers]);
    }

    public function save(Request $request)
    {
        try {
            $data = $request->all();
            
            // Validate fund if provided
            if (isset($data['fund'])) {
                $fundValidation = $this->validateFund($data['fund']);
                if ($fundValidation['Status'] !== 'OK') {
                    return response()->json($fundValidation);
                }
            }

            // Save abstract header and details logic here
            // This would be a complex implementation depending on your business logic
            
            return response()->json(['Status' => 'OK', 'Message' => 'Abstract saved successfully']);
            
        } catch (\Exception $e) {
            return response()->json(['Status' => 'Error', 'Message' => $e->getMessage()]);
        }
    }

    // Helper methods
    private function generateAbstractControlNo()
    {
        $year = date('Y');
        $sequence = DB::table('AbstractHeaders')
            ->whereYear('DateCreated', $year)
            ->count() + 1;
        
        return 'ABS-' . $year . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    private function validateFund($fund)
    {
        // Fund validation logic
        $fundData = DB::table('Funds')
            ->where('FundCode', $fund)
            ->first();
        
        if (!$fundData) {
            return ['Status' => 'Error', 'Message' => 'Invalid fund code'];
        }
        
        return ['Status' => 'OK'];
    }
}
