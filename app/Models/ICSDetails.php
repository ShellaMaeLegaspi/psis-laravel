<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class ICSDetails
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

    public static function getDetails($icsHeaderID)
    {
        $conn = self::getConnection();
        return DB::connection($conn)->table('ICS_Details')
            ->where('ICSHeaderID', $icsHeaderID)
            ->get()
            ->toArray();
    }

    public static function getDetail($icsDetailsID)
    {
        $conn = self::getConnection();
        $result = DB::connection($conn)->table('ICS_Details')
            ->where('ICSDetailsID', $icsDetailsID)
            ->first();

        return $result ? (array) $result : null;
    }

    public static function saveDetail($data)
    {
        $conn = self::getConnection();

        if (isset($data['ICSDetailsID']) && $data['ICSDetailsID']) {
            DB::connection($conn)->table('ICS_Details')
                ->where('ICSDetailsID', $data['ICSDetailsID'])
                ->update($data);
            return $data['ICSDetailsID'];
        } else {
            return DB::connection($conn)->table('ICS_Details')->insertGetId($data);
        }
    }

    public static function deleteDetails($icsHeaderID)
    {
        $conn = self::getConnection();
        return DB::connection($conn)->table('ICS_Details')
            ->where('ICSHeaderID', $icsHeaderID)
            ->delete();
    }
}
