<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PARHeader extends Model
{
    /**
     * The connection name for the model.
     */
    protected $connection = 'sqlsrv';

    /**
     * The table associated with the model.
     */
    protected $table = 'PAR_Header';

    /**
     * The primary key for the model.
     */
    protected $primaryKey = 'PARHeaderID';

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    /**
     * Get headers by criteria.
     * Maps to CI: par_model.php->getHeaders()
     *
     * @param array $criteria
     * @return array
     */
    public static function getHeaders(array $criteria = [])
    {
        $fundClass = session('FundClass', 'CORPORATE');
        $connection = 'psis_' . strtolower($fundClass);
        
        $query = DB::connection($connection)
            ->table('PAR_Header AS A');

        $userid = session('EmployeeID');
        $canViewAll = session('CanViewAll', 0);

        if (!$canViewAll) {
            $query->where('A.PreparedBy', $userid);
        }

        if (isset($criteria['Status'])) {
            if (is_array($criteria['Status'])) {
                $query->whereIn('A.Status', $criteria['Status']);
            } else {
                $query->where('A.Status', $criteria['Status']);
            }
        }

        if (isset($criteria['PARControlNo'])) {
            $query->where('A.PARControlNo', $criteria['PARControlNo']);
        }

        if (isset($criteria['PARNo'])) {
            $query->where('A.PARNo', $criteria['PARNo']);
        }

        if (isset($criteria['Particulars'])) {
            $query->where('A.Particulars', 'LIKE', '%' . $criteria['Particulars'] . '%');
        }

        if (isset($criteria['DateFrom']) && isset($criteria['DateTo'])) {
            $query->whereBetween('A.DateCreated', [$criteria['DateFrom'], $criteria['DateTo']]);
        }

        return $query->limit(500)->get()->map(function ($item) {
            return (array) $item;
        })->toArray();
    }

    /**
     * Get header by ID.
     *
     * @param int $parHeaderID
     * @return array|null
     */
    public static function getHeader($parHeaderID)
    {
        $fundClass = session('FundClass', 'CORPORATE');
        $connection = 'psis_' . strtolower($fundClass);
        
        $header = DB::connection($connection)
            ->table('PAR_Header')
            ->where('PARHeaderID', $parHeaderID)
            ->first();

        return $header ? (array) $header : null;
    }

    /**
     * Save header.
     *
     * @param array $data
     * @return int
     */
    public static function saveHeader(array $data)
    {
        $fundClass = session('FundClass', 'CORPORATE');
        $connection = 'psis_' . strtolower($fundClass);

        if (isset($data['PARHeaderID']) && $data['PARHeaderID'] > 0) {
            DB::connection($connection)->table('PAR_Header')
                ->where('PARHeaderID', $data['PARHeaderID'])
                ->update($data);
            return $data['PARHeaderID'];
        } else {
            unset($data['PARHeaderID']);
            $data['DateCreated'] = $data['DateCreated'] ?? now()->toDateString();
            return DB::connection($connection)->table('PAR_Header')->insertGetId($data, 'PARHeaderID');
        }
    }

    /**
     * Count headers by status.
     *
     * @param array $status
     * @return int
     */
    public static function countByStatus(array $status)
    {
        $fundClass = session('FundClass', 'CORPORATE');
        $connection = 'psis_' . strtolower($fundClass);
        
        return DB::connection($connection)
            ->table('PAR_Header')
            ->whereIn('Status', $status)
            ->count();
    }
}
