<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\POHeader;
use App\Models\PODetails;
use App\Models\Employee;
use App\Models\Status;
use App\Models\LibParameter;

class POController extends Controller
{
    public function count_preparation_inbox()
    {
        return POHeader::countByStatus(['N', 'R']);
    }
    
    public function count_approval_inbox()
    {
        return POHeader::countByStatus(['D']);
    }
    
    public function count_certification_inbox()
    {
        return POHeader::countByStatus(['A']);
    }
    
    public function count_receiving_inbox()
    {
        return POHeader::countByStatus(['C']);
    }
    
    public function preparation_inbox()
    {
        $data = [];
        $criteria = [];
        $data['elementActive'] = 'PO';
        $criteria['PreparedBy'] = session('EmployeeID');
        $criteria['Status'] = ['N', 'R'];
        
        $data['po'] = POHeader::getHeaders($criteria);
        
        foreach ($data['po'] as &$po) {
            $po['PreparedBy_Name'] = Employee::getEmployeeName($po['PreparedBy']);
            $po['RequisitionedBy_Name'] = Employee::getEmployeeName($po['RequisitionedBy'] ?? '');
            $po['EvaluatedBy_Name'] = Employee::getEmployeeName($po['EvaluatedBy'] ?? '');
            $po['FundsAvailableBy_Name'] = Employee::getEmployeeName($po['FundsAvailableBy'] ?? '');
            $po['StatusName'] = Status::getStatusName($po['Status']);
        }
        
        return view('pages.po.inbox', $data);
    }
    
    public function approval_inbox()
    {
        $data = [];
        $criteria = [];
        $data['elementActive'] = 'PO';
        $criteria['EvaluatedBy'] = session('EmployeeID');
        $criteria['Status'] = ['D'];
        
        $data['po'] = POHeader::getHeaders($criteria);
        
        foreach ($data['po'] as &$po) {
            $po['PreparedBy_Name'] = Employee::getEmployeeName($po['PreparedBy']);
            $po['RequisitionedBy_Name'] = Employee::getEmployeeName($po['RequisitionedBy'] ?? '');
            $po['EvaluatedBy_Name'] = Employee::getEmployeeName($po['EvaluatedBy'] ?? '');
            $po['FundsAvailableBy_Name'] = Employee::getEmployeeName($po['FundsAvailableBy'] ?? '');
            $po['StatusName'] = Status::getStatusName($po['Status']);
        }
        
        return view('pages.po.inbox', $data);
    }
    
    public function certification_inbox()
    {
        $data = [];
        $criteria = [];
        $data['elementActive'] = 'PO';
        $criteria['FundsAvailableBy'] = session('EmployeeID');
        $criteria['Status'] = ['A'];
        
        $data['po'] = POHeader::getHeaders($criteria);
        
        foreach ($data['po'] as &$po) {
            $po['PreparedBy_Name'] = Employee::getEmployeeName($po['PreparedBy']);
            $po['RequisitionedBy_Name'] = Employee::getEmployeeName($po['RequisitionedBy'] ?? '');
            $po['EvaluatedBy_Name'] = Employee::getEmployeeName($po['EvaluatedBy'] ?? '');
            $po['FundsAvailableBy_Name'] = Employee::getEmployeeName($po['FundsAvailableBy'] ?? '');
            $po['StatusName'] = Status::getStatusName($po['Status']);
        }
        
        return view('pages.po.inbox', $data);
    }
    
    public function receiving_inbox()
    {
        $data = [];
        $criteria = [];
        $data['elementActive'] = 'PO';
        $criteria['Status'] = ['C'];
        
        $data['po'] = POHeader::getHeaders($criteria);
        
        foreach ($data['po'] as &$po) {
            $po['PreparedBy_Name'] = Employee::getEmployeeName($po['PreparedBy']);
            $po['RequisitionedBy_Name'] = Employee::getEmployeeName($po['RequisitionedBy'] ?? '');
            $po['EvaluatedBy_Name'] = Employee::getEmployeeName($po['EvaluatedBy'] ?? '');
            $po['FundsAvailableBy_Name'] = Employee::getEmployeeName($po['FundsAvailableBy'] ?? '');
            $po['StatusName'] = Status::getStatusName($po['Status']);
        }
        
        return view('pages.po.inbox', $data);
    }
    
    public function query()
    {
        $data = [];
        $data['elementActive'] = 'PO';
        
        $data['RespoList'] = LibParameter::getParam(['ParameterName' => 'RC']);
        $data['Divisions'] = LibParameter::getParam(['ParameterName' => 'Division']);
        
        return view('pages.po.query', $data);
    }
    
    public function preparation()
    {
        $data = [];
        $data['elementActive'] = 'PO';
        $data['POControlNo'] = request('POControlNo', '');
        
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
        
        return view('pages.po.preparation', $data);
    }
    
    public function get_headers(Request $request)
    {
        $criteria = $request->input('criteria', []);
        
        $headers = POHeader::getHeaders($criteria);
        
        foreach ($headers as &$row) {
            $row['PreparedBy_Name'] = Employee::getEmployeeName($row['PreparedBy']);
            $row['RequisitionedBy_Name'] = Employee::getEmployeeName($row['RequisitionedBy'] ?? '');
            $row['EvaluatedBy_Name'] = Employee::getEmployeeName($row['EvaluatedBy'] ?? '');
            $row['FundsAvailableBy_Name'] = Employee::getEmployeeName($row['FundsAvailableBy'] ?? '');
            $row['StatusName'] = Status::getStatusName($row['Status']);
        }
        
        return response()->json(['header' => $headers]);
    }
    
    public function get(Request $request)
    {
        $controlno = $request->input('controlno', $request->input('POControlNo', ''));
        
        $header = POHeader::getHeader($controlno);
        $details = $header ? PODetails::getDetails($header['POHeaderID']) : [];
        
        $data = [
            'header' => $header ?: [],
            'details' => $details,
            'summary' => []
        ];
        
        return response()->json($data);
    }
}
