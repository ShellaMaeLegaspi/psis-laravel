<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class PTR
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

        $query = DB::connection($conn)->table('PTR_Header as A');

        if (!$canViewAll) {
            $query->where('A.PreparedBy', $userid);
        }

        if (isset($criteria['Status']) && is_array($criteria['Status'])) {
            $query->whereIn('A.Status', $criteria['Status']);
        } elseif (isset($criteria['Status'])) {
            $query->where('A.Status', $criteria['Status']);
        }

        if (isset($criteria['PTRControlNo'])) {
            $query->where('A.PTRControlNo', $criteria['PTRControlNo']);
        }

        if (isset($criteria['PreparedBy'])) {
            $query->where('A.PreparedBy', $criteria['PreparedBy']);
        }

        return $query->orderBy('A.PTRHeaderID', 'DESC')->get()->toArray();
    }

    public static function getHeader($ptrHeaderID)
    {
        $conn = self::getConnection();
        $result = DB::connection($conn)->table('PTR_Header')
            ->where('PTRHeaderID', $ptrHeaderID)
            ->first();

        return $result ? (array) $result : null;
    }

    public static function getHeaderByControlNo($controlNo)
    {
        $conn = self::getConnection();
        $result = DB::connection($conn)->table('PTR_Header')
            ->where('PTRControlNo', $controlNo)
            ->first();

        return $result ? (array) $result : null;
    }

    public static function saveHeader($data)
    {
        $conn = self::getConnection();

        if (isset($data['PTRHeaderID']) && $data['PTRHeaderID']) {
            DB::connection($conn)->table('PTR_Header')
                ->where('PTRHeaderID', $data['PTRHeaderID'])
                ->update($data);
            return $data['PTRHeaderID'];
        } else {
            $data['DateCreated'] = date('Y-m-d H:i:s');
            $data['CreatedBy'] = session('EmployeeID');
            return DB::connection($conn)->table('PTR_Header')->insertGetId($data);
        }
    }

    public static function countByStatus($statuses)
    {
        $conn = self::getConnection();
        $userid = session('EmployeeID');
        $canViewAll = session('CanViewAll', false);

        $query = DB::connection($conn)->table('PTR_Header');

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

    public static function getDetails($ptrHeaderID)
    {
        $conn = self::getConnection();
        return DB::connection($conn)->table('PTR_Details')
            ->where('PTRHeaderID', $ptrHeaderID)
            ->get()
            ->toArray();
    }

    public static function saveDetails($ptrHeaderID, $details)
    {
        $conn = self::getConnection();
        
        DB::connection($conn)->table('PTR_Details')
            ->where('PTRHeaderID', $ptrHeaderID)
            ->delete();

        foreach ($details as $detail) {
            $detail['PTRHeaderID'] = $ptrHeaderID;
            DB::connection($conn)->table('PTR_Details')->insert($detail);
        }

        return true;
    }
}
