<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class PRDetails
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

    public static function getDetails($prHeaderID)
    {
        $conn = self::getConnection();
        return DB::connection($conn)->table('PR_Details')
            ->where('PRHeaderID', $prHeaderID)
            ->get()
            ->toArray();
    }

    public static function getDetail($prDetailsID)
    {
        $conn = self::getConnection();
        $result = DB::connection($conn)->table('PR_Details')
            ->where('PRDetailsID', $prDetailsID)
            ->first();

        return $result ? (array) $result : null;
    }

    public static function saveDetail($data)
    {
        $conn = self::getConnection();

        if (isset($data['PRDetailsID']) && $data['PRDetailsID']) {
            DB::connection($conn)->table('PR_Details')
                ->where('PRDetailsID', $data['PRDetailsID'])
                ->update($data);
            return $data['PRDetailsID'];
        } else {
            return DB::connection($conn)->table('PR_Details')->insertGetId($data);
        }
    }

    public static function deleteDetails($prHeaderID)
    {
        $conn = self::getConnection();
        return DB::connection($conn)->table('PR_Details')
            ->where('PRHeaderID', $prHeaderID)
            ->delete();
    }
}
