<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SecUsersToUsersAccess extends Model
{
    /**
     * The connection name for the model.
     * Maps to CI: Security_Model uses 'psisrundb' connection
     */
    protected $connection = 'sqlsrv';

    /**
     * The table associated with the model.
     */
    protected $table = 'SEC_UsersToUsersAccess';

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    /**
     * Get users-to-users access by criteria.
     * Maps to CI: Security_Model->getUsersToUsersAccess($criteria)
     *
     * @param array $criteria
     * @return array
     */
    public static function getAccess(array $criteria)
    {
        $query = DB::connection('sqlsrv')
            ->table('SEC_UsersToUsersAccess')
            ->select('*');

        if (isset($criteria['FromEmployeeID'])) {
            $query->where('FromEmployeeID', $criteria['FromEmployeeID']);
        }

        if (isset($criteria['ToEmployeeID'])) {
            $query->where('ToEmployeeID', $criteria['ToEmployeeID']);
        }

        if (isset($criteria['NotFromEmployeeID'])) {
            $query->where('FromEmployeeID', '!=', $criteria['NotFromEmployeeID']);
        }

        return $query->get()->map(function ($item) {
            return (array) $item;
        })->toArray();
    }
}
