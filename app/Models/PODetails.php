<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PODetails extends Model
{
    /**
     * The connection name for the model.
     */
    protected $connection = 'sqlsrv';

    /**
     * The table associated with the model.
     */
    protected $table = 'PO_Details';

    /**
     * The primary key for the model.
     */
    protected $primaryKey = 'PODetailsID';

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    /**
     * Get details by header ID.
     *
     * @param int $poHeaderID
     * @return array
     */
    public static function getDetails($poHeaderID)
    {
        $fundClass = session('FundClass', 'CORPORATE');
        $connection = 'psis_' . strtolower($fundClass);
        
        return DB::connection($connection)
            ->table('PO_Details AS A')
            ->select('A.*', 'B.ItemCode', 'B.SpecDetails', 'B.Unit')
            ->leftJoin('PSISRUNDB.dbo.LIB_Items AS B', 'A.ItemID', '=', 'B.ItemID')
            ->where('A.POHeaderID', $poHeaderID)
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

        if (isset($data['PODetailsID']) && $data['PODetailsID'] > 0) {
            DB::connection($connection)->table('PO_Details')
                ->where('PODetailsID', $data['PODetailsID'])
                ->update($data);
            return $data['PODetailsID'];
        } else {
            unset($data['PODetailsID']);
            return DB::connection($connection)->table('PO_Details')->insertGetId($data, 'PODetailsID');
        }
    }

    /**
     * Delete details by header ID.
     *
     * @param int $poHeaderID
     * @return void
     */
    public static function deleteByHeader($poHeaderID)
    {
        $fundClass = session('FundClass', 'CORPORATE');
        $connection = 'psis_' . strtolower($fundClass);
        
        DB::connection($connection)
            ->table('PO_Details')
            ->where('POHeaderID', $poHeaderID)
            ->delete();
    }
}
