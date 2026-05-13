<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\NOAHeader;
use App\Models\NOADetails;
use App\Models\Employee;
use App\Models\Status;
use App\Models\LibParameter;

class NOAController extends Controller
{
    public function preparation_inbox()
    {
        $data = [];
        $criteria = [];
        $data['elementActive'] = 'NOA';
        $criteria['PreparedBy'] = session('EmployeeID');
        $criteria['Status'] = ['N', 'R'];
        
        $data['row'] = NOAHeader::getHeaders($criteria);
        
        foreach ($data['row'] as &$row) {
            $row['PreparedBy_Name'] = Employee::getEmployeeName($row['PreparedBy']);
            $row['ApprovedBy_Name'] = Employee::getEmployeeName($row['ApprovedBy'] ?? '');
            $row['StatusName'] = Status::getStatusName($row['Status']);
        }
        
        return view('pages.noa.inbox', $data);
    }
    
    public function approval_inbox()
    {
        $data = [];
        $criteria = [];
        $data['elementActive'] = 'NOA';
        $criteria['EvaluatedBy'] = session('EmployeeID');
        $criteria['Status'] = ['D'];
        
        $data['row'] = NOAHeader::getHeaders($criteria);
        
        foreach ($data['row'] as &$row) {
            $row['PreparedBy_Name'] = Employee::getEmployeeName($row['PreparedBy']);
            $row['ApprovedBy_Name'] = Employee::getEmployeeName($row['ApprovedBy'] ?? '');
            $row['StatusName'] = Status::getStatusName($row['Status']);
        }
        
        return view('pages.noa.inbox', $data);
    }
    
    public function query()
    {
        $data = [];
        $data['elementActive'] = 'NOA';
        
        $data['RespoList'] = LibParameter::getParam(['ParameterName' => 'RC']);
        $data['Divisions'] = LibParameter::getParam(['ParameterName' => 'Division']);
        
        return view('pages.noa.query', $data);
    }
    
    public function preparation()
    {
        $data = [];
        $data['elementActive'] = 'NOA';
        $data['NOAControlNo'] = request('NOAControlNo', '');
        
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
        
        return view('pages.noa.preparation', $data);
    }
    
    public function get_headers(Request $request)
    {
        $criteria = $request->input('criteria', []);
        
        $headers = NOAHeader::getHeaders($criteria);
        
        foreach ($headers as &$row) {
            $row['PreparedBy_Name'] = Employee::getEmployeeName($row['PreparedBy']);
            $row['ApprovedBy_Name'] = Employee::getEmployeeName($row['ApprovedBy'] ?? '');
            $row['StatusName'] = Status::getStatusName($row['Status']);
        }
        
        return response()->json(['header' => $headers]);
    }
    
    public function get(Request $request)
    {
        $controlno = $request->input('controlno', $request->input('NOAControlNo', ''));
        
        $header = NOAHeader::getHeader($controlno);
        $details = $header ? NOADetails::getDetails($header['NOAHeaderID']) : [];
        
        $data = [
            'header' => $header ?: [],
            'details' => $details,
            'summary' => []
        ];
        
        return response()->json($data);
    }
    
    public function count_preparation_inbox()
    {
        return response()->json(['count' => NOAHeader::countByStatus(['N', 'R'])]);
    }
}
