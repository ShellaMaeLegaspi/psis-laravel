<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class PPMPDetails
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

    public static function getDetails($ppmpHeaderID)
    {
        $conn = self::getConnection();
        return DB::connection($conn)->table('PPMP_Details')
            ->where('PPMPHeaderID', $ppmpHeaderID)
            ->get()
            ->toArray();
    }

    public static function getDetail($ppmpDetailsID)
    {
        $conn = self::getConnection();
        $result = DB::connection($conn)->table('PPMP_Details')
            ->where('PPMPDetailsID', $ppmpDetailsID)
            ->first();

        return $result ? (array) $result : null;
    }

    public static function saveDetail($data)
    {
        $conn = self::getConnection();

        if (isset($data['PPMPDetailsID']) && $data['PPMPDetailsID']) {
            DB::connection($conn)->table('PPMP_Details')
                ->where('PPMPDetailsID', $data['PPMPDetailsID'])
                ->update($data);
            return $data['PPMPDetailsID'];
        } else {
            return DB::connection($conn)->table('PPMP_Details')->insertGetId($data);
        }
    }

    public static function deleteDetails($ppmpHeaderID)
    {
        $conn = self::getConnection();
        return DB::connection($conn)->table('PPMP_Details')
            ->where('PPMPHeaderID', $ppmpHeaderID)
            ->delete();
    }
}
