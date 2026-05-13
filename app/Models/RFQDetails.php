<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class RFQDetails
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

    public static function getDetails($rfqHeaderID)
    {
        $conn = self::getConnection();
        return DB::connection($conn)->table('RFQ_Details')
            ->where('RFQHeaderID', $rfqHeaderID)
            ->get()
            ->toArray();
    }

    public static function getDetail($rfqDetailsID)
    {
        $conn = self::getConnection();
        $result = DB::connection($conn)->table('RFQ_Details')
            ->where('RFQDetailsID', $rfqDetailsID)
            ->first();

        return $result ? (array) $result : null;
    }

    public static function saveDetail($data)
    {
        $conn = self::getConnection();

        if (isset($data['RFQDetailsID']) && $data['RFQDetailsID']) {
            DB::connection($conn)->table('RFQ_Details')
                ->where('RFQDetailsID', $data['RFQDetailsID'])
                ->update($data);
            return $data['RFQDetailsID'];
        } else {
            return DB::connection($conn)->table('RFQ_Details')->insertGetId($data);
        }
    }

    public static function deleteDetails($rfqHeaderID)
    {
        $conn = self::getConnection();
        return DB::connection($conn)->table('RFQ_Details')
            ->where('RFQHeaderID', $rfqHeaderID)
            ->delete();
    }
}
