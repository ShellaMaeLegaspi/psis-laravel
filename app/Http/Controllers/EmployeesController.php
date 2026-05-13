<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;

class EmployeesController extends Controller
{
    /**
     * AJAX: Search employees from HRIS database.
     */
    public function getEmployees(Request $request)
    {
        $criteria = (array) $request->input('criteria', []);

        if (isset($criteria['EmployeeName'])) {
            $criteria['EmployeeName'] = trim((string) $criteria['EmployeeName']);
            if (strlen($criteria['EmployeeName']) < 2) {
                return response()->json([]);
            }
        }

        try {
            return response()->json(Employee::getEmployees($criteria));
        } catch (\Throwable $e) {
            return response()->json([]);
        }
    }
}

