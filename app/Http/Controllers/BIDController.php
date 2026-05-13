<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\BidHeader;
use App\Models\BidDetails;
use App\Models\Employee;
use App\Models\Status;
use App\Models\LibParameter;

class BIDController extends Controller
{
    public function preparation_inbox()
    {
        $data = [];
        $criteria = [];
        $data['elementActive'] = 'BID';
        $criteria['PreparedBy'] = session('EmployeeID');
        $criteria['Status'] = ['N', 'R'];
        
        $data['row'] = BidHeader::getHeaders($criteria);
        
        foreach ($data['row'] as &$row) {
            $row['PreparedBy_Name'] = Employee::getEmployeeName($row['PreparedBy']);
            $row['ApprovedBy_Name'] = Employee::getEmployeeName($row['ApprovedBy'] ?? '');
            $row['StatusName'] = Status::getStatusName($row['Status']);
        }
        
        return view('pages.bid.inbox', $data);
    }
    
    public function approval_inbox()
    {
        $data = [];
        $criteria = [];
        $data['elementActive'] = 'BID';
        $criteria['EvaluatedBy'] = session('EmployeeID');
        $criteria['Status'] = ['D'];
        
        $data['row'] = BidHeader::getHeaders($criteria);
        
        foreach ($data['row'] as &$row) {
            $row['PreparedBy_Name'] = Employee::getEmployeeName($row['PreparedBy']);
            $row['ApprovedBy_Name'] = Employee::getEmployeeName($row['ApprovedBy'] ?? '');
            $row['StatusName'] = Status::getStatusName($row['Status']);
        }
        
        return view('pages.bid.inbox', $data);
    }
    
    public function query()
    {
        $data = [];
        $data['elementActive'] = 'BID';
        
        $data['RespoList'] = LibParameter::getParam(['ParameterName' => 'RC']);
        $data['Divisions'] = LibParameter::getParam(['ParameterName' => 'Division']);
        
        return view('pages.bid.query', $data);
    }
    
    public function preparation()
    {
        $data = [];
        $data['elementActive'] = 'BID';
        $data['BIDControlNo'] = request('BIDControlNo', '');
        
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
        
        return view('pages.bid.preparation', $data);
    }
    
    public function get_headers(Request $request)
    {
        $criteria = $request->input('criteria', []);
        
        $headers = BidHeader::getHeaders($criteria);
        
        foreach ($headers as &$row) {
            $row['PreparedBy_Name'] = Employee::getEmployeeName($row['PreparedBy']);
            $row['ApprovedBy_Name'] = Employee::getEmployeeName($row['ApprovedBy'] ?? '');
            $row['StatusName'] = Status::getStatusName($row['Status']);
        }
        
        return response()->json(['header' => $headers]);
    }
    
    public function get(Request $request)
    {
        $controlno = $request->input('controlno', $request->input('BIDControlNo', ''));
        
        $header = BidHeader::getHeader($controlno);
        $details = $header ? BidDetails::getDetails($header['BIDHeaderID']) : [];
        
        $data = [
            'header' => $header ?: [],
            'details' => $details,
            'summary' => []
        ];
        
        return response()->json($data);
    }
    
    public function count_preparation_inbox()
    {
        return response()->json(['count' => BidHeader::countByStatus(['N', 'R'])]);
    }
}
