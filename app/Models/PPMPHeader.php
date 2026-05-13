<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class PPMPHeader
{
    protected static function getConnection()
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
        $conn = self::getConnection();
        $userid = session('EmployeeID');
        $canViewAll = session('CanViewAll', false);

        $query = DB::connection($conn)->table('PPMP_Header as A');

        if (!$canViewAll) {
            $query->where(function ($q) use ($userid) {
                $q->where('EncodedBy', $userid)
                  ->orWhere('PreparedBy', $userid)
                  ->orWhere('ApprovedBy', $userid)
                  ->orWhere('CertifiedBy', $userid)
                  ->orWhereIn('ProjectCode', function ($q) use ($userid) {
                      $q->select('ProjectCode')
                        ->from('PPMP_UserAccess')
                        ->where('EmployeeID', $userid);
                  });
            });
        }

        if (isset($criteria['PPMPHeaderID'])) {
            $query->where('A.PPMPHeaderID', $criteria['PPMPHeaderID']);
        }

        if (isset($criteria['PreparatoryFormat'])) {
            $query->where('PreparatoryFormat', $criteria['PreparatoryFormat']);
        }

        if (isset($criteria['ProgramCode'])) {
            $query->where('ProgramCode', $criteria['ProgramCode']);
        }

        if (isset($criteria['ProjectCode'])) {
            $query->where('ProjectCode', $criteria['ProjectCode']);
        }

        if (isset($criteria['PPMPYear'])) {
            $query->where('PPMPYear', $criteria['PPMPYear']);
        }

        if (isset($criteria['Status']) && is_array($criteria['Status'])) {
            $query->whereIn('Status', $criteria['Status']);
        } elseif (isset($criteria['Status'])) {
            $query->where('Status', $criteria['Status']);
        }

        if (isset($criteria['EncodedBy'])) {
            $query->where('EncodedBy', $criteria['EncodedBy']);
        }

        if (isset($criteria['PreparedBy'])) {
            $query->where('PreparedBy', $criteria['PreparedBy']);
        }

        return $query->orderBy('PPMPHeaderID', 'DESC')->get()->toArray();
    }

    public static function getHeader($ppmpHeaderID)
    {
        $conn = self::getConnection();
        $result = DB::connection($conn)->table('PPMP_Header')
            ->where('PPMPHeaderID', $ppmpHeaderID)
            ->first();

        return $result ? (array) $result : null;
    }

    public static function saveHeader($data)
    {
        $conn = self::getConnection();

        if (isset($data['PPMPHeaderID']) && $data['PPMPHeaderID']) {
            DB::connection($conn)->table('PPMP_Header')
                ->where('PPMPHeaderID', $data['PPMPHeaderID'])
                ->update($data);
            return $data['PPMPHeaderID'];
        } else {
            $data['DateCreated'] = date('Y-m-d H:i:s');
            $data['CreatedBy'] = session('EmployeeID');
            return DB::connection($conn)->table('PPMP_Header')->insertGetId($data);
        }
    }

    public static function countByStatus($statuses)
    {
        $conn = self::getConnection();
        $userid = session('EmployeeID');
        $canViewAll = session('CanViewAll', false);

        $query = DB::connection($conn)->table('PPMP_Header');

        if (!$canViewAll) {
            $query->where(function ($q) use ($userid) {
                $q->where('EncodedBy', $userid)
                  ->orWhere('PreparedBy', $userid)
                  ->orWhere('ApprovedBy', $userid)
                  ->orWhere('CertifiedBy', $userid);
            });
        }

        if (is_array($statuses)) {
            $query->whereIn('Status', $statuses);
        } else {
            $query->where('Status', $statuses);
        }

        return $query->count();
    }

    public static function getDetails($ppmpHeaderID)
    {
        $conn = self::getConnection();
        return DB::connection($conn)->table('PPMP_Details')
            ->where('PPMPHeaderID', $ppmpHeaderID)
            ->get()
            ->toArray();
    }

    public static function saveDetails($ppmpHeaderID, $details)
    {
        $conn = self::getConnection();
        
        DB::connection($conn)->table('PPMP_Details')
            ->where('PPMPHeaderID', $ppmpHeaderID)
            ->delete();

        foreach ($details as $detail) {
            $detail['PPMPHeaderID'] = $ppmpHeaderID;
            DB::connection($conn)->table('PPMP_Details')->insert($detail);
        }

        return true;
    }
}
