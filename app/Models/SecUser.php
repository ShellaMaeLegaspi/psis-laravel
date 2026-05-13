<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SecUser extends Model
{
    /**
     * The connection name for the model.
     * Maps to CI: Security_Model uses 'psisrundb' connection
     */
    protected $connection = 'sqlsrv';

    /**
     * The table associated with the model.
     */
    protected $table = 'SEC_Users';

    /**
     * The primary key for the model.
     */
    protected $primaryKey = 'UserID';

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'EmployeeID',
        'GroupID',
        'GroupID_RCEF',
        'CanViewAll',
        'Locked',
        'UserLevel',
        'CreatedBy',
        'DateCreated',
    ];

    /**
     * Get users by criteria.
     * Maps to CI: Security_Model->getUsers($criteria)
     *
     * @param array $criteria
     * @return \Illuminate\Support\Collection
     */
    public static function getUsers(array $criteria)
    {
        $query = DB::connection('sqlsrv')
            ->table('SEC_Users AS A')
            ->select('A.*')
            ->leftJoin('SEC_Groups AS B', 'A.GroupID', '=', 'B.GroupID');

        if (isset($criteria['EmployeeID'])) {
            $query->where('A.EmployeeID', $criteria['EmployeeID']);
        }

        if (isset($criteria['GroupName'])) {
            $query->where('B.GroupName', 'LIKE', '%' . $criteria['GroupName'] . '%');
        }

        if (isset($criteria['InActive'])) {
            $query->where('A.InActive', $criteria['InActive']);
        }

        return $query->get()->toArray();
    }

    /**
     * Save (insert) a new user with default preparer role.
     * Maps to CI: Security_Model->saveUser($header) — insert branch only
     *
     * @param array $header
     * @return void
     */
    public static function saveNewUser(array $header)
    {
        DB::connection('sqlsrv')->table('SEC_Users')->insert([
            'EmployeeID'  => $header['EmployeeID'],
            'GroupID'      => $header['GroupID'],
            'GroupID_RCEF' => $header['GroupID_RCEF'],
            'CanViewAll'   => $header['CanViewAll'],
            'Locked'       => $header['Locked'],
            'UserLevel'    => $header['UserLevel'],
            'CreatedBy'    => $header['CreatedBy'],
            'DateCreated'  => $header['DateCreated'] ?? now()->toDateString(),
        ]);
    }
}
