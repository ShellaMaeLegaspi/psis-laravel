<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SPBIHeader extends Model
{
    protected $table = 'SPBI_Header';
    protected $primaryKey = 'SPBIHeaderID';
    public $timestamps = false;
    protected $guarded = [];

    private static function psisConn()
    {
        return match (session('FundClass')) {
            'BDD' => 'psis_bdd',
            'TRUST' => 'psis_trust',
            'RCEP' => 'psis_rcep',
            default => 'psis_corporate',
        };
    }

    public static function getHeaders($criteria = [])
    {
        $query = DB::connection(self::psisConn())->table('SPBI_Header')
            ->leftJoin('PPMP_Header', 'SPBI_Header.PPMPHeaderID', '=', 'PPMP_Header.PPMPHeaderID')
            ->select('SPBI_Header.*', 'PPMP_Header.ProjectCode', 'PPMP_Header.ProjectTitle')
            ->limit(500);

        if (!empty($criteria['Status'])) {
            if (is_array($criteria['Status'])) {
                $query->whereIn('SPBI_Header.Status', $criteria['Status']);
            } else {
                $query->where('SPBI_Header.Status', $criteria['Status']);
            }
        }

        if (!empty($criteria['EncodedBy'])) {
            $query->where('SPBI_Header.EncodedBy', $criteria['EncodedBy']);
        }

        if (!empty($criteria['SPBIYear'])) {
            $query->where('SPBI_Header.SPBIYear', $criteria['SPBIYear']);
        }

        return $query->get();
    }

    public static function getDetails($spbiHeaderID)
    {
        return DB::connection(self::psisConn())->table('SPBI_Details')
            ->leftJoin('PPMP_Items', 'SPBI_Details.PPMPDetailsID', '=', 'PPMP_Items.PPMPDetailsID')
            ->leftJoin('PSISRUNDB.dbo.LIB_Items', 'PPMP_Items.ItemID', '=', 'PSISRUNDB.dbo.LIB_Items.ItemID')
            ->leftJoin('PSISRUNDB.dbo.StockCard_Header', 'SPBI_Details.StockHeaderID', '=', 'PSISRUNDB.dbo.StockCard_Header.StockHeaderID')
            ->leftJoin('PSISRUNDB.dbo.LIB_PPE', 'SPBI_Details.PropertyID', '=', 'PSISRUNDB.dbo.LIB_PPE.PropertyID')
            ->where('SPBI_Details.SPBIHeaderID', $spbiHeaderID)
            ->select('SPBI_Details.*', 'PPMP_Items.Description', 'PPMP_Items.UnitPrice', 
                'PSISRUNDB.dbo.LIB_Items.ItemCode', 'PSISRUNDB.dbo.LIB_Items.ItemDescription')
            ->get();
    }

    public static function getHistory($criteria = [])
    {
        $query = DB::connection(self::psisConn())->table('SPBI_History')
            ->leftJoin('SPBI_Header', 'SPBI_History.SPBIHeaderID', '=', 'SPBI_Header.SPBIHeaderID');

        if (!empty($criteria['SPBIHeaderID'])) {
            $query->where('SPBI_History.SPBIHeaderID', $criteria['SPBIHeaderID']);
        }

        return $query->get();
    }

    public static function saveSPBI($header, $details)
    {
        $conn = DB::connection(self::psisConn());
        
        try {
            return $conn->transaction(function () use ($header, $details, $conn) {
                $spbiHeaderID = $conn->table('SPBI_Header')->insertGetId($header);

                foreach ($details as $detail) {
                    $detail['SPBIHeaderID'] = $spbiHeaderID;
                    $conn->table('SPBI_Details')->insert($detail);
                }

                return $spbiHeaderID;
            });
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public static function approveSPBI($spbiHeaderID, $remarks = '')
    {
        $conn = DB::connection(self::psisConn());
        
        return $conn->transaction(function () use ($spbiHeaderID, $remarks, $conn) {
            $conn->table('SPBI_Header')
                ->where('SPBIHeaderID', $spbiHeaderID)
                ->update([
                    'Status' => 'A',
                    'DateApproved' => now(),
                ]);

            $conn->table('SPBI_History')->insert([
                'SPBIHeaderID' => $spbiHeaderID,
                'Status' => 'A',
                'DateModified' => now(),
                'Remarks' => 'Approved',
            ]);

            return true;
        });
    }

    public static function cancelSPBI($spbiHeaderID, $remarks = '')
    {
        $conn = DB::connection(self::psisConn());
        
        return $conn->transaction(function () use ($spbiHeaderID, $remarks, $conn) {
            $conn->table('SPBI_Header')
                ->where('SPBIHeaderID', $spbiHeaderID)
                ->update([
                    'Status' => 'X',
                    'DateCancelled' => now(),
                ]);

            $conn->table('SPBI_History')->insert([
                'SPBIHeaderID' => $spbiHeaderID,
                'Status' => 'X',
                'DateModified' => now(),
                'Remarks' => 'Cancelled: ' . $remarks,
            ]);

            return true;
        });
    }
}
