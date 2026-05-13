<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class IARHeader extends Model
{
    /**
     * The connection name for the model.
     * Will be dynamically set based on FundClass
     */
    protected $connection = 'sqlsrv';

    /**
     * The table associated with the model.
     */
    protected $table = 'IAR_Header';

    /**
     * The primary key for the model.
     */
    protected $primaryKey = 'IARHeaderID';

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    /**
     * Get headers by criteria.
     * Maps to CI: iar_model.php->getHeaders()
     *
     * @param array $criteria
     * @return array
     */
    public static function getHeaders(array $criteria = [])
    {
        $fundClass = session('FundClass', 'CORPORATE');
        $connection = 'psis_' . strtolower($fundClass);
        
        $query = DB::connection($connection)
            ->table('IAR_Header AS A')
            ->select('A.*', 'B.SupplierName', 'B.SupplierAddress', 'B.SupplierTIN')
            ->leftJoin('PSISRUNDB.dbo.LIB_Suppliers AS B', 'A.SupplierID', '=', 'B.SupplierID');

        $userid = session('EmployeeID');
        $canViewAll = session('CanViewAll', 0);
        $hasAccess41 = hasAccess(41);

        if (!$canViewAll && !$hasAccess41) {
            $query->where(function ($q) use ($userid) {
                $q->where('A.PreparedBy', $userid)
                  ->orWhere('A.InspectedBy', $userid)
                  ->orWhere('A.AcceptedBy', $userid);
            });
        }

        if (isset($criteria['Status'])) {
            if (is_array($criteria['Status'])) {
                $query->whereIn('A.Status', $criteria['Status']);
            } else {
                $query->where('A.Status', $criteria['Status']);
            }
        }

        if (isset($criteria['IARControlNo'])) {
            $query->where('A.IARControlNo', $criteria['IARControlNo']);
        }

        if (isset($criteria['IARNo'])) {
            $query->where('A.IARNo', $criteria['IARNo']);
        }

        if (isset($criteria['PreparedBy'])) {
            $query->where('A.PreparedBy', $criteria['PreparedBy']);
        }

        if (isset($criteria['InspectedBy'])) {
            $query->where('A.InspectedBy', $criteria['InspectedBy']);
        }

        if (isset($criteria['AcceptedBy'])) {
            $query->where('A.AcceptedBy', $criteria['AcceptedBy']);
        }

        if (isset($criteria['RespoCenter'])) {
            $query->where('A.RespoCenter', $criteria['RespoCenter']);
        }

        if (isset($criteria['DivCode'])) {
            $query->where('A.DivCode', $criteria['DivCode']);
        }

        if (isset($criteria['SupplierName'])) {
            $query->where('B.SupplierName', 'LIKE', '%' . $criteria['SupplierName'] . '%');
        }

        if (isset($criteria['Remarks'])) {
            $query->where('A.Remarks', 'LIKE', '%' . $criteria['Remarks'] . '%');
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
     * @param int $iarHeaderID
     * @return array|null
     */
    public static function getHeader($iarHeaderID)
    {
        $fundClass = session('FundClass', 'CORPORATE');
        $connection = 'psis_' . strtolower($fundClass);
        
        $header = DB::connection($connection)
            ->table('IAR_Header AS A')
            ->select('A.*', 'B.SupplierName', 'B.SupplierAddress', 'B.SupplierTIN')
            ->leftJoin('PSISRUNDB.dbo.LIB_Suppliers AS B', 'A.SupplierID', '=', 'B.SupplierID')
            ->where('A.IARHeaderID', $iarHeaderID)
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

        if (isset($data['IARHeaderID']) && $data['IARHeaderID'] > 0) {
            // Update
            DB::connection($connection)->table('IAR_Header')
                ->where('IARHeaderID', $data['IARHeaderID'])
                ->update($data);
            return $data['IARHeaderID'];
        } else {
            // Insert
            unset($data['IARHeaderID']);
            $data['DateCreated'] = $data['DateCreated'] ?? now()->toDateString();
            return DB::connection($connection)->table('IAR_Header')->insertGetId($data, 'IARHeaderID');
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
            ->table('IAR_Header')
            ->whereIn('Status', $status)
            ->count();
    }
}
