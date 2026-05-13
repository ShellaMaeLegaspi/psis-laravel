<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class IIRUPController extends Controller
{
    public function __construct()
    {
        // Add middleware if needed
        // $this->middleware('auth');
    }

    public function save(Request $request)
    {
        $header = $request->get('header', []);
        $details = json_decode($request->get('details', '[]'), true);
        
        try {
            // Save IIRUP header and details
            $result = $this->saveIIRUP($header, $details);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['Status' => 'Error', 'Message' => $e->getMessage()]);
        }
    }

    public function get($controlno = 0, ?Request $request = null)
    {
        if ($controlno == 0) {
            $use_controlno = $request ? $request->get('controlno', '') : '';
        } else {
            $use_controlno = $controlno;
        }

        $data = [];
        
        // Get IIRUP header
        $header = DB::table('IIRUPHeaders')
            ->where('IIRUPControlNo', $use_controlno)
            ->first();
        
        if ($header) {
            $data['header'] = (array) $header;
            
            // Get IIRUP details
            $data['details'] = DB::table('IIRUPDetails')
                ->where('IIRUPHeaderID', $header->IIRUPHeaderID)
                ->get()
                ->toArray();

            // Get status name
            $status = DB::table('Status')
                ->where('StatusID', $header->Status)
                ->first();
            $data['header']['StatusName'] = $status ? $status->StatusName : '';

            // Get employee names and designations
            $data['header']['PreparedBy_Name'] = $this->getEmployeeName($header->PreparedBy);
            $data['header']['RequestedBy_Name'] = $this->getEmployeeName($header->RequestedBy);
            $data['header']['InspectedBy_Name'] = $this->getEmployeeName($header->InspectedBy);
            $data['header']['ApprovedBy_Name'] = $this->getEmployeeName($header->ApprovedBy);
            $data['header']['WitnessBy_Name'] = $this->getEmployeeName($header->WitnessBy);

            $data['header']['PreparedBy_Designation'] = $this->getEmployeeDesignation($header->PreparedBy);
            $data['header']['RequestedBy_Designation'] = $this->getEmployeeDesignation($header->RequestedBy);
            $data['header']['InspectedBy_Designation'] = $this->getEmployeeDesignation($header->InspectedBy);
            $data['header']['ApprovedBy_Designation'] = $this->getEmployeeDesignation($header->ApprovedBy);
            $data['header']['WitnessBy_Designation'] = $this->getEmployeeDesignation($header->WitnessBy);

            foreach ($data['details'] as &$row) {
                $row = (array) $row;
                $row['AccountableOfficer_Name'] = $this->getEmployeeName($row['AccountableOfficer']);

                $param = DB::table('Parameters')
                    ->where('ParameterName', 'PropertyStatus')
                    ->where('ParameterCode', $row['Status'])
                    ->first();
                $row['StatusName'] = $param ? $param->ParameterValue : '';
            }
        }

        if ($controlno != 0) {
            return $data;
        }
        
        return response()->json($data);
    }

    public function done(Request $request)
    {
        $header = $request->get('header', []);
        $fund = $request->get('fund', '');
        
        if ($fund == 'RCEP') $fund = 'RCEF';
        
        $fundCd = DB::table('Parameters')
            ->where('ParameterName', 'FundCd')
            ->where('InActive', 0)
            ->where('ParameterValue', $fund)
            ->first();
        
        $header['fundcd'] = $fundCd ? $fundCd->ParameterCode : '';
        
        try {
            $result = $this->markIIRUPAsDone($header);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['Status' => 'Error', 'Message' => $e->getMessage()]);
        }
    }

    public function returnDocument(Request $request)
    {
        $header = $request->get('header', []);
        
        try {
            $result = $this->returnIIRUPDocument($header);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['Status' => 'Error', 'Message' => $e->getMessage()]);
        }
    }

    public function getHistory(Request $request)
    {
        $headerid = $request->get('headerid', '');
        $headers = DB::table('IIRUPHistory')
            ->where('IIRUPHeaderID', $headerid)
            ->orderBy('DateCreated', 'desc')
            ->get()
            ->toArray();
        
        foreach ($headers as &$row) {
            $row = (array) $row;
            $status = DB::table('Status')
                ->where('StatusID', $row['Status'])
                ->first();
            $row['StatusName'] = $status ? $status->StatusName : '';
        }
        
        return response()->json(['header' => $headers]);
    }

    public function preparation(Request $request)
    {
        if (!hasAccess(22)) {
            return view('pages.access_denied');
        }

        $data = [];
        $data['elementActive'] = 'IIRUP';
        $IIRUPControlNo = $request->get('IIRUPControlNo', '');

        if ($IIRUPControlNo) {
            // Get IIRUP data
            $iirupData = $this->get($IIRUPControlNo);
            $data = array_merge($data, $iirupData);
        } else {
            $data['IIRUPControlNo'] = $this->generateIIRUPControlNo();
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

        // Get default prepared by
        $data['default']['PreparedBy'] = session('EmployeeID');
        $data['default']['PreparedBy_Name'] = session('EmployeeName');

        return view('pages.iirup.preparation', $data);
    }

    public function preparationInbox()
    {
        if (!hasAccess(22)) {
            return view('pages.access_denied');
        }

        $data = [];
        $data['elementActive'] = 'IIRUP';

        // Get IIRUP headers for inbox
        $data['headers'] = DB::table('IIRUPHeaders')
            ->leftJoin('Employees', 'IIRUPHeaders.PreparedBy', '=', 'Employees.EmployeeID')
            ->leftJoin('Status', 'IIRUPHeaders.Status', '=', 'Status.StatusID')
            ->select(
                'IIRUPHeaders.*',
                'Employees.EmployeeName as PreparedBy_Name',
                'Status.StatusName'
            )
            ->orderBy('IIRUPHeaders.DateCreated', 'desc')
            ->get()
            ->toArray();

        return view('pages.iirup.inbox', $data);
    }

    public function query()
    {
        $data = [];
        $data['elementActive'] = 'IIRUP';

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

        return view('pages.iirup.query', $data);
    }

    public function getHeaders(Request $request)
    {
        $criteria = $request->get('criteria', []);
        
        $query = DB::table('IIRUPHeaders')
            ->leftJoin('Employees', 'IIRUPHeaders.PreparedBy', '=', 'Employees.EmployeeID')
            ->leftJoin('Divisions', 'IIRUPHeaders.DivCode', '=', 'Divisions.DivCode')
            ->leftJoin('Status', 'IIRUPHeaders.Status', '=', 'Status.StatusID')
            ->select(
                'IIRUPHeaders.*',
                'Employees.EmployeeName as PreparedBy_Name',
                'Divisions.DivName',
                'Status.StatusName'
            );

        // Apply criteria
        if (isset($criteria['IIRUPControlNo']) && $criteria['IIRUPControlNo']) {
            $query->where('IIRUPHeaders.IIRUPControlNo', 'like', '%' . $criteria['IIRUPControlNo'] . '%');
        }
        if (isset($criteria['IIRUPNo']) && $criteria['IIRUPNo']) {
            $query->where('IIRUPHeaders.IIRUPNo', 'like', '%' . $criteria['IIRUPNo'] . '%');
        }
        if (isset($criteria['DivCode']) && $criteria['DivCode']) {
            $query->where('IIRUPHeaders.DivCode', $criteria['DivCode']);
        }
        if (isset($criteria['RespoCenter']) && $criteria['RespoCenter']) {
            $query->where('IIRUPHeaders.RespoCenter', $criteria['RespoCenter']);
        }
        if (isset($criteria['PreparedBy']) && $criteria['PreparedBy']) {
            $query->where('IIRUPHeaders.PreparedBy', $criteria['PreparedBy']);
        }
        if (isset($criteria['Status']) && $criteria['Status']) {
            $query->where('IIRUPHeaders.Status', $criteria['Status']);
        }
        if (isset($criteria['DateFrom']) && $criteria['DateFrom']) {
            $query->whereDate('IIRUPHeaders.DateCreated', '>=', $criteria['DateFrom']);
        }
        if (isset($criteria['DateTo']) && $criteria['DateTo']) {
            $query->whereDate('IIRUPHeaders.DateCreated', '<=', $criteria['DateTo']);
        }

        $headers = $query->orderBy('IIRUPHeaders.DateCreated', 'desc')->get()->toArray();
        
        return response()->json(['header' => $headers]);
    }

    // Helper methods
    private function saveIIRUP($header, $details)
    {
        DB::beginTransaction();
        
        try {
            // Save header
            $headerId = DB::table('IIRUPHeaders')->insertGetId([
                'IIRUPControlNo' => $header['IIRUPControlNo'],
                'IIRUPNo' => $header['IIRUPNo'] ?? '',
                'DivCode' => $header['DivCode'] ?? '',
                'RespoCenter' => $header['RespoCenter'] ?? '',
                'PreparedBy' => session('EmployeeID'),
                'DateCreated' => now(),
                'Status' => 'N'
            ]);

            // Save details
            foreach ($details as $detail) {
                DB::table('IIRUPDetails')->insert([
                    'IIRUPHeaderID' => $headerId,
                    'ItemID' => $detail['ItemID'],
                    'Quantity' => $detail['Quantity'],
                    'AccountableOfficer' => $detail['AccountableOfficer'],
                    'Status' => $detail['Status'] ?? 'N'
                ]);
            }

            DB::commit();
            return ['Status' => 'OK', 'Message' => 'IIRUP saved successfully'];
            
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    private function markIIRUPAsDone($header)
    {
        DB::table('IIRUPHeaders')
            ->where('IIRUPHeaderID', $header['IIRUPHeaderID'])
            ->update([
                'Status' => 'D',
                'DateModified' => now()
            ]);

        return ['Status' => 'OK', 'Message' => 'IIRUP marked as done'];
    }

    private function returnIIRUPDocument($header)
    {
        DB::table('IIRUPHeaders')
            ->where('IIRUPHeaderID', $header['IIRUPHeaderID'])
            ->update([
                'Status' => 'R',
                'DateModified' => now()
            ]);

        return ['Status' => 'OK', 'Message' => 'IIRUP returned'];
    }

    private function generateIIRUPControlNo()
    {
        $year = date('Y');
        $sequence = DB::table('IIRUPHeaders')
            ->whereYear('DateCreated', $year)
            ->count() + 1;
        
        return 'IIRUP-' . $year . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    private function getEmployeeName($employeeId)
    {
        $employee = DB::table('Employees')
            ->where('EmployeeID', $employeeId)
            ->first();
        
        return $employee ? $employee->EmployeeName : '';
    }

    private function getEmployeeDesignation($employeeId)
    {
        $employee = DB::table('Employees')
            ->where('EmployeeID', $employeeId)
            ->first();
        
        return $employee ? $employee->Designation : '';
    }
}
