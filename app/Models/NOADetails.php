<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class NOADetails extends Model
{
    /**
     * The connection name for the model.
     */
    protected $connection = 'sqlsrv';

    /**
     * The table associated with the model.
     */
    protected $table = 'NOA_Details';

    /**
     * The primary key for the model.
     */
    protected $primaryKey = 'NOADetailsID';

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    /**
     * Get details by header ID.
     *
     * @param int $noaHeaderID
     * @return array
     */
    public static function getDetails($noaHeaderID)
    {
        $fundClass = session('FundClass', 'CORPORATE');
        $connection = 'psis_' . strtolower($fundClass);
        
        return DB::connection($connection)
            ->table('NOA_Details AS A')
            ->select('A.*', 'B.ItemCode', 'B.SpecDetails', 'B.Unit')
            ->leftJoin('PSISRUNDB.dbo.LIB_Items AS B', 'A.ItemID', '=', 'B.ItemID')
            ->where('A.NOAHeaderID', $noaHeaderID)
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

        if (isset($data['NOADetailsID']) && $data['NOADetailsID'] > 0) {
            DB::connection($connection)->table('NOA_Details')
                ->where('NOADetailsID', $data['NOADetailsID'])
                ->update($data);
            return $data['NOADetailsID'];
        } else {
            unset($data['NOADetailsID']);
            return DB::connection($connection)->table('NOA_Details')->insertGetId($data, 'NOADetailsID');
        }
    }

    /**
     * Delete details by header ID.
     *
     * @param int $noaHeaderID
     * @return void
     */
    public static function deleteByHeader($noaHeaderID)
    {
        $fundClass = session('FundClass', 'CORPORATE');
        $connection = 'psis_' . strtolower($fundClass);
        
        DB::connection($connection)
            ->table('NOA_Details')
            ->where('NOAHeaderID', $noaHeaderID)
            ->delete();
    }
}
