<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class Property
{
    public static function getPropertyCard($criteria = [])
    {
        $conn = 'sqlsrv'; // PSISRUNDB connection

        $query = DB::connection($conn)->table('PropertyCard');

        if (isset($criteria['PropertyID'])) {
            $query->where('PropertyID', $criteria['PropertyID']);
        }

        if (isset($criteria['Reference'])) {
            $query->where('Reference', $criteria['Reference']);
        }

        if (isset($criteria['ReferenceControlNo'])) {
            $query->where('ReferenceControlNo', $criteria['ReferenceControlNo']);
        }

        if (isset($criteria['FundCd'])) {
            $query->where('FundCd', $criteria['FundCd']);
        }

        return $query->orderBy('DateLog', 'DESC')->get()->toArray();
    }

    public static function insertPropertyCard($data)
    {
        $conn = 'sqlsrv'; // PSISRUNDB connection

        // Delete existing record if exists
        DB::connection($conn)->table('PropertyCard')
            ->where('Reference', $data['Reference'])
            ->where('ReferenceControlNo', $data['ReferenceControlNo'])
            ->where('FundCd', $data['FundCd'])
            ->where('PropertyID', $data['PropertyID'])
            ->delete();

        // Insert new record
        return DB::connection($conn)->table('PropertyCard')->insert($data);
    }

    public static function getPropertyBalance($propertyID, $fundCd)
    {
        $conn = 'sqlsrv'; // PSISRUNDB connection

        $result = DB::connection($conn)->table('PropertyCard')
            ->where('PropertyID', $propertyID)
            ->where('FundCd', $fundCd)
            ->orderBy('DateLog', 'DESC')
            ->first();

        return $result ? (array) $result : null;
    }
}
