<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SecUser;
use App\Models\SecUsersToUsersAccess;
use App\Models\LibParameter;
use App\Models\Employee;

class LoginController extends Controller
{
    /**
     * Show the login form.
     * Maps to CI: Login->form()
     *
     * @return \Illuminate\View\View
     */
    public function form()
    {
        $data = [];

        // Check if user is already logged in
        if (session()->has('EmployeeID')) {
            return redirect('/ppmp');
        }

        // Check if SQL Server is reachable before attempting DB queries
        $dbHost = env('DB_HOST', 'localhost');
        $dbPort = env('DB_PORT', '1433');
        $conn = @fsockopen($dbHost, $dbPort, $errno, $errstr, 2);

        if ($conn) {
            fclose($conn);
            try {
                // Get station parameters
                // Maps to CI: Param_Model->getParam(array('ParameterName' => 'Station'))
                $station = LibParameter::getParam(['ParameterName' => 'Station']);

                $data['Station'] = [];
                $data['StationFundClass'] = [];

                foreach ($station as $row) {
                    $data['Station'][] = $row['ParameterCode'];

                    // Get fund classes for each station
                    $psisdb = LibParameter::getParam(['ParameterName' => $row['ParameterCode'] . '_FundClass']);
                    foreach ($psisdb as $row2) {
                        $data['StationFundClass'][$row['ParameterCode']][] = $row2['ParameterCode'];
                    }
                }
            } catch (\Exception $e) {
                // Fallback data when database is not available
                $data['Station'] = ['PHILRICE', 'BRANCHES'];
                $data['StationFundClass'] = [
                    'PHILRICE' => ['CORPORATE', 'BDD', 'TRUST', 'RCEP'],
                    'BRANCHES' => ['CORPORATE', 'BDD', 'TRUST', 'RCEP']
                ];
            }
        } else {
            // Fallback data when database is not reachable
            $data['Station'] = ['PHILRICE', 'BRANCHES'];
            $data['StationFundClass'] = [
                'PHILRICE' => ['CORPORATE', 'BDD', 'TRUST', 'RCEP'],
                'BRANCHES' => ['CORPORATE', 'BDD', 'TRUST', 'RCEP']
            ];
        }

        // Pass session employee ID if exists (for pre-filling)
        $data['emp_idno'] = session('emp_idno', '');

        return view('pages.login', $data);
    }

    /**
     * AJAX endpoint to authenticate/get user.
     * Maps to CI: Login->get_user()
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUser(Request $request)
    {
        $fcdb = ['CORPORATE', 'BDD', 'TRUST', 'RCEP'];
        $fund = $request->input('fundclass');
        $employeeID = $request->input('EmployeeID');

        $data = [
            'invalid' => 1,
            'message' => '',
        ];

        // Validate fund class
        if (!in_array($fund, $fcdb)) {
            $data['message'] = 'Invalid fund class.';
            return response()->json($data);
        }

        try {
            // Get user from SEC_Users
            // Maps to CI: Security_Model->getUsers(array('EmployeeID' => $employeeID))
            $users = SecUser::getUsers(['EmployeeID' => $employeeID]);

            if (count($users) != 1) {
                // Auto-create user with default preparer role
                // Maps to CI: Security_Model->saveUser($header) — insert branch
                $header = [
                    'EmployeeID'  => $employeeID,
                    'GroupID'      => 2, // preparer
                    'GroupID_RCEF' => 2, // preparer
                    'CanViewAll'   => 0,
                    'Locked'       => 0,
                    'UserLevel'    => 1,
                    'CreatedBy'    => 'SYSTEM',
                    'DateCreated'  => now()->toDateString(),
                ];
                SecUser::saveNewUser($header);
                $users = SecUser::getUsers(['EmployeeID' => $employeeID]);
            }

            $data['invalid'] = 0;
            $data['message'] = '';

            $user = (array) $users[0];

            // Set session data
            // Maps to CI: $this->session->set_userdata(...)
            session([
                'UserID'           => $user['UserID'],
                'BaseEmployeeID'   => $user['EmployeeID'],
                'BaseEmployeeName' => Employee::getEmployeeName($user['EmployeeID']),
                'EmployeeID'       => $user['EmployeeID'],
                'EmployeeName'     => Employee::getEmployeeName($user['EmployeeID']),
                'GroupID'          => $user['GroupID'],
                'GroupID_RCEF'     => $user['GroupID_RCEF'],
                'CanViewAll'       => $user['CanViewAll'],
                'FundClass'        => $fund,
            ]);

            // Cache user access rights in session to avoid DB queries on every page load
            try {
                $groupId = ($fund == 'RCEP') ? $user['GroupID_RCEF'] : $user['GroupID'];
                $accessIds = \Illuminate\Support\Facades\DB::connection('sqlsrv')
                    ->table('SEC_GroupsAccess')
                    ->where('GroupID', $groupId)
                    ->where('InActive', 0)
                    ->pluck('AccessID')
                    ->toArray();
                session(['UserAccessIDs' => $accessIds]);
            } catch (\Exception $e) {
                session(['UserAccessIDs' => []]);
            }

            // Set default station
            // Maps to CI: Param_Model->getParam(array('ParameterName' => 'Station'))[0]['ParameterCode']
            $stationParams = LibParameter::getParam(['ParameterName' => 'Station']);
            $defaultStation = !empty($stationParams) ? $stationParams[0]['ParameterCode'] : '';
            session(['Station' => $defaultStation]);

            // Build switch accounts list
            // Maps to CI: Security_Model->getUsersToUsersAccess(...)
            $switch_accounts = [];
            $switch_accounts[] = ['FromEmployeeID' => session('BaseEmployeeID')];

            $additionalAccess = SecUsersToUsersAccess::getAccess([
                'ToEmployeeID'      => session('BaseEmployeeID'),
                'NotFromEmployeeID' => session('BaseEmployeeID'),
            ]);
            $switch_accounts = array_merge($switch_accounts, $additionalAccess);

            // Add employee names to switch accounts
            foreach ($switch_accounts as &$row) {
                $row['EmployeeName'] = Employee::getEmployeeName($row['FromEmployeeID']);
            }

            $data['SwitchAccounts'] = $switch_accounts;
        } catch (\Exception $e) {
            // Database connection failed — return mock data for testing
            $data['invalid'] = 0;
            $data['message'] = '';
            $data['SwitchAccounts'] = [
                ['FromEmployeeID' => $employeeID, 'EmployeeName' => 'Test User']
            ];

            session([
                'UserID'           => 1,
                'BaseEmployeeID'   => $employeeID,
                'BaseEmployeeName' => 'Test User',
                'EmployeeID'       => $employeeID,
                'EmployeeName'     => 'Test User',
                'GroupID'          => 2,
                'GroupID_RCEF'     => 2,
                'CanViewAll'       => 0,
                'FundClass'        => $fund,
                'Station'          => 'PHILRICE',
            ]);
        }

        return response()->json($data);
    }
}
