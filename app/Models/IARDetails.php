<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class IARDetails extends Model
{
    /**
     * The connection name for the model.
     */
    protected $connection = 'sqlsrv';

    /**
     * The table associated with the model.
     */
    protected $table = 'IAR_Details';

    /**
     * The primary key for the model.
     */
    protected $primaryKey = 'IARDetailsID';

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    /**
     * Get details by header ID.
     * Maps to CI: iar_model.php->getDetails()
     *
     * @param int $iarHeaderID
     * @return array
     */
    public static function getDetails($iarHeaderID)
    {
        $fundClass = session('FundClass', 'CORPORATE');
        $connection = 'psis_' . strtolower($fundClass);
        
        return DB::connection($connection)
            ->table('IAR_Details AS A')
            ->select('A.*', 'B.ItemCode', 'B.SpecDetails', 'B.Unit')
            ->leftJoin('PSISRUNDB.dbo.LIB_Items AS B', 'A.ItemID', '=', 'B.ItemID')
            ->where('A.IARHeaderID', $iarHeaderID)
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

        if (isset($data['IARDetailsID']) && $data['IARDetailsID'] > 0) {
            // Update
            DB::connection($connection)->table('IAR_Details')
                ->where('IARDetailsID', $data['IARDetailsID'])
                ->update($data);
            return $data['IARDetailsID'];
        } else {
            // Insert
            unset($data['IARDetailsID']);
            return DB::connection($connection)->table('IAR_Details')->insertGetId($data, 'IARDetailsID');
        }
    }

    /**
     * Delete details by header ID.
     *
     * @param int $iarHeaderID
     * @return void
     */
    public static function deleteByHeader($iarHeaderID)
    {
        $fundClass = session('FundClass', 'CORPORATE');
        $connection = 'psis_' . strtolower($fundClass);
        
        DB::connection($connection)
            ->table('IAR_Details')
            ->where('IARHeaderID', $iarHeaderID)
            ->delete();
    }
}
