<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;

class SecurityController extends Controller
{
    /**
     * Security / Access page.
     */
    public function userAccess()
    {
        if (!hasAccess(13)) {
            return view('pages.access_denied');
        }

        return view('pages.security.user_access', [
            'elementActive' => 'Security',
        ]);
    }

    /**
     * Security / Groups page.
     */
    public function userGroups()
    {
        if (!hasAccess(14)) {
            return view('pages.access_denied');
        }

        return view('pages.security.user_groups', [
            'elementActive' => 'Security',
        ]);
    }

    /**
     * Security / Assign Project Code page.
     */
    public function assignProjectCode()
    {
        if (!hasAccess(147)) {
            return view('pages.access_denied');
        }

        return view('pages.security.assign_project_code', [
            'elementActive' => 'Security',
        ]);
    }

    /**
     * Security / SPBI Common Preparers page.
     */
    public function spbiCommonPreparers()
    {
        if (!hasAccess(15)) {
            return view('pages.access_denied');
        }

        return view('pages.security.spbi_common_preparers', [
            'elementActive' => 'Security',
        ]);
    }

    /**
     * Security / Users page.
     */
    public function users()
    {
        if (!hasAccess(15)) {
            return view('pages.access_denied');
        }

        return view('pages.security.users', [
            'elementActive' => 'Security',
        ]);
    }

    /**
     * Security / Users to Users page.
     */
    public function userstousers()
    {
        $switchAccounts = [];
        try {
            $switchAccounts = DB::connection('sqlsrv')
                ->table('SEC_UsersToUsersAccess')
                ->where('FromEmployeeID', session('EmployeeID'))
                ->get()
                ->map(function ($item) {
                    $row = (array) $item;
                    $row['EmployeeName'] = Employee::getEmployeeName($row['ToEmployeeID'] ?? '');
                    return $row;
                })
                ->toArray();
        } catch (\Exception $e) {
            $switchAccounts = [];
        }

        return view('pages.security.userstousers', [
            'elementActive' => 'Security',
            'switchAccounts' => $switchAccounts,
        ]);
    }

    /**
     * AJAX: Get users list.
     */
    public function getUsers(Request $request)
    {
        $criteria = $request->input('criteria', []);

        $dbHost = env('DB_HOST', 'localhost');
        $dbPort = env('DB_PORT', '1433');
        $conn = @fsockopen($dbHost, $dbPort, $errno, $errstr, 2);
        if (!$conn) {
            return response()->json([]);
        }
        fclose($conn);

        try {
            $query = DB::connection('sqlsrv')
                ->table('SEC_Users as A')
                ->leftJoin('SEC_Groups as B', 'A.GroupID', '=', 'B.GroupID')
                ->select('A.*', 'B.GroupName');

            if (!empty($criteria['EmployeeID'])) {
                $query->where('A.EmployeeID', $criteria['EmployeeID']);
            }
            if (!empty($criteria['GroupName'])) {
                $query->where('B.GroupName', 'LIKE', '%' . $criteria['GroupName'] . '%');
            }
            if (isset($criteria['InActive'])) {
                $query->where('A.InActive', $criteria['InActive']);
            }

            $data = $query->get()->map(function ($item) {
                return (array) $item;
            })->toArray();

            foreach ($data as $key => &$row) {
                $row['EmployeeName'] = Employee::getEmployeeName($row['EmployeeID'] ?? '');
                if (!empty($criteria['EmployeeName'])) {
                    if (stripos($row['EmployeeName'], $criteria['EmployeeName']) === false) {
                        unset($data[$key]);
                        continue;
                    }
                }
                $row['GroupName_RCEF'] = DB::connection('sqlsrv')
                    ->table('SEC_Groups')
                    ->where('GroupID', $row['GroupID_RCEF'] ?? 0)
                    ->value('GroupName') ?? '';
                $row['CreatedBy_Name'] = Employee::getEmployeeName($row['CreatedBy'] ?? '');
                $row['UpdatedBy_Name'] = Employee::getEmployeeName($row['UpdatedBy'] ?? '');
            }

            return response()->json(array_values($data));
        } catch (\Exception $e) {
            return response()->json([]);
        }
    }

