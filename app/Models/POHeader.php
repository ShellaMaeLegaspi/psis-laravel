<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class POHeader extends Model
{
    /**
     * The connection name for the model.
     */
    protected $connection = 'sqlsrv';

    /**
     * The table associated with the model.
     */
    protected $table = 'PO_Header';

    /**
     * The primary key for the model.
     */
    protected $primaryKey = 'POHeaderID';

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    /**
     * Get headers by criteria.
     * Maps to CI: po_model.php->getHeaders()
     *
     * @param array $criteria
     * @return array
     */
    public static function getHeaders(array $criteria = [])
    {
        $fundClass = session('FundClass', 'CORPORATE');
        $connection = 'psis_' . strtolower($fundClass);
        
        $query = DB::connection($connection)
            ->table('PO_Header AS A')
            ->select('A.*', 'B.SupplierName')
            ->leftJoin('PSISRUNDB.dbo.LIB_Suppliers AS B', 'A.SupplierID', '=', 'B.SupplierID');

        $userid = session('EmployeeID');
        $canViewAll = session('CanViewAll', 0);

        if (!$canViewAll) {
            $query->where(function ($q) use ($userid) {
                $q->where('A.PreparedBy', $userid)
                  ->orWhere('A.RequisitionedBy', $userid)
                  ->orWhere('A.EvaluatedBy', $userid)
                  ->orWhere('A.FundsAvailableBy', $userid);
            });
        }

        if (isset($criteria['Status'])) {
            if (is_array($criteria['Status'])) {
                $query->whereIn('A.Status', $criteria['Status']);
            } else {
                $query->where('A.Status', $criteria['Status']);
            }
        }

        if (isset($criteria['POControlNo'])) {
            $query->where('A.POControlNo', $criteria['POControlNo']);
        }

        if (isset($criteria['PONo'])) {
            $query->where('A.PONo', $criteria['PONo']);
        }

        if (isset($criteria['SupplierName'])) {
            $query->where('B.SupplierName', 'LIKE', '%' . $criteria['SupplierName'] . '%');
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
     * @param int $poHeaderID
     * @return array|null
     */
    public static function getHeader($poHeaderID)
    {
        $fundClass = session('FundClass', 'CORPORATE');
        $connection = 'psis_' . strtolower($fundClass);
        
        $header = DB::connection($connection)
            ->table('PO_Header AS A')
            ->select('A.*', 'B.SupplierName', 'B.SupplierAddress', 'B.SupplierTIN')
            ->leftJoin('PSISRUNDB.dbo.LIB_Suppliers AS B', 'A.SupplierID', '=', 'B.SupplierID')
            ->where('A.POHeaderID', $poHeaderID)
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

        if (isset($data['POHeaderID']) && $data['POHeaderID'] > 0) {
            DB::connection($connection)->table('PO_Header')
                ->where('POHeaderID', $data['POHeaderID'])
                ->update($data);
            return $data['POHeaderID'];
        } else {
            unset($data['POHeaderID']);
            $data['DateCreated'] = $data['DateCreated'] ?? now()->toDateString();
            return DB::connection($connection)->table('PO_Header')->insertGetId($data, 'POHeaderID');
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
            ->table('PO_Header')
            ->whereIn('Status', $status)
            ->count();
    }
}
