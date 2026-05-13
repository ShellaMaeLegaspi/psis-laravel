<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class ICSHeader
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

        $query = DB::connection($conn)->table('ICS_Header as A');

        if (!$canViewAll) {
            $query->where('A.PreparedBy', $userid);
        }

        if (isset($criteria['Status']) && is_array($criteria['Status'])) {
            $query->whereIn('A.Status', $criteria['Status']);
        } elseif (isset($criteria['Status'])) {
            $query->where('A.Status', $criteria['Status']);
        }

        if (isset($criteria['Particulars'])) {
            $query->where('A.Particulars', 'like', '%' . $criteria['Particulars'] . '%');
        }

        if (isset($criteria['PreparedBy'])) {
            $query->where('A.PreparedBy', $criteria['PreparedBy']);
        }

        if (isset($criteria['AccountableOfficer'])) {
            $query->where('A.AccountableOfficer', $criteria['AccountableOfficer']);
        }

        if (isset($criteria['CoAccountableOfficer'])) {
            $query->where('A.CoAccountableOfficer', $criteria['CoAccountableOfficer']);
        }

        if (isset($criteria['ICSControlNo'])) {
            $query->where('A.ICSControlNo', $criteria['ICSControlNo']);
        }

        return $query->distinct()->orderBy('A.ICSHeaderID', 'DESC')->get()->toArray();
    }

    public static function getHeader($icsHeaderID)
    {
        $conn = self::getConnection();
        $result = DB::connection($conn)->table('ICS_Header')
            ->where('ICSHeaderID', $icsHeaderID)
            ->first();

        return $result ? (array) $result : null;
    }

    public static function getHeaderByControlNo($controlNo)
    {
        $conn = self::getConnection();
        $result = DB::connection($conn)->table('ICS_Header')
            ->where('ICSControlNo', $controlNo)
            ->first();

        return $result ? (array) $result : null;
    }

    public static function saveHeader($data)
    {
        $conn = self::getConnection();

        if (isset($data['ICSHeaderID']) && $data['ICSHeaderID']) {
            DB::connection($conn)->table('ICS_Header')
                ->where('ICSHeaderID', $data['ICSHeaderID'])
                ->update($data);
            return $data['ICSHeaderID'];
        } else {
            $data['DateCreated'] = date('Y-m-d H:i:s');
            $data['CreatedBy'] = session('EmployeeID');
            return DB::connection($conn)->table('ICS_Header')->insertGetId($data);
        }
    }

    public static function countByStatus($statuses)
    {
        $conn = self::getConnection();
        $userid = session('EmployeeID');
        $canViewAll = session('CanViewAll', false);

        $query = DB::connection($conn)->table('ICS_Header');

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

    public static function getDetails($icsHeaderID)
    {
        $conn = self::getConnection();
        return DB::connection($conn)->table('ICS_Details')
            ->where('ICSHeaderID', $icsHeaderID)
            ->get()
            ->toArray();
    }

    public static function saveDetails($icsHeaderID, $details)
    {
        $conn = self::getConnection();
        
        DB::connection($conn)->table('ICS_Details')
            ->where('ICSHeaderID', $icsHeaderID)
            ->delete();

        foreach ($details as $detail) {
            $detail['ICSHeaderID'] = $icsHeaderID;
            DB::connection($conn)->table('ICS_Details')->insert($detail);
        }

        return true;
    }
}