    /**
     * AJAX: Save users to users access.
     */
    public function secSaveUsersToUsers(Request $request)
    {
        $criteria = $request->input('criteria', []);

        try {
            DB::connection('sqlsrv')->table('SEC_UsersToUsersAccess')->insert([
                'FromEmployeeID' => $criteria['FromEmployeeID'] ?? session('EmployeeID'),
                'ToEmployeeID' => $criteria['ToEmployeeID'] ?? '',
                'DateCreated' => date('Y-m-d H:i:s'),
                'CreatedBy' => session('EmployeeID'),
            ]);
            return response()->json(['Status' => 'COMMIT', 'Message' => 'Added access to this user.']);
        } catch (\Exception $e) {
            return response()->json(['Status' => 'ROLLBACK', 'Message' => $e->getMessage()]);
        }
    }

    /**
     * AJAX: Delete users to users access.
     */
    public function secDeleteUsersToUsers(Request $request)
    {
        $criteria = $request->input('criteria', []);

        try {
            DB::connection('sqlsrv')
                ->table('SEC_UsersToUsersAccess')
                ->where('FromEmployeeID', $criteria['FromEmployeeID'] ?? '')
                ->where('ToEmployeeID', $criteria['ToEmployeeID'] ?? '')
                ->delete();
            return response()->json(['Status' => 'COMMIT', 'Message' => 'Deleted access to this user.']);
        } catch (\Exception $e) {
            return response()->json(['Status' => 'ROLLBACK', 'Message' => $e->getMessage()]);
        }
    }

    /**
     * AJAX: Get access list.
     */
    public function getAccess(Request $request)
    {
        $criteria = $request->input('criteria', []);

        $dbHost = env('DB_HOST', 'localhost');
        $dbPort = env('DB_PORT', '1433');
        $conn = @fsockopen($dbHost, $dbPort, $errno, $errstr, 2);
        if (!$conn) {
            return response()->json([]);
        }
        fclose($conn);

        try {
            $query = DB::connection('sqlsrv')->table('SEC_Access');
            if (!empty($criteria['AccessID'])) {
                $query->where('AccessID', $criteria['AccessID']);
            }
            if (!empty($criteria['AccessDescription'])) {
                $query->where('AccessDescription', 'LIKE', '%' . $criteria['AccessDescription'] . '%');
            }
            $data = $query->get()->map(function ($item) {
                return (array) $item;
            })->toArray();

            foreach ($data as &$row) {
                $row['CreatedBy_Name'] = Employee::getEmployeeName($row['CreatedBy'] ?? '');
                $row['UpdatedBy_Name'] = Employee::getEmployeeName($row['UpdatedBy'] ?? '');
            }

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json([]);
        }
    }

    /**
     * AJAX: Save access.
     */
    public function saveAccess(Request $request)
    {
        $header = $request->input('header', []);
        $header['CreatedBy'] = session('EmployeeID');
        $header['DateCreated'] = date('Y-m-d');

        try {
            if (!empty($header['AccessID']) && $header['AccessID'] > 0) {
                DB::connection('sqlsrv')
                    ->table('SEC_Access')
                    ->where('AccessID', $header['AccessID'])
                    ->update([
                        'AccessDescription' => $header['AccessDescription'] ?? '',
                        'InActive' => $header['InActive'] ?? 0,
                        'UpdatedBy' => $header['CreatedBy'],
                        'DateUpdated' => $header['DateCreated'],
                    ]);
            } else {
                DB::connection('sqlsrv')->table('SEC_Access')->insert([
                    'AccessDescription' => $header['AccessDescription'] ?? '',
                    'InActive' => $header['InActive'] ?? 0,
                    'CreatedBy' => $header['CreatedBy'],
                    'DateCreated' => $header['DateCreated'],
                ]);
            }
            return response()->json(['Status' => 'OK', 'Message' => 'Saved successfully.']);
        } catch (\Exception $e) {
            return response()->json(['Status' => 'ROLLBACK', 'Message' => $e->getMessage()]);
        }
    }

