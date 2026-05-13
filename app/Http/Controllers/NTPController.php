<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\NTPHeader;
use App\Models\NTPDetails;
use App\Models\Employee;
use App\Models\Status;
use App\Models\LibParameter;

class NTPController extends Controller
{
    public function preparation_inbox()
    {
        $data = [];
        $criteria = [];
        $data['elementActive'] = 'NTP';
        $criteria['PreparedBy'] = session('EmployeeID');
        $criteria['Status'] = ['N', 'R'];
        
        $data['row'] = NTPHeader::getHeaders($criteria);
        
        foreach ($data['row'] as &$row) {
            $row['PreparedBy_Name'] = Employee::getEmployeeName($row['PreparedBy']);
            $row['ApprovedBy_Name'] = Employee::getEmployeeName($row['ApprovedBy'] ?? '');
            $row['StatusName'] = Status::getStatusName($row['Status']);
        }
        
        return view('pages.ntp.inbox', $data);
    }
    
    public function approval_inbox()
    {
        $data = [];
        $criteria = [];
        $data['elementActive'] = 'NTP';
        $criteria['EvaluatedBy'] = session('EmployeeID');
        $criteria['Status'] = ['D'];
        
        $data['row'] = NTPHeader::getHeaders($criteria);
        
        foreach ($data['row'] as &$row) {
            $row['PreparedBy_Name'] = Employee::getEmployeeName($row['PreparedBy']);
            $row['ApprovedBy_Name'] = Employee::getEmployeeName($row['ApprovedBy'] ?? '');
            $row['StatusName'] = Status::getStatusName($row['Status']);
        }
        
        return view('pages.ntp.inbox', $data);
    }
    
    public function query()
    {
        $data = [];
        $data['elementActive'] = 'NTP';
        
        $data['RespoList'] = LibParameter::getParam(['ParameterName' => 'RC']);
        $data['Divisions'] = LibParameter::getParam(['ParameterName' => 'Division']);
        
        return view('pages.ntp.query', $data);
    }
    
    public function preparation()
    {
        $data = [];
        $data['elementActive'] = 'NTP';
        $data['NTPControlNo'] = request('NTPControlNo', '');
        
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
        
        return view('pages.ntp.preparation', $data);
    }
    
    public function get_headers(Request $request)
    {
        $criteria = $request->input('criteria', []);
        
        $headers = NTPHeader::getHeaders($criteria);
        
        foreach ($headers as &$row) {
            $row['PreparedBy_Name'] = Employee::getEmployeeName($row['PreparedBy']);
            $row['ApprovedBy_Name'] = Employee::getEmployeeName($row['ApprovedBy'] ?? '');
            $row['StatusName'] = Status::getStatusName($row['Status']);
        }
        
        return response()->json(['header' => $headers]);
    }
    
    public function get(Request $request)
    {
        $controlno = $request->input('controlno', $request->input('NTPControlNo', ''));
        
        $header = NTPHeader::getHeader($controlno);
        $details = $header ? NTPDetails::getDetails($header['NTPHeaderID']) : [];
        
        $data = [
            'header' => $header ?: [],
            'details' => $details,
            'summary' => []
        ];
        
        return response()->json($data);
    }
    
    public function count_preparation_inbox()
    {
        return response()->json(['count' => NTPHeader::countByStatus(['N', 'R'])]);
    }
}
