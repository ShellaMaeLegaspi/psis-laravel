<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PPAPHeader extends Model
{
    protected $table = 'PPAP_Header';
    protected $primaryKey = 'PPAPHeaderID';
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
        $query = DB::connection(self::psisConn())->table('PPAP_Header')
            ->leftJoin('PPMP_Header', 'PPAP_Header.PPMPHeaderID', '=', 'PPMP_Header.PPMPHeaderID')
            ->select('PPAP_Header.*', 'PPMP_Header.ProjectCode', 'PPMP_Header.ProjectTitle', 'PPMP_Header.FundClass');

        if (!empty($criteria['Status'])) {
            if (is_array($criteria['Status'])) {
                $query->whereIn('PPAP_Header.Status', $criteria['Status']);
            } else {
                $query->where('PPAP_Header.Status', $criteria['Status']);
            }
        }

        if (!empty($criteria['EncodedBy'])) {
            $query->where('PPAP_Header.EncodedBy', $criteria['EncodedBy']);
        }

        if (!empty($criteria['PPAPYear'])) {
            $query->where('PPAP_Header.PPAPYear', $criteria['PPAPYear']);
        }

        return $query->get();
    }

    public static function getDetails($criteria = [])
    {
        $query = DB::connection(self::psisConn())->table('PPAP_Details')
            ->leftJoin('PPMP_Items', 'PPAP_Details.PPMPDetailsID', '=', 'PPMP_Items.PPMPDetailsID')
            ->leftJoin('PPAP_Header', 'PPAP_Details.PPAPHeaderID', '=', 'PPAP_Header.PPAPHeaderID')
            ->select('PPAP_Details.*', 'PPMP_Items.ItemID', 'PPMP_Items.Description', 
                'PPMP_Items.UnitPrice', 'PPAP_Header.PPAPNo');

        if (!empty($criteria['PPAPHeaderID'])) {
            $query->where('PPAP_Details.PPAPHeaderID', $criteria['PPAPHeaderID']);
        }

        return $query->get();
    }

    public static function getHistory($headerid)
    {
        return DB::connection(self::psisConn())->table('PPAP_History')
            ->where('PPAPHeaderID', $headerid)
            ->orderBy('HistoryID', 'asc')
            ->get();
    }

    public static function savePPAP($header, $details, $summary = [])
    {
        $conn = DB::connection(self::psisConn());
        
        try {
            return $conn->transaction(function () use ($header, $details, $summary, $conn) {
                $ppapHeaderID = $conn->table('PPAP_Header')->insertGetId($header);

                foreach ($details as $detail) {
                    $detail['PPAPHeaderID'] = $ppapHeaderID;
                    $conn->table('PPAP_Details')->insert($detail);
                }

                foreach ($summary as $summ) {
                    $summ['PPAPHeaderID'] = $ppapHeaderID;
                    $conn->table('PPAP_Summary')->insert($summ);
                }

                return $ppapHeaderID;
            });
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public static function approvePPAP($ppapHeaderID, $remarks = '')
    {
        $conn = DB::connection(self::psisConn());
        
        return $conn->transaction(function () use ($ppapHeaderID, $remarks, $conn) {
            $conn->table('PPAP_Header')
                ->where('PPAPHeaderID', $ppapHeaderID)
                ->update([
                    'Status' => 'A',
                    'DateApproved' => now(),
                ]);

            $conn->table('PPAP_History')->insert([
                'PPAPHeaderID' => $ppapHeaderID,
                'Status' => 'A',
                'DateModified' => now(),
                'Remarks' => 'Approved',
            ]);

            return true;
        });
    }
}
