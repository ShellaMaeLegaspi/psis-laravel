<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class StockCard
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

    public static function getStockCard($criteria = [])
    {
        $conn = self::getConnection();

        $query = DB::connection($conn)->table('LIB_StockCard');

        if (isset($criteria['ItemID'])) {
            $query->where('ItemID', $criteria['ItemID']);
        }

        if (isset($criteria['ItemCode'])) {
            $query->where('ItemCode', $criteria['ItemCode']);
        }

        if (isset($criteria['FundCd'])) {
            $query->where('FundCd', $criteria['FundCd']);
        }

        return $query->orderBy('DateLog', 'DESC')->get()->toArray();
    }

    public static function insertStockCard($data)
    {
        $conn = self::getConnection();
        return DB::connection($conn)->table('LIB_StockCard')->insert($data);
    }

    public static function updateStockCard($stockCardID, $data)
    {
        $conn = self::getConnection();
        return DB::connection($conn)->table('LIB_StockCard')
            ->where('StockCardID', $stockCardID)
            ->update($data);
    }

    public static function getStockBalance($itemID, $fundCd)
    {
        $conn = self::getConnection();

        $result = DB::connection($conn)->table('LIB_StockCard')
            ->where('ItemID', $itemID)
            ->where('FundCd', $fundCd)
            ->orderBy('DateLog', 'DESC')
            ->first();

        return $result ? (array) $result : null;
    }
}
