<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PARHeader;
use App\Models\PARDetails;
use App\Models\Employee;
use App\Models\Status;
use App\Models\LibParameter;

class PARController extends Controller
{
    public function preparation_inbox()
    {
        $data = [];
        $criteria = [];
        $data['elementActive'] = 'PAR';
        $criteria['PreparedBy'] = session('EmployeeID');
        $criteria['Status'] = ['N', 'R'];
        
        $data['row'] = PARHeader::getHeaders($criteria);
        
        foreach ($data['row'] as &$row) {
            $row['PreparedBy_Name'] = Employee::getEmployeeName($row['PreparedBy']);
            $row['AccountableOfficer_Name'] = Employee::getEmployeeName($row['AccountableOfficer'] ?? '');
            $row['CoAccountableOfficer_Name'] = Employee::getEmployeeName($row['CoAccountableOfficer'] ?? '');
            $row['StatusName'] = Status::getStatusName($row['Status']);
        }
        
        return view('pages.par.inbox', $data);
    }
    
    public function acceptance_inbox()
    {
        $data = [];
        $criteria = [];
        $data['elementActive'] = 'PAR';
        $criteria['EvaluatedBy'] = session('EmployeeID');
        $criteria['Status'] = ['D'];
        
        $data['row'] = PARHeader::getHeaders($criteria);
        
        foreach ($data['row'] as &$row) {
            $row['PreparedBy_Name'] = Employee::getEmployeeName($row['PreparedBy']);
            $row['AccountableOfficer_Name'] = Employee::getEmployeeName($row['AccountableOfficer'] ?? '');
            $row['CoAccountableOfficer_Name'] = Employee::getEmployeeName($row['CoAccountableOfficer'] ?? '');
            $row['StatusName'] = Status::getStatusName($row['Status']);
        }
        
        return view('pages.par.inbox', $data);
    }
    
    public function approval_inbox()
    {
        $data = [];
        $criteria = [];
        $data['elementActive'] = 'PAR';
        $criteria['EvaluatedBy'] = session('EmployeeID');
        $criteria['Status'] = ['T'];
        
        $data['row'] = PARHeader::getHeaders($criteria);
        
        foreach ($data['row'] as &$row) {
            $row['PreparedBy_Name'] = Employee::getEmployeeName($row['PreparedBy']);
            $row['AccountableOfficer_Name'] = Employee::getEmployeeName($row['AccountableOfficer'] ?? '');
            $row['CoAccountableOfficer_Name'] = Employee::getEmployeeName($row['CoAccountableOfficer'] ?? '');
            $row['StatusName'] = Status::getStatusName($row['Status']);
        }
        
        return view('pages.par.inbox', $data);
    }
    
    public function receiving_inbox()
    {
        $data = [];
        $criteria = [];
        $data['elementActive'] = 'PAR';
        $criteria['Status'] = ['A'];
        
        $data['row'] = PARHeader::getHeaders($criteria);
        
        foreach ($data['row'] as &$row) {
            $row['PreparedBy_Name'] = Employee::getEmployeeName($row['PreparedBy']);
            $row['AccountableOfficer_Name'] = Employee::getEmployeeName($row['AccountableOfficer'] ?? '');
            $row['CoAccountableOfficer_Name'] = Employee::getEmployeeName($row['CoAccountableOfficer'] ?? '');
            $row['StatusName'] = Status::getStatusName($row['Status']);
        }
        
        return view('pages.par.inbox', $data);
    }
    
    public function query()
    {
        $data = [];
        $data['elementActive'] = 'PAR';
        
        $data['RespoList'] = LibParameter::getParam(['ParameterName' => 'RC']);
        $data['Divisions'] = LibParameter::getParam(['ParameterName' => 'Division']);
        
        return view('pages.par.query', $data);
    }
    
    public function preparation()
    {
        $data = [];
        $data['elementActive'] = 'PAR';
        $data['PARControlNo'] = request('PARControlNo', '');
        
        $data['RespoList'] = LibParameter::getParam(['ParameterName' => 'RC']);
        $data['Divisions'] = LibParameter::getParam(['ParameterName' => 'Division']);
        $data['Stations'] = LibParameter::getParam(['ParameterName' => 'Station']);
        $data['Buildings'] = LibParameter::getParam(['ParameterName' => 'Building']);
        $data['Rooms'] = LibParameter::getParam(['ParameterName' => 'Room']);
        
        $data['default']['PreparedBy'] = session('EmployeeID');
        $data['default']['PreparedBy_Name'] = Employee::getEmployeeName(session('EmployeeID'));
        $data['corporate'] = 0;
        $data['fundclass'] = session('FundClass', '');
        
        if ($data['fundclass'] == 'CORPORATE') $data['corporate'] = 1;
        
        return view('pages.par.preparation', $data);
    }
    
    public function get_headers(Request $request)
    {
        $criteria = $request->input('criteria', []);
        
        $headers = PARHeader::getHeaders($criteria);
        
        foreach ($headers as &$row) {
            $row['PreparedBy_Name'] = Employee::getEmployeeName($row['PreparedBy']);
            $row['AccountableOfficer_Name'] = Employee::getEmployeeName($row['AccountableOfficer'] ?? '');
            $row['CoAccountableOfficer_Name'] = Employee::getEmployeeName($row['CoAccountableOfficer'] ?? '');
            $row['StatusName'] = Status::getStatusName($row['Status']);
        }
        
        return response()->json(['header' => $headers]);
    }
    
    public function get(Request $request)
    {
        $controlno = $request->input('controlno', $request->input('PARControlNo', ''));
        
        $header = PARHeader::getHeader($controlno);
        $details = $header ? PARDetails::getDetails($header['PARHeaderID']) : [];
        
        $data = [
            'header' => $header ?: [],
            'details' => $details,
            'summary' => []
        ];
        
        return response()->json($data);
    }
    
    public function count_preparation_inbox()
    {
        return response()->json(['count' => PARHeader::countByStatus(['N', 'R'])]);
    }
}
