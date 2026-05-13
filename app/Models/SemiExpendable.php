<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class SemiExpendable
{
    protected static function getConnection()
    {
        return match (session('FundClass')) {
            'BDD' => 'psis_bdd',
            'TRUST' => 'psis_trust',
            'RCEP' => 'psis_rcep',
            default => 'psis_corporate',
        };
    }

    public static function getHeaders($criteria = [])
    {
        $conn = self::getConnection();
        $userid = session('EmployeeID');
        $canViewAll = session('CanViewAll', false);

        $query = DB::connection($conn)->table('SemiExpendable_Header as A');

        if (!$canViewAll) {
            $query->where('A.PreparedBy', $userid);
        }

        if (isset($criteria['Status']) && is_array($criteria['Status'])) {
            $query->whereIn('A.Status', $criteria['Status']);
        } elseif (isset($criteria['Status'])) {
            $query->where('A.Status', $criteria['Status']);
        }

        if (isset($criteria['SEControlNo'])) {
            $query->where('A.SEControlNo', $criteria['SEControlNo']);
        }

        if (isset($criteria['PreparedBy'])) {
            $query->where('A.PreparedBy', $criteria['PreparedBy']);
        }

        return $query->orderBy('A.SemiExpendableHeaderID', 'DESC')->get()->toArray();
    }

    public static function getHeader($semiExpendableHeaderID)
    {
        $conn = self::getConnection();
        $result = DB::connection($conn)->table('SemiExpendable_Header')
            ->where('SemiExpendableHeaderID', $semiExpendableHeaderID)
            ->first();

        return $result ? (array) $result : null;
    }

    public static function getHeaderByControlNo($controlNo)
    {
        $conn = self::getConnection();
        $result = DB::connection($conn)->table('SemiExpendable_Header')
            ->where('SEControlNo', $controlNo)
            ->first();

        return $result ? (array) $result : null;
    }

    public static function saveHeader($data)
    {
        $conn = self::getConnection();

        if (isset($data['SemiExpendableHeaderID']) && $data['SemiExpendableHeaderID']) {
            DB::connection($conn)->table('SemiExpendable_Header')
                ->where('SemiExpendableHeaderID', $data['SemiExpendableHeaderID'])
                ->update($data);
            return $data['SemiExpendableHeaderID'];
        } else {
            $data['DateCreated'] = date('Y-m-d H:i:s');
            $data['CreatedBy'] = session('EmployeeID');
            return DB::connection($conn)->table('SemiExpendable_Header')->insertGetId($data);
        }
    }

    public static function countByStatus($statuses)
    {
        $conn = self::getConnection();
        $userid = session('EmployeeID');
        $canViewAll = session('CanViewAll', false);

        $query = DB::connection($conn)->table('SemiExpendable_Header');

        if (!$canViewAll) {
            $query->where('PreparedBy', $userid);
        }

        if (is_array($statuses)) {
            $query->whereIn('Status', $statuses);
        } else {
            $query->where('Status', $statuses);
        }

        return $query->count();
    }

    public static function getDetails($semiExpendableHeaderID)
    {
        $conn = self::getConnection();
        return DB::connection($conn)->table('SemiExpendable_Details')
            ->where('SemiExpendableHeaderID', $semiExpendableHeaderID)
            ->get()
            ->toArray();
    }

    public static function saveDetails($semiExpendableHeaderID, $details)
    {
        $conn = self::getConnection();
        
        DB::connection($conn)->table('SemiExpendable_Details')
            ->where('SemiExpendableHeaderID', $semiExpendableHeaderID)
            ->delete();

        foreach ($details as $detail) {
            $detail['SemiExpendableHeaderID'] = $semiExpendableHeaderID;
            DB::connection($conn)->table('SemiExpendable_Details')->insert($detail);
        }

        return true;
    }
}
