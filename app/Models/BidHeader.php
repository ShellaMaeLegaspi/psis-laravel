<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BidHeader extends Model
{
    protected $table = 'Bid_Header';
    protected $primaryKey = 'BidHeaderID';
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
        $query = DB::connection(self::psisConn())->table('Bid_Header')
            ->select('Bid_Header.*');

        if (!empty($criteria['Status'])) {
            if (is_array($criteria['Status'])) {
                $query->whereIn('Bid_Header.Status', $criteria['Status']);
            } else {
                $query->where('Bid_Header.Status', $criteria['Status']);
            }
        }

        if (!empty($criteria['EncodedBy'])) {
            $query->where('Bid_Header.EncodedBy', $criteria['EncodedBy']);
        }

        return $query->get();
    }

    public static function getDetails($criteria = [])
    {
        $query = DB::connection(self::psisConn())->table('Bid_Details')
            ->leftJoin('PR_Details', 'Bid_Details.PRDetailsID', '=', 'PR_Details.PRDetailsID')
            ->leftJoin('PR_Header', 'PR_Details.PRHeaderID', '=', 'PR_Header.PRHeaderID')
            ->leftJoin('Bid_Header', 'Bid_Details.BidHeaderID', '=', 'Bid_Header.BidHeaderID')
            ->leftJoin('PPMP_Items', 'PR_Details.PPMPDetailsID', '=', 'PPMP_Items.PPMPDetailsID')
            ->leftJoin('PPMP_Header', 'PPMP_Items.PPMPHeaderID', '=', 'PPMP_Header.PPMPHeaderID')
            ->leftJoin('PSISRUNDB.dbo.LIB_Items', 'PPMP_Items.ItemID', '=', 'PSISRUNDB.dbo.LIB_Items.ItemID')
            ->select('Bid_Details.*', 'PR_Header.PRControlNo', 'PPMP_Items.Description', 
                'PPMP_Items.Quantity', 'PPMP_Items.UnitPrice');

        if (!empty($criteria['BidHeaderID'])) {
            $query->where('Bid_Details.BidHeaderID', $criteria['BidHeaderID']);
        }

        return $query->get();
    }

    public static function getHistory($headerid)
    {
        return DB::connection(self::psisConn())->table('Bid_History')
            ->where('BidHeaderID', $headerid)
            ->orderBy('HistoryID', 'asc')
            ->get();
    }

    public static function saveBid($header, $details)
    {
        $conn = DB::connection(self::psisConn());
        
        try {
            return $conn->transaction(function () use ($header, $details, $conn) {
                $bidHeaderID = $conn->table('Bid_Header')->insertGetId($header);

                foreach ($details as $detail) {
                    $detail['BidHeaderID'] = $bidHeaderID;
                    $conn->table('Bid_Details')->insert($detail);
                }

                return $bidHeaderID;
            });
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public static function approveBid($bidHeaderID, $remarks = '')
    {
        $conn = DB::connection(self::psisConn());
        
        return $conn->transaction(function () use ($bidHeaderID, $remarks, $conn) {
            $conn->table('Bid_Header')
                ->where('BidHeaderID', $bidHeaderID)
                ->update([
                    'Status' => 'A',
                    'DateApproved' => now(),
                ]);

            $conn->table('Bid_History')->insert([
                'BidHeaderID' => $bidHeaderID,
                'Status' => 'A',
                'DateModified' => now(),
                'Remarks' => 'Approved',
            ]);

            return true;
        });
    }

    public static function cancelBid($bidHeaderID, $remarks = '')
    {
        $conn = DB::connection(self::psisConn());
        
        return $conn->transaction(function () use ($bidHeaderID, $remarks, $conn) {
            $conn->table('Bid_Header')
                ->where('BidHeaderID', $bidHeaderID)
                ->update([
                    'Status' => 'X',
                    'DateCancelled' => now(),
                ]);

            $conn->table('Bid_History')->insert([
                'BidHeaderID' => $bidHeaderID,
                'Status' => 'X',
                'DateModified' => now(),
                'Remarks' => 'Cancelled: ' . $remarks,
            ]);

            return true;
        });
    }
}
