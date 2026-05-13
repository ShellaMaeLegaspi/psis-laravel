<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Status extends Model
{
    /**
     * The connection name for the model.
     */
    protected $connection = 'sqlsrv';

    /**
     * The table associated with the model.
     */
    protected $table = 'LIB_Status';

    /**
     * The primary key for the model.
     */
    protected $primaryKey = 'StatusID';

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    /**
     * Get status name by ID.
     * Maps to CI: status_model.php
     *
     * @param int $statusID
     * @return string
     */
    public static function getStatusName($statusID)
    {
        if (empty($statusID)) {
            return '';
        }

        $status = DB::connection('sqlsrv')
            ->table('LIB_Status')
            ->where('StatusID', $statusID)
            ->first();

        return $status ? $status->StatusName : '';
    }

    /**
     * Get all statuses.
     *
     * @return array
     */
    public static function getAll()
    {
        return DB::connection('sqlsrv')
            ->table('LIB_Status')
            ->where('InActive', 0)
            ->get()
            ->map(function ($item) {
                return (array) $item;
            })
            ->toArray();
    }
}