    /**
     * AJAX: Get groups list.
     */
    public function getGroups(Request $request)
    {
        $criteria = $request->input('criteria', []);

        $dbHost = env('DB_HOST', 'localhost');
        $dbPort = env('DB_PORT', '1433');
        $conn = @fsockopen($dbHost, $dbPort, $errno, $errstr, 2);
        if (!$conn) {
            return response()->json([]);
        }
        fclose($conn);

        try {
            $query = DB::connection('sqlsrv')->table('SEC_Groups');
            if (!empty($criteria['GroupID'])) {
                $query->where('GroupID', $criteria['GroupID']);
            }
            if (!empty($criteria['GroupName'])) {
                $query->where('GroupName', 'LIKE', '%' . $criteria['GroupName'] . '%');
            }
            $data = $query->get()->map(function ($item) {
                return (array) $item;
            })->toArray();

            foreach ($data as &$row) {
                $row['CreatedBy_Name'] = Employee::getEmployeeName($row['CreatedBy'] ?? '');
                $row['UpdatedBy_Name'] = Employee::getEmployeeName($row['UpdatedBy'] ?? '');
            }

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json([]);
        }
    }

    /**
     * AJAX: Get group access.
     */
    public function getGroupAccess(Request $request)
    {
        $criteria = $request->input('criteria', []);

        try {
            $data = DB::connection('sqlsrv')
                ->table('SEC_GroupsAccess')
                ->where('GroupID', $criteria['GroupID'] ?? 0)
                ->get()
                ->map(function ($item) {
                    return (array) $item;
                })
                ->toArray();

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json([]);
        }
    }

    /**
     * AJAX: Save group.
     */
    public function saveGroup(Request $request)
    {
        $header = $request->input('header', []);
        $header['CreatedBy'] = session('EmployeeID');
        $header['DateCreated'] = date('Y-m-d');

        try {
            if (!empty($header['GroupID']) && $header['GroupID'] > 0) {
                DB::connection('sqlsrv')
                    ->table('SEC_Groups')
                    ->where('GroupID', $header['GroupID'])
                    ->update([
                        'GroupName' => $header['GroupName'] ?? '',
                        'InActive' => $header['InActive'] ?? 0,
                        'UpdatedBy' => $header['CreatedBy'],
                        'DateUpdated' => $header['DateCreated'],
                    ]);
            } else {
                $groupID = DB::connection('sqlsrv')->table('SEC_Groups')->insertGetId([
                    'GroupName' => $header['GroupName'] ?? '',
                    'InActive' => $header['InActive'] ?? 0,
                    'CreatedBy' => $header['CreatedBy'],
                    'DateCreated' => $header['DateCreated'],
                ]);
                $header['GroupID'] = $groupID;
            }

            if (!empty($header['GroupAccess'])) {
                DB::connection('sqlsrv')
                    ->table('SEC_GroupsAccess')
                    ->where('GroupID', $header['GroupID'])
                    ->delete();
                foreach ((array) $header['GroupAccess'] as $accessID) {
                    DB::connection('sqlsrv')->table('SEC_GroupsAccess')->insert([
                        'GroupID' => $header['GroupID'],
                        'AccessID' => $accessID,
                        'InActive' => 0,
                    ]);
                }
            }

            return response()->json(['Status' => 'OK', 'Message' => 'Saved successfully.']);
        } catch (\Exception $e) {
            return response()->json(['Status' => 'ROLLBACK', 'Message' => $e->getMessage()]);
        }
    }

