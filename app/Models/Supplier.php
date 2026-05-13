<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Supplier extends Model
{
    /**
     * The connection name for the model.
     */
    protected $connection = 'sqlsrv';

    /**
     * The table associated with the model.
     */
    protected $table = 'LIB_Suppliers';

    /**
     * The primary key for the model.
     */
    protected $primaryKey = 'SupplierID';

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    /**
     * Get suppliers by criteria.
     * Maps to CI: supplier_model.php
     *
     * @param array $criteria
     * @return array
     */
    public static function getSuppliers(array $criteria = [])
    {
        $query = DB::connection('sqlsrv')
            ->table('LIB_Suppliers')
            ->select('*');

        if (isset($criteria['SupplierID'])) {
            $query->where('SupplierID', $criteria['SupplierID']);
        }

        if (isset($criteria['SupplierName'])) {
            $query->where('SupplierName', 'LIKE', '%' . $criteria['SupplierName'] . '%');
        }

        if (isset($criteria['InActive'])) {
            $query->where('InActive', $criteria['InActive']);
        }

        return $query->get()->map(function ($item) {
            return (array) $item;
        })->toArray();
    }

    /**
     * Get supplier name by ID.
     *
     * @param int $supplierID
     * @return string
     */
    public static function getSupplierName($supplierID)
    {
        if (empty($supplierID)) {
            return '';
        }

        $supplier = DB::connection('sqlsrv')
            ->table('LIB_Suppliers')
            ->where('SupplierID', $supplierID)
            ->first();

        return $supplier ? $supplier->SupplierName : '';
    }
}
