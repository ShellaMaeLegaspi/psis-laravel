<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PARDetails extends Model
{
    /**
     * The connection name for the model.
     */
    protected $connection = 'sqlsrv';

    /**
     * The table associated with the model.
     */
    protected $table = 'PAR_Details';

    /**
     * The primary key for the model.
     */
    protected $primaryKey = 'PARDetailsID';

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    /**
     * Get details by header ID.
     *
     * @param int $parHeaderID
     * @return array
     */
    public static function getDetails($parHeaderID)
    {
        $fundClass = session('FundClass', 'CORPORATE');
        $connection = 'psis_' . strtolower($fundClass);
        
        return DB::connection($connection)
            ->table('PAR_Details AS A')
            ->select('A.*', 'B.ItemCode', 'B.SpecDetails', 'B.Unit')
            ->leftJoin('PSISRUNDB.dbo.LIB_Items AS B', 'A.ItemID', '=', 'B.ItemID')
            ->where('A.PARHeaderID', $parHeaderID)
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

        if (isset($data['PARDetailsID']) && $data['PARDetailsID'] > 0) {
            DB::connection($connection)->table('PAR_Details')
                ->where('PARDetailsID', $data['PARDetailsID'])
                ->update($data);
            return $data['PARDetailsID'];
        } else {
            unset($data['PARDetailsID']);
            return DB::connection($connection)->table('PAR_Details')->insertGetId($data, 'PARDetailsID');
        }
    }

    /**
     * Delete details by header ID.
     *
     * @param int $parHeaderID
     * @return void
     */
    public static function deleteByHeader($parHeaderID)
    {
        $fundClass = session('FundClass', 'CORPORATE');
        $connection = 'psis_' . strtolower($fundClass);
        
        DB::connection($connection)
            ->table('PAR_Details')
            ->where('PARHeaderID', $parHeaderID)
            ->delete();
    }
}
