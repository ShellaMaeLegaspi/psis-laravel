<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class FMIS
{
    protected static function getConnection()
    {
        return 'hris'; // FMIS data is in HRIS database
    }

    public static function getPrograms($criteria = '', $database = 'corporate')
    {
        $conn = self::getConnection();

        $query = DB::connection($conn)->table('FMIS_Programs');

        if (!empty($criteria)) {
            $query->where('ProgramCode', 'like', '%' . $criteria . '%')
                  ->orWhere('ProgramDesc', 'like', '%' . $criteria . '%');
        }

        return $query->orderBy('ProgramCode')->get()->toArray();
    }

    public static function getProjects($programCode = '', $database = 'corporate')
    {
        $conn = self::getConnection();

        $query = DB::connection($conn)->table('FMIS_Projects');

        if (!empty($programCode)) {
            $query->where('ProgramCode', $programCode);
        }

        return $query->orderBy('ProjectCode')->get()->toArray();
    }

    public static function getOECode($database = 'corporate')
    {
        $conn = self::getConnection();

        return DB::connection($conn)->table('FMIS_ObjectOfExpend')
            ->orderBy('OECode')
            ->get()
            ->toArray();
    }

    public static function getACCode($OECode, $keyword = '')
    {
        $conn = self::getConnection();

        $query = DB::connection($conn)->table('FMIS_AccountCode');

        if (!empty($OECode)) {
            $query->where('OECode', $OECode);
        }

        if (!empty($keyword)) {
            $query->where('ACDesc', 'like', '%' . $keyword . '%');
        }

        return $query->orderBy('ACCode')->get()->toArray();
    }
}
