<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RFQHeader extends Model
{
    protected $table = 'RFQ_Header';
    protected $primaryKey = 'RFQHeaderID';
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
        $query = DB::connection(self::psisConn())->table('RFQ_Header')
            ->select('RFQ_Header.*');

        if (!empty($criteria['Status'])) {
            if (is_array($criteria['Status'])) {
                $query->whereIn('RFQ_Header.Status', $criteria['Status']);
            } else {
                $query->where('RFQ_Header.Status', $criteria['Status']);
            }
        }

        if (!empty($criteria['EncodedBy'])) {
            $query->where('RFQ_Header.EncodedBy', $criteria['EncodedBy']);
        }

        return $query->get();
    }

    public static function getDetails($criteria = [])
    {
        $query = DB::connection(self::psisConn())->table('RFQ_Details')
            ->leftJoin('PR_Details', 'RFQ_Details.PRDetailsID', '=', 'PR_Details.PRDetailsID')
            ->leftJoin('PR_Header', 'PR_Details.PRHeaderID', '=', 'PR_Header.PRHeaderID')
            ->leftJoin('RFQ_Header', 'RFQ_Details.RFQHeaderID', '=', 'RFQ_Header.RFQHeaderID')
            ->leftJoin('PPMP_Items', 'PR_Details.PPMPDetailsID', '=', 'PPMP_Items.PPMPDetailsID')
            ->leftJoin('PPMP_Header', 'PPMP_Items.PPMPHeaderID', '=', 'PPMP_Header.PPMPHeaderID')
            ->leftJoin('PSISRUNDB.dbo.LIB_Items', 'PPMP_Items.ItemID', '=', 'PSISRUNDB.dbo.LIB_Items.ItemID')
            ->select('RFQ_Details.*', 'PR_Header.PRControlNo', 'PPMP_Items.Description', 
                'PPMP_Items.Quantity', 'PPMP_Items.UnitPrice');

        if (!empty($criteria['RFQHeaderID'])) {
            $query->where('RFQ_Details.RFQHeaderID', $criteria['RFQHeaderID']);
        }

        return $query->get();
    }

    public static function getHistory($headerid)
    {
        return DB::connection(self::psisConn())->table('RFQ_History')
            ->where('RFQHeaderID', $headerid)
            ->orderBy('HistoryID', 'asc')
            ->get();
    }

    public static function saveRFQ($header, $details)
    {
        $conn = DB::connection(self::psisConn());
        
        try {
            return $conn->transaction(function () use ($header, $details, $conn) {
                $rfqHeaderID = $conn->table('RFQ_Header')->insertGetId($header);

                foreach ($details as $detail) {
                    $detail['RFQHeaderID'] = $rfqHeaderID;
                    $conn->table('RFQ_Details')->insert($detail);
                }

                return $rfqHeaderID;
            });
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public static function approveRFQ($rfqHeaderID, $remarks = '')
    {
        $conn = DB::connection(self::psisConn());
        
        return $conn->transaction(function () use ($rfqHeaderID, $remarks, $conn) {
            $conn->table('RFQ_Header')
                ->where('RFQHeaderID', $rfqHeaderID)
                ->update([
                    'Status' => 'A',
                    'DateApproved' => now(),
                ]);

            $conn->table('RFQ_History')->insert([
                'RFQHeaderID' => $rfqHeaderID,
                'Status' => 'A',
                'DateModified' => now(),
                'Remarks' => 'Approved',
            ]);

            return true;
        });
    }
}
