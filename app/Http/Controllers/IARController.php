<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\IARHeader;
use App\Models\IARDetails;
use App\Models\Employee;
use App\Models\Status;
use App\Models\LibParameter;

class IARController extends Controller
{
    public function preparation_inbox()
    {
        $data = [];
        $criteria = [];
        $data['elementActive'] = 'IAR';
        $criteria['PreparedBy'] = session('EmployeeID');
        $criteria['Status'] = ['N', 'R'];
        
        $data['iar'] = IARHeader::getHeaders($criteria);
        
        foreach ($data['iar'] as &$iar) {
            $iar['PreparedBy_Name'] = Employee::getEmployeeName($iar['PreparedBy']);
            $iar['InspectedBy_Name'] = Employee::getEmployeeName($iar['InspectedBy'] ?? '');
            $iar['AcceptedBy_Name'] = Employee::getEmployeeName($iar['AcceptedBy'] ?? '');
            $iar['StatusName'] = Status::getStatusName($iar['Status']);
        }
        
        return view('pages.iar.inbox', $data);
    }
    
    public function approval_inbox()
    {
        $data = [];
        $criteria = [];
        $data['elementActive'] = 'IAR';
        $criteria['Status'] = ['D'];
        $criteria['InspectedBy'] = session('EmployeeID');
        
        $data['iar'] = IARHeader::getHeaders($criteria);
        
        foreach ($data['iar'] as &$iar) {
            $iar['PreparedBy_Name'] = Employee::getEmployeeName($iar['PreparedBy']);
            $iar['InspectedBy_Name'] = Employee::getEmployeeName($iar['InspectedBy'] ?? '');
            $iar['AcceptedBy_Name'] = Employee::getEmployeeName($iar['AcceptedBy'] ?? '');
            $iar['StatusName'] = Status::getStatusName($iar['Status']);
        }
        
        return view('pages.iar.inbox', $data);
    }
    
    public function acceptance_inbox()
    {
        $data = [];
        $criteria = [];
        $data['elementActive'] = 'IAR';
        $criteria['Status'] = ['K'];
        $criteria['AcceptedBy'] = session('EmployeeID');
        
        $data['iar'] = IARHeader::getHeaders($criteria);
        
        foreach ($data['iar'] as &$iar) {
            $iar['PreparedBy_Name'] = Employee::getEmployeeName($iar['PreparedBy']);
            $iar['InspectedBy_Name'] = Employee::getEmployeeName($iar['InspectedBy'] ?? '');
            $iar['AcceptedBy_Name'] = Employee::getEmployeeName($iar['AcceptedBy'] ?? '');
            $iar['StatusName'] = Status::getStatusName($iar['Status']);
        }
        
        return view('pages.iar.inbox', $data);
    }
    
    public function receiving_inbox()
    {
        $data = [];
        $criteria = [];
        $data['elementActive'] = 'IAR';
        $criteria['Status'] = ['T'];
        
        $data['iar'] = IARHeader::getHeaders($criteria);
        
        foreach ($data['iar'] as &$iar) {
            $iar['PreparedBy_Name'] = Employee::getEmployeeName($iar['PreparedBy']);
            $iar['InspectedBy_Name'] = Employee::getEmployeeName($iar['InspectedBy'] ?? '');
            $iar['AcceptedBy_Name'] = Employee::getEmployeeName($iar['AcceptedBy'] ?? '');
            $iar['StatusName'] = Status::getStatusName($iar['Status']);
        }
        
        return view('pages.iar.inbox', $data);
    }
    
    public function query()
    {
        $data = [];
        $data['elementActive'] = 'IAR';
        
        // Get responsibility centers and divisions from LIB_Parameters
        $data['RespoList'] = LibParameter::getParam(['ParameterName' => 'RC']);
        $data['Divisions'] = LibParameter::getParam(['ParameterName' => 'Division']);
        
        return view('pages.iar.query', $data);
    }
    
    public function preparation()
    {
        $data = [];
        $data['elementActive'] = 'IAR';
        $data['IARControlNo'] = request('IARControlNo', '');
        
        // Get responsibility centers, divisions, stations, buildings, rooms from LIB_Parameters
        $data['RespoList'] = LibParameter::getParam(['ParameterName' => 'RC']);
        $data['Divisions'] = LibParameter::getParam(['ParameterName' => 'Division']);
        $data['Stations'] = LibParameter::getParam(['ParameterName' => 'Station']);
        $data['Buildings'] = LibParameter::getParam(['ParameterName' => 'Building']);
        $data['Rooms'] = LibParameter::getParam(['ParameterName' => 'Room']);
        
        // Set default values
        $data['default']['PreparedBy'] = session('EmployeeID');
        $data['default']['PreparedBy_Name'] = Employee::getEmployeeName(session('EmployeeID'));
        $data['corporate'] = 0;
        $data['fundclass'] = session('FundClass', '');
        
        if ($data['fundclass'] == 'CORPORATE') $data['corporate'] = 1;
        
        return view('pages.iar.preparation', $data);
    }
    
    public function get_headers(Request $request)
    {
        $criteria = $request->input('criteria', []);
        
        $headers = IARHeader::getHeaders($criteria);
        
        foreach ($headers as &$row) {
            $row['PreparedBy_Name'] = Employee::getEmployeeName($row['PreparedBy']);
            $row['InspectedBy_Name'] = Employee::getEmployeeName($row['InspectedBy'] ?? '');
            $row['AcceptedBy_Name'] = Employee::getEmployeeName($row['AcceptedBy'] ?? '');
            $row['StatusName'] = Status::getStatusName($row['Status']);
        }
        
        return response()->json(['header' => $headers]);
    }
    
    public function get(Request $request)
    {
        $controlno = $request->input('controlno', $request->input('IARControlNo', ''));
        
        $header = IARHeader::getHeader($controlno);
        $details = $header ? IARDetails::getDetails($header['IARHeaderID']) : [];
        
        $data = [
            'header' => $header ?: [],
            'details' => $details,
            'summary' => []
        ];
        
        return response()->json($data);
    }
    
    public function count_preparation_inbox()
    {
        return response()->json(['count' => IARHeader::countByStatus(['N', 'R'])]);
    }
}
