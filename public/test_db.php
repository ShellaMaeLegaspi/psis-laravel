<?php
// Test SQL Server connection with different host formats
$tests = [
    '(local)',
    'localhost',
    '127.0.0.1',
    'localhost,1433',
    '.',
];

foreach ($tests as $host) {
    echo "Testing host: $host ... ";
    try {
        $dsn = "sqlsrv:Server=$host;Database=PSISRUNDB;Encrypt=no;TrustServerCertificate=yes";
        $pdo = new PDO($dsn, 'sa', 'User@123', [
            PDO::ATTR_TIMEOUT => 3,
        ]);
        echo "SUCCESS!\n";
        $stmt = $pdo->query("SELECT TOP 1 ParameterName FROM LIB_Parameters");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "  -> Sample data: " . json_encode($row) . "\n";
        $pdo = null;
    } catch (Exception $e) {
        echo "FAILED: " . $e->getMessage() . "\n";
    }
}
