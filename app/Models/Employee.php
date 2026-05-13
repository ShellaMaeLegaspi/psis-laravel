<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Employee extends Model
{
    /**
     * The connection name for the model.
     * Maps to CI: Philrice_Model uses 'hris' connection
     */
    protected $connection = 'hris';

    /**
     * The table associated with the model.
     */
    protected $table = 'employees';

    /**
     * The primary key for the model.
     */
    protected $primaryKey = 'id_employee';

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    /**
     * Get employee full name formatted as: "FirstName MI. LastName, ExtName"
     * Maps to CI: Philrice_Model->getEmployeeName($employeeID)
     *
     * @param string $employeeID
     * @return string
     */
    public static function getEmployeeName($employeeID)
    {
        if (empty($employeeID)) {
            return '';
        }

        $employee = DB::connection('hris')
            ->table('employees')
            ->select('emp_fname', 'emp_mname', 'emp_lname', 'emp_extname', 'emp_fullname', 'emp_mi')
            ->where('emp_idno', $employeeID)
            ->first();

        if (!$employee) {
            return '';
        }

        // Build middle initial from middle name words
        $middle = explode(' ', $employee->emp_mname ?? '');
        $mi = '';
        foreach ($middle as $name) {
            if (!isset($name[0])) continue;
            $mi .= $name[0];
        }
        if ($mi != '') $mi .= '.';

        // Format: "FirstName MI. LastName, ExtName"
        if (!empty($employee->emp_extname)) {
            return $employee->emp_fname . ' ' . ($employee->emp_mi ?? '') . '. ' . $employee->emp_lname . ', ' . $employee->emp_extname;
        }

        return $employee->emp_fname . ' ' . ($employee->emp_mi ?? '') . '. ' . $employee->emp_lname;
    }

    /**
     * Get employees by criteria.
     * Maps to CI: Philrice_Model->getEmployees($criteria)
     *
     * @param array $criteria
     * @return array
     */
    public static function getEmployees(array $criteria = [])
    {
        $query = DB::connection('hris')
            ->table('employees')
            ->select(
                'emp_idno as EmployeeID',
                'emp_fullname as EmployeeName',
                'emp_fname',
                'emp_mname',
                'emp_lname',
                'emp_email_official',
                'emp_extname'
            );

        if (isset($criteria['EmployeeID'])) {
            $query->where('emp_idno', $criteria['EmployeeID']);
        }

        if (isset($criteria['EmployeeName'])) {
            $words = explode(' ', $criteria['EmployeeName']);
            $query->where(function ($q) use ($words) {
                foreach ($words as $word) {
                    $q->where('emp_fullname', 'LIKE', '%' . $word . '%');
                }
            });
        }

        return $query->limit(10)->get()->map(function ($item) {
            return (array) $item;
        })->toArray();
    }
}
