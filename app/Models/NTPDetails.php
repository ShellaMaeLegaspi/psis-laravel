<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class NTPDetails extends Model
{
    /**
     * The connection name for the model.
     */
    protected $connection = 'sqlsrv';

    /**
     * The table associated with the model.
     */
    protected $table = 'NTP_Details';

    /**
     * The primary key for the model.
     */
    protected $primaryKey = 'NTPDetailsID';

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    /**
     * Get details by header ID.
     *
     * @param int $ntpHeaderID
     * @return array
     */
    public static function getDetails($ntpHeaderID)
    {
        $fundClass = session('FundClass', 'CORPORATE');
        $connection = 'psis_' . strtolower($fundClass);
        
        return DB::connection($connection)
            ->table('NTP_Details AS A')
            ->select('A.*', 'B.ItemCode', 'B.SpecDetails', 'B.Unit')
            ->leftJoin('PSISRUNDB.dbo.LIB_Items AS B', 'A.ItemID', '=', 'B.ItemID')
            ->where('A.NTPHeaderID', $ntpHeaderID)
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

        if (isset($data['NTPDetailsID']) && $data['NTPDetailsID'] > 0) {
            DB::connection($connection)->table('NTP_Details')
                ->where('NTPDetailsID', $data['NTPDetailsID'])
                ->update($data);
            return $data['NTPDetailsID'];
        } else {
            unset($data['NTPDetailsID']);
            return DB::connection($connection)->table('NTP_Details')->insertGetId($data, 'NTPDetailsID');
        }
    }

    /**
     * Delete details by header ID.
     *
     * @param int $ntpHeaderID
     * @return void
     */
    public static function deleteByHeader($ntpHeaderID)
    {
        $fundClass = session('FundClass', 'CORPORATE');
        $connection = 'psis_' . strtolower($fundClass);
        
        DB::connection($connection)
            ->table('NTP_Details')
            ->where('NTPHeaderID', $ntpHeaderID)
            ->delete();
    }
}
