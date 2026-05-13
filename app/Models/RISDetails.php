<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RISDetails extends Model
{
    /**
     * The connection name for the model.
     */
    protected $connection = 'sqlsrv';

    /**
     * The table associated with the model.
     */
    protected $table = 'RIS_Details';

    /**
     * The primary key for the model.
     */
    protected $primaryKey = 'RISDetailsID';

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    /**
     * Get details by header ID.
     *
     * @param int $risHeaderID
     * @return array
     */
    public static function getDetails($risHeaderID)
    {
        $fundClass = session('FundClass', 'CORPORATE');
        $connection = 'psis_' . strtolower($fundClass);
        
        return DB::connection($connection)
            ->table('RIS_Details AS A')
            ->select('A.*', 'B.ItemCode', 'B.SpecDetails', 'B.Unit')
            ->leftJoin('PSISRUNDB.dbo.LIB_Items AS B', 'A.ItemID', '=', 'B.ItemID')
            ->where('A.RISHeaderID', $risHeaderID)
            ->get()
            ->map(function ($item) {
                return (array) $item;
            })
            ->toArray();
    }

    /**
     * Save details.
     *
     * @param array $data
     * @return int
     */
    public static function saveDetails(array $data)
    {
        $fundClass = session('FundClass', 'CORPORATE');
        $connection = 'psis_' . strtolower($fundClass);

        if (isset($data['RISDetailsID']) && $data['RISDetailsID'] > 0) {
            DB::connection($connection)->table('RIS_Details')
                ->where('RISDetailsID', $data['RISDetailsID'])
                ->update($data);
            return $data['RISDetailsID'];
        } else {
            unset($data['RISDetailsID']);
            return DB::connection($connection)->table('RIS_Details')->insertGetId($data, 'RISDetailsID');
        }
    }

    /**
     * Delete details by header ID.
     *
     * @param int $risHeaderID
     * @return void
     */
    public static function deleteByHeader($risHeaderID)
    {
        $fundClass = session('FundClass', 'CORPORATE');
        $connection = 'psis_' . strtolower($fundClass);
        
        DB::connection($connection)
            ->table('RIS_Details')
            ->where('RISHeaderID', $risHeaderID)
            ->delete();
    }
}
