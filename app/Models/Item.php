<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Item extends Model
{
    /**
     * The connection name for the model.
     */
    protected $connection = 'sqlsrv';

    /**
     * The table associated with the model.
     */
    protected $table = 'LIB_Items';

    /**
     * The primary key for the model.
     */
    protected $primaryKey = 'ItemID';

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    /**
     * Get items by criteria.
     * Maps to CI: items_model.php
     *
     * @param array $criteria
     * @return array
     */
    public static function getItems(array $criteria = [])
    {
        $query = DB::connection('sqlsrv')
            ->table('LIB_Items')
            ->select('*');

        if (isset($criteria['ItemID'])) {
            $query->where('ItemID', $criteria['ItemID']);
        }

        if (isset($criteria['ItemCode'])) {
            $query->where('ItemCode', 'LIKE', '%' . $criteria['ItemCode'] . '%');
        }

        if (isset($criteria['InActive'])) {
            $query->where('InActive', $criteria['InActive']);
        }

        return $query->limit(100)->get()->map(function ($item) {
            return (array) $item;
        })->toArray();
    }

    /**
     * Get item details by ID.
     *
     * @param int $itemID
     * @return array|null
     */
    public static function getItem($itemID)
    {
        if (empty($itemID)) {
            return null;
        }

        $item = DB::connection('sqlsrv')
            ->table('LIB_Items')
            ->where('ItemID', $itemID)
            ->first();

        return $item ? (array) $item : null;
    }
}