    /**
     * AJAX: Get SPBI common preparers.
     */
    public function getSpbiPreparers(Request $request)
    {
        $criteria = $request->input('criteria', []);

        $dbHost = env('DB_HOST', 'localhost');
        $dbPort = env('DB_PORT', '1433');
        $conn = @fsockopen($dbHost, $dbPort, $errno, $errstr, 2);
        if (!$conn) {
            return response()->json([]);
        }
        fclose($conn);

        try {
            $query = DB::connection('sqlsrv')->table('LIB_Common_Preparers');
            if (!empty($criteria['EmployeeID'])) {
                $query->where('EmployeeID', $criteria['EmployeeID']);
            }
            if (!empty($criteria['GroupName'])) {
                $query->where('GroupName', 'LIKE', '%' . $criteria['GroupName'] . '%');
            }
            if (isset($criteria['InActive'])) {
                $query->where('InActive', $criteria['InActive']);
            }
            $data = $query->get()->map(function ($item) {
                return (array) $item;
            })->toArray();

            foreach ($data as $key => &$row) {
                $row['EmployeeName'] = Employee::getEmployeeName($row['EmployeeID'] ?? '');
                if (!empty($criteria['EmployeeName'])) {
                    if (stripos($row['EmployeeName'], $criteria['EmployeeName']) === false) {
                        unset($data[$key]);
                        continue;
                    }
                }
                $row['CreatedByName'] = Employee::getEmployeeName($row['CreatedBy'] ?? '');
                $row['UpdatedByName'] = Employee::getEmployeeName($row['UpdatedBy'] ?? '');
            }

            return response()->json(array_values($data));
        } catch (\Exception $e) {
            return response()->json([]);
        }
    }

    /**
     * AJAX: Save SPBI common preparer.
     */
    public function saveUser(Request $request)
    {
        $header = $request->input('header', []);
        $header['CreatedBy'] = session('EmployeeID');
        $header['DateCreated'] = date('Y-m-d');

        try {
            if (!empty($header['UserID']) && $header['UserID'] > 0) {
                DB::connection('sqlsrv')
                    ->table('SEC_Users')
                    ->where('UserID', $header['UserID'])
                    ->update([
                        'EmployeeID' => $header['EmployeeID'] ?? '',
                        'GroupID' => $header['GroupID'] ?? 0,
                        'GroupID_RCEF' => $header['GroupID_RCEF'] ?? 0,
                        'UserLevel' => $header['UserLevel'] ?? 1,
                        'CanViewAll' => $header['CanViewAll'] ?? 0,
                        'Locked' => $header['Locked'] ?? 0,
                        'UpdatedBy' => $header['CreatedBy'],
                        'DateUpdated' => $header['DateCreated'],
                    ]);
            } else {
                DB::connection('sqlsrv')->table('SEC_Users')->insert([
                    'EmployeeID' => $header['EmployeeID'] ?? '',
                    'GroupID' => $header['GroupID'] ?? 0,
                    'GroupID_RCEF' => $header['GroupID_RCEF'] ?? 0,
                    'UserLevel' => $header['UserLevel'] ?? 1,
                    'CanViewAll' => $header['CanViewAll'] ?? 0,
                    'Locked' => $header['Locked'] ?? 0,
                    'CreatedBy' => $header['CreatedBy'],
                    'DateCreated' => $header['DateCreated'],
                ]);
            }
            return response()->json(['Status' => 'OK', 'Message' => 'Saved successfully.']);
        } catch (\Exception $e) {
            return response()->json(['Status' => 'ROLLBACK', 'Message' => $e->getMessage()]);
        }
    }

    /**
     * AJAX: Assign Project Code user access (PPMP).
     * Used by `pages/security/assign_project_code.blade.php`.
     */
    public function ppmpAddUserAccess(Request $request)
    {
        $criteria = (array) $request->input('criteria', []);
        $projectCode = trim((string) ($criteria['ProjectCode'] ?? ''));
        $employeeID = trim((string) ($criteria['EmployeeID'] ?? ''));

        if ($projectCode === '' || $employeeID === '') {
            return response()->json([
                'Status' => 'ROLLBACK',
                'Message' => 'Project Code and Employee ID are required.',
            ]);
        }

        try {
            // Best-effort insert, table exists in some deployments.
            DB::connection('sqlsrv')->table('PPMP_UserAccess')->insert([
                'ProjectCode' => $projectCode,
                'EmployeeID' => $employeeID,
                'InActive' => 0,
                'CreatedBy' => session('EmployeeID'),
                'DateCreated' => date('Y-m-d'),
            ]);

            return response()->json(['Status' => 'COMMIT', 'Message' => 'User access saved.']);
        } catch (\Throwable $e) {
            return response()->json(['Status' => 'ROLLBACK', 'Message' => $e->getMessage()]);
        }
    }
}
