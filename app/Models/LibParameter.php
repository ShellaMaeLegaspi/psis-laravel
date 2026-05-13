<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class LibParameter extends Model
{
    /**
     * The connection name for the model.
     * Maps to CI: Param_Model uses 'psisrundb' connection
     */
    protected $connection = 'sqlsrv';

    /**
     * The table associated with the model.
     */
    protected $table = 'LIB_Parameters';

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    /**
     * Get parameters by criteria.
     * Maps to CI: Param_Model->getParam($criteria)
     *
     * @param array $criteria
     * @return array
     */
    public static function getParam(array $criteria = [])
    {
        $query = DB::connection('sqlsrv')
            ->table('LIB_Parameters')
            ->select('*');

        if (isset($criteria['ParameterName'])) {
            $query->where('ParameterName', $criteria['ParameterName']);
        }

        if (isset($criteria['ParameterCode'])) {
            $query->where('ParameterCode', $criteria['ParameterCode']);
        }

        if (isset($criteria['InActive'])) {
            $query->where('InActive', $criteria['InActive']);
        }

        return $query->get()->map(function ($item) {
            return (array) $item;
        })->toArray();
    }
}
