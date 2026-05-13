<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\RISHeader;
use App\Models\RISDetails;
use App\Models\Employee;
use App\Models\Status;
use App\Models\LibParameter;

class RISController extends Controller
{
    public function preparation_inbox()
    {
        $data = [];
        $criteria = [];
        $data['elementActive'] = 'RIS';
        $criteria['Status'] = ['N', 'R'];
        $criteria['inbox'] = 1;
        
        $data['ris'] = RISHeader::getHeaders($criteria);
        
        foreach ($data['ris'] as &$ris) {
            $ris['PreparedBy_Name'] = Employee::getEmployeeName($ris['PreparedBy']);
            $ris['ApprovedBy_Name'] = Employee::getEmployeeName($ris['ApprovedBy'] ?? '');
            $ris['StatusName'] = Status::getStatusName($ris['Status']);
        }
        
        return view('pages.ris.inbox', $data);
    }
    
    public function approval_inbox()
    {
        $data = [];
        $criteria = [];
        $data['elementActive'] = 'RIS';
        $criteria['Status'] = ['D'];
        $criteria['ApprovedBy'] = session('EmployeeID');
        
        $data['ris'] = RISHeader::getHeaders($criteria);
        
        foreach ($data['ris'] as &$ris) {
            $ris['PreparedBy_Name'] = Employee::getEmployeeName($ris['PreparedBy']);
            $ris['ApprovedBy_Name'] = Employee::getEmployeeName($ris['ApprovedBy'] ?? '');
            $ris['StatusName'] = Status::getStatusName($ris['Status']);
        }
        
        return view('pages.ris.inbox', $data);
    }
    
    public function query()
    {
        $data = [];
        $data['elementActive'] = 'RIS';
        
        $data['RespoList'] = LibParameter::getParam(['ParameterName' => 'RC']);
        $data['Divisions'] = LibParameter::getParam(['ParameterName' => 'Division']);
        
        return view('pages.ris.query', $data);
    }
    
    public function preparation()
    {
        $data = [];
        $data['elementActive'] = 'RIS';
        $data['RISControlNo'] = request('RISControlNo', '');
        
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
        
        return view('pages.ris.preparation', $data);
    }
    
    public function get_headers(Request $request)
    {
        $criteria = $request->input('criteria', []);
        
        $headers = RISHeader::getHeaders($criteria);
        
        foreach ($headers as &$row) {
            $row['PreparedBy_Name'] = Employee::getEmployeeName($row['PreparedBy']);
            $row['ApprovedBy_Name'] = Employee::getEmployeeName($row['ApprovedBy'] ?? '');
            $row['StatusName'] = Status::getStatusName($row['Status']);
        }
        
        return response()->json(['header' => $headers]);
    }
    
    public function get(Request $request)
    {
        $controlno = $request->input('controlno', $request->input('RISControlNo', ''));
        
        $header = RISHeader::getHeader($controlno);
        $details = $header ? RISDetails::getDetails($header['RISHeaderID']) : [];
        
        $data = [
            'header' => $header ?: [],
            'details' => $details,
            'summary' => []
        ];
        
        return response()->json($data);
    }
    
    public function count_preparation_inbox()
    {
        return response()->json(['count' => RISHeader::countByStatus(['N', 'R'])]);
    }
}
