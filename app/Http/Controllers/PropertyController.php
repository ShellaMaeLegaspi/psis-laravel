<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class PropertyController extends Controller
{
    public function __construct()
    {
        // Add middleware if needed
        // $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $data = [];
        $data['elementActive'] = 'PROPERTY';
        $data['PropertyNo'] = $request->get('PropertyNo', '');

        // Get property header
        if ($data['PropertyNo']) {
            $header = DB::table('PropertyHeaders')
                ->where('PropertyNo', $data['PropertyNo'])
                ->first();
            
            if ($header) {
                $data['header'] = (array) $header;
                
                // Get property card details
                $data['details'] = DB::table('PropertyCards')
                    ->where('PropertyID', $header->PropertyID)
                    ->get()
                    ->toArray();

                foreach ($data['details'] as &$row) {
                    $row = (array) $row;
                    
                    if ($row['Reference'] == 'IAR') {
                        $row['PreparedBy_Name'] = $this->getEmployeeName($row['PreparedBy']);
                    } else if ($row['Reference'] == 'RIS') {
                        $ris = DB::table('RISHeaders')
                            ->where('RISControlNo', $row['ReferenceControlNo'])
                            ->first();
                        
                        if ($ris) {
                            $row['EndUser'] = $ris->ApprovedBy;
                            $row['PreparedBy_Name'] = $this->getEmployeeName($row['EndUser']);
                        }
                    } else {
                        $row['PreparedBy_Name'] = $this->getEmployeeName($row['PreparedBy']);
                    }
                }
            }
        }

        return view('pages.propertycard_index', $data);
    }

    public function propertyItems()
    {
        if (!hasAccess(10) && !hasAccess(119)) {
            return view('pages.access_denied');
        }

        $data = [];
        $data['elementActive'] = 'Maintenance';
        
        // Get fund codes
        $data['FundCd'] = DB::table('Parameters')
            ->where('ParameterName', 'FundCd')
            ->where('InActive', 0)
            ->get()
            ->toArray();

        return view('pages.property_items', $data);
    }

    public function prisupPreparation(Request $request)
    {
        $data = [];
        $data['elementActive'] = 'PRISUP';
        $PRISUPControlNo = $request->get('PRISUPControlNo', '');

        if ($PRISUPControlNo) {
            // Get PRISUP header
            $header = DB::table('PRISUPHeaders')
                ->where('PRISUPControlNo', $PRISUPControlNo)
                ->first();
            
            if ($header) {
                $data['header'] = (array) $header;
                $data['PRISUPControlNo'] = $header->PRISUPControlNo;
                
                // Get PRISUP details
                $data['details'] = DB::table('PRISUPDetails')
                    ->where('PRISUPHeaderID', $header->PRISUPHeaderID)
                    ->get()
                    ->toArray();
            }
        } else {
            $data['PRISUPControlNo'] = $this->generatePRISUPControlNo();
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

        return view('pages.prisup.preparation', $data);
    }

    public function prisupPreparationInbox()
    {
        if (!hasAccess(22)) {
            return view('pages.access_denied');
        }

        $data = [];
        $data['elementActive'] = 'PRISUP';

        // Get PRISUP headers for inbox
        $data['headers'] = DB::table('PRISUPHeaders')
            ->leftJoin('Employees', 'PRISUPHeaders.PreparedBy', '=', 'Employees.EmployeeID')
            ->leftJoin('Status', 'PRISUPHeaders.Status', '=', 'Status.StatusID')
            ->select(
                'PRISUPHeaders.*',
                'Employees.EmployeeName as PreparedBy_Name',
                'Status.StatusName'
            )
            ->orderBy('PRISUPHeaders.DateCreated', 'desc')
            ->get()
            ->toArray();

        return view('pages.prisup.inbox', $data);
    }

    public function prisupQuery()
    {
        $data = [];
        $data['elementActive'] = 'PRISUP';

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

        return view('pages.prisup.query', $data);
    }

    public function prisupImport()
    {
        if (!hasAccess(22)) {
            return view('pages.access_denied');
        }

        $data = [];
        $data['elementActive'] = 'PRISUP';

        return view('pages.prisup.import', $data);
    }

    public function palPreparation(Request $request)
    {
        $data = [];
        $data['elementActive'] = 'PAL';
        $ItemNo = $request->get('ItemNo', '');

        if ($ItemNo) {
            // Get PAL header
            $header = DB::table('PALHeaders')
                ->where('ItemNo', $ItemNo)
                ->first();
            
            if ($header) {
                $data['header'] = (array) $header;
                $data['ItemNo'] = $header->ItemNo;
                
                // Get PAL details
                $data['details'] = DB::table('PALDetails')
                    ->where('PALHeaderID', $header->PALHeaderID)
                    ->get()
                    ->toArray();
            }
        } else {
            $data['ItemNo'] = $this->generateItemNo();
        }

        // Get default prepared by
        $data['default']['PreparedBy'] = session('EmployeeID');
        $data['default']['PreparedBy_Name'] = session('EmployeeName');

        return view('pages.pal.preparation', $data);
    }

    public function palQuery()
    {
        $data = [];
        $data['elementActive'] = 'PAL';

        return view('pages.pal.query', $data);
    }

    public function palImport()
    {
        if (!hasAccess(22)) {
            return view('pages.access_denied');
        }

        $data = [];
        $data['elementActive'] = 'PAL';

        return view('pages.pal.import', $data);
    }

    public function iirupPreparation(Request $request)
    {
        $data = [];
        $data['elementActive'] = 'IIRUP';
        $IIRUPControlNo = $request->get('IIRUPControlNo', '');

        if ($IIRUPControlNo) {
            // Get IIRUP header
            $header = DB::table('IIRUPHeaders')
                ->where('IIRUPControlNo', $IIRUPControlNo)
                ->first();
            
            if ($header) {
                $data['header'] = (array) $header;
                $data['IIRUPControlNo'] = $header->IIRUPControlNo;
                
                // Get IIRUP details
                $data['details'] = DB::table('IIRUPDetails')
                    ->where('IIRUPHeaderID', $header->IIRUPHeaderID)
                    ->get()
                    ->toArray();
            }
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

    public function iirupPreparationInbox()
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

    public function iirupQuery()
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

    public function iirupImport()
    {
        if (!hasAccess(22)) {
            return view('pages.access_denied');
        }

        $data = [];
        $data['elementActive'] = 'IIRUP';

        return view('pages.iirup.import', $data);
    }

    // Helper methods
    private function generatePRISUPControlNo()
    {
        $year = date('Y');
        $sequence = DB::table('PRISUPHeaders')
            ->whereYear('DateCreated', $year)
            ->count() + 1;
        
        return 'PRISUP-' . $year . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    private function generateIIRUPControlNo()
    {
        $year = date('Y');
        $sequence = DB::table('IIRUPHeaders')
            ->whereYear('DateCreated', $year)
            ->count() + 1;
        
        return 'IIRUP-' . $year . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    private function generateItemNo()
    {
        $year = date('Y');
        $sequence = DB::table('PALHeaders')
            ->whereYear('DateCreated', $year)
            ->count() + 1;
        
        return 'ITEM-' . $year . '-' . str_pad($sequence, 6, '0', STR_PAD_LEFT);
    }

    private function getEmployeeName($employeeId)
    {
        $employee = DB::table('Employees')
            ->where('EmployeeID', $employeeId)
            ->first();
        
        return $employee ? $employee->EmployeeName : '';
    }
}
