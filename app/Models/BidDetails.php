<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BidDetails extends Model
{
    /**
     * The connection name for the model.
     */
    protected $connection = 'sqlsrv';

    /**
     * The table associated with the model.
     */
    protected $table = 'BID_Details';

    /**
     * The primary key for the model.
     */
    protected $primaryKey = 'BIDDetailsID';

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    /**
     * Get details by header ID.
     *
     * @param int $bidHeaderID
     * @return array
     */
    public static function getDetails($bidHeaderID)
    {
        $fundClass = session('FundClass', 'CORPORATE');
        $connection = 'psis_' . strtolower($fundClass);
        
        return DB::connection($connection)
            ->table('BID_Details AS A')
            ->select('A.*', 'B.ItemCode', 'B.SpecDetails', 'B.Unit')
            ->leftJoin('PSISRUNDB.dbo.LIB_Items AS B', 'A.ItemID', '=', 'B.ItemID')
            ->where('A.BIDHeaderID', $bidHeaderID)
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

        if (isset($data['BIDDetailsID']) && $data['BIDDetailsID'] > 0) {
            DB::connection($connection)->table('BID_Details')
                ->where('BIDDetailsID', $data['BIDDetailsID'])
                ->update($data);
            return $data['BIDDetailsID'];
        } else {
            unset($data['BIDDetailsID']);
            return DB::connection($connection)->table('BID_Details')->insertGetId($data, 'BIDDetailsID');
        }
    }

    /**
     * Delete details by header ID.
     *
     * @param int $bidHeaderID
     * @return void
     */
    public static function deleteByHeader($bidHeaderID)
    {
        $fundClass = session('FundClass', 'CORPORATE');
        $connection = 'psis_' . strtolower($fundClass);
        
        DB::connection($connection)
            ->table('BID_Details')
            ->where('BIDHeaderID', $bidHeaderID)
            ->delete();
    }
}
