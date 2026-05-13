<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PRHeader extends Model
{
    protected $table = 'PR_Header';
    protected $primaryKey = 'PRHeaderID';
    public $timestamps = false;
    protected $guarded = [];

    /**
     * Get the active PSIS fund-class database connection.
     */
    private static function psisConn()
    {
        return match (session('FundClass')) {
            'BDD' => 'psis_bdd',
            'TRUST' => 'psis_trust',
            'RCEP' => 'psis_rcep',
            default => 'psis_corporate',
        };
    }

    /**
     * Get PR headers with filtering criteria
     */
    public static function getHeaders($criteria = [])
    {
        $query = DB::connection(self::psisConn())->table('PR_Header')
            ->leftJoin('SPBI_Header', 'PR_Header.SPBIHeaderID', '=', 'SPBI_Header.SPBIHeaderID')
            ->leftJoin('PPMP_Header', 'SPBI_Header.PPMPHeaderID', '=', 'PPMP_Header.PPMPHeaderID')
            ->select('PR_Header.*', 'PPMP_Header.ProjectCode', 'PPMP_Header.ProjectTitle', 'SPBI_Header.SPBINo');

        // Apply filtering
        if (!empty($criteria['Status'])) {
            if (is_array($criteria['Status'])) {
                $query->whereIn('PR_Header.Status', $criteria['Status']);
            } else {
                $query->where('PR_Header.Status', $criteria['Status']);
            }
        }

        if (!empty($criteria['EncodedBy'])) {
            $query->where('PR_Header.EncodedBy', $criteria['EncodedBy']);
        }

        if (!empty($criteria['PRControlNo'])) {
            $query->where('PR_Header.PRControlNo', $criteria['PRControlNo']);
        }

        return $query->get();
    }

    /**
     * Get PR details for a specific PR header
     */
    public static function getDetails($criteria = [])
    {
        $query = DB::connection(self::psisConn())->table('PR_Details')
            ->leftJoin('PR_Header', 'PR_Details.PRHeaderID', '=', 'PR_Header.PRHeaderID')
            ->leftJoin('PPMP_Items', 'PR_Details.PPMPDetailsID', '=', 'PPMP_Items.PPMPDetailsID')
            ->leftJoin('PPMP_Header', 'PPMP_Items.PPMPHeaderID', '=', 'PPMP_Header.PPMPHeaderID')
            ->leftJoin('PSISRUNDB.dbo.LIB_Items', 'PPMP_Items.ItemID', '=', 'PSISRUNDB.dbo.LIB_Items.ItemID')
            ->select('PR_Details.*', 'PR_Header.PRControlNo', 'PPMP_Items.ItemID', 
                'PPMP_Items.Description', 'PPMP_Items.Quantity', 'PPMP_Items.UnitPrice');

        if (!empty($criteria['PRHeaderID'])) {
            $query->where('PR_Details.PRHeaderID', $criteria['PRHeaderID']);
        }

        return $query->get();
    }

    /**
     * Get PR history/audit trail
     */
    public static function getHistory($headerid)
    {
        return DB::connection(self::psisConn())->table('PR_History')
            ->where('PRHeaderID', $headerid)
            ->orderBy('HistoryID', 'asc')
            ->get();
    }

    /**
     * Save/Create PR
     */
    public static function savePR($header, $details)
    {
        $conn = DB::connection(self::psisConn());
        
        try {
            return $conn->transaction(function () use ($header, $details, $conn) {
                // Insert PR header
                $prHeaderID = $conn->table('PR_Header')->insertGetId($header);

                // Insert PR details
                foreach ($details as $detail) {
                    $detail['PRHeaderID'] = $prHeaderID;
                    $conn->table('PR_Details')->insert($detail);
                }

                return $prHeaderID;
            });
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Approve PR
     */
    public static function approvePR($prHeaderID, $remarks = '')
    {
        $conn = DB::connection(self::psisConn());
        
        return $conn->transaction(function () use ($prHeaderID, $remarks, $conn) {
            $conn->table('PR_Header')
                ->where('PRHeaderID', $prHeaderID)
                ->update([
                    'Status' => 'A',
                    'DateApproved' => now(),
                    'Remarks' => $remarks,
                ]);

            $conn->table('PR_History')->insert([
                'PRHeaderID' => $prHeaderID,
                'Status' => 'A',
                'DateModified' => now(),
                'Remarks' => 'Approved: ' . $remarks,
            ]);

            return true;
        });
    }

    /**
     * Cancel PR
     */
    public static function cancelPR($prHeaderID, $remarks = '')
    {
        $conn = DB::connection(self::psisConn());
        
        return $conn->transaction(function () use ($prHeaderID, $remarks, $conn) {
            $conn->table('PR_Header')
                ->where('PRHeaderID', $prHeaderID)
                ->update([
                    'Status' => 'X',
                    'DateCancelled' => now(),
                    'Remarks' => $remarks,
                ]);

            $conn->table('PR_History')->insert([
                'PRHeaderID' => $prHeaderID,
                'Status' => 'X',
                'DateModified' => now(),
                'Remarks' => 'Cancelled: ' . $remarks,
            ]);

            return true;
        });
    }
}
