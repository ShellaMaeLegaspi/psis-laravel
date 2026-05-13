<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class PPAPDetails
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

    public static function getDetails($ppapHeaderID)
    {
        $conn = self::getConnection();
        return DB::connection($conn)->table('PPAP_Details')
            ->where('PPAPHeaderID', $ppapHeaderID)
            ->get()
            ->toArray();
    }

    public static function getDetail($ppapDetailsID)
    {
        $conn = self::getConnection();
        $result = DB::connection($conn)->table('PPAP_Details')
            ->where('PPAPDetailsID', $ppapDetailsID)
            ->first();

        return $result ? (array) $result : null;
    }

    public static function saveDetail($data)
    {
        $conn = self::getConnection();

        if (isset($data['PPAPDetailsID']) && $data['PPAPDetailsID']) {
            DB::connection($conn)->table('PPAP_Details')
                ->where('PPAPDetailsID', $data['PPAPDetailsID'])
                ->update($data);
            return $data['PPAPDetailsID'];
        } else {
            return DB::connection($conn)->table('PPAP_Details')->insertGetId($data);
        }
    }

    public static function deleteDetails($ppapHeaderID)
    {
        $conn = self::getConnection();
        return DB::connection($conn)->table('PPAP_Details')
            ->where('PPAPHeaderID', $ppapHeaderID)
            ->delete();
    }
}
