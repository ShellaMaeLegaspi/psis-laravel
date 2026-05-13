<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class NTPHeader extends Model
{
    /**
     * The connection name for the model.
     */
    protected $connection = 'sqlsrv';

    /**
     * The table associated with the model.
     */
    protected $table = 'NTP_Header';

    /**
     * The primary key for the model.
     */
    protected $primaryKey = 'NTPHeaderID';

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    /**
     * Get headers by criteria.
     *
     * @param array $criteria
     * @return array
     */
    public static function getHeaders(array $criteria = [])
    {
        $fundClass = session('FundClass', 'CORPORATE');
        $connection = 'psis_' . strtolower($fundClass);
        
        $query = DB::connection($connection)
            ->table('NTP_Header');

        $userid = session('EmployeeID');
        $canViewAll = session('CanViewAll', 0);

        if (!$canViewAll) {
            $query->where('PreparedBy', $userid);
        }

        if (isset($criteria['Status'])) {
            if (is_array($criteria['Status'])) {
                $query->whereIn('Status', $criteria['Status']);
            } else {
                $query->where('Status', $criteria['Status']);
            }
        }

        if (isset($criteria['NTPControlNo'])) {
            $query->where('NTPControlNo', $criteria['NTPControlNo']);
        }

        if (isset($criteria['DateFrom']) && isset($criteria['DateTo'])) {
            $query->whereBetween('DateCreated', [$criteria['DateFrom'], $criteria['DateTo']]);
        }

        return $query->limit(500)->get()->map(function ($item) {
            return (array) $item;
        })->toArray();
    }

    /**
     * Get header by ID.
     *
     * @param int $ntpHeaderID
     * @return array|null
     */
    public static function getHeader($ntpHeaderID)
    {
        $fundClass = session('FundClass', 'CORPORATE');
        $connection = 'psis_' . strtolower($fundClass);
        
        $header = DB::connection($connection)
            ->table('NTP_Header')
            ->where('NTPHeaderID', $ntpHeaderID)
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

        if (isset($data['NTPHeaderID']) && $data['NTPHeaderID'] > 0) {
            DB::connection($connection)->table('NTP_Header')
                ->where('NTPHeaderID', $data['NTPHeaderID'])
                ->update($data);
            return $data['NTPHeaderID'];
        } else {
            unset($data['NTPHeaderID']);
            $data['DateCreated'] = $data['DateCreated'] ?? now()->toDateString();
            return DB::connection($connection)->table('NTP_Header')->insertGetId($data, 'NTPHeaderID');
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
            ->table('NTP_Header')
            ->whereIn('Status', $status)
            ->count();
    }
}
