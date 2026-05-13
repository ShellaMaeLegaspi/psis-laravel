<?php

require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

function tryCount(string $label, string $conn, string $table): void
{
    try {
        $count = DB::connection($conn)->table($table)->count();
        echo "{$label}={$count}\n";
    } catch (Throwable $e) {
        echo "{$label}=ERROR: {$e->getMessage()}\n";
    }
}

tryCount('sqlsrv_SEC_Users', 'sqlsrv', 'SEC_Users');
tryCount('sqlsrv_LIB_Parameters', 'sqlsrv', 'LIB_Parameters');
tryCount('hris_employees', 'hris', 'employees');
tryCount('corporate_PPMP_Header', 'psis_corporate', 'PPMP_Header');
tryCount('corporate_PPMP_Details', 'psis_corporate', 'PPMP_Details');

