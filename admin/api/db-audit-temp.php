<?php
require_once __DIR__ . '/conectorBD.php';

$db = new conector();
$tables = [
    'factura',
    'factura_evidencia',
    'xml_ingresados',
    'proveedores',
    'pagos',
    'aplazo_de_factura',
    'usuarios',
    'recuperacion',
    'log_facturas',
];

$audit = [];
foreach ($tables as $table) {
    $existsResult = $db->ejecutar("SHOW TABLES LIKE '{$table}'");
    if (!$existsResult || $existsResult->num_rows === 0) {
        $audit[$table] = ['missing' => true];
        continue;
    }

    $statusResult = $db->ejecutar("SHOW TABLE STATUS LIKE '{$table}'");
    $status = $statusResult ? $statusResult->fetch_assoc() : null;

    $columnsResult = $db->ejecutar("SHOW COLUMNS FROM `{$table}`");
    $columns = [];
    if ($columnsResult) {
        while ($row = $columnsResult->fetch_assoc()) {
            $columns[] = [
                'name' => $row['Field'],
                'type' => $row['Type'],
                'null' => $row['Null'],
                'key' => $row['Key'],
            ];
        }
    }

    $indexResult = $db->ejecutar("SHOW INDEX FROM `{$table}`");
    $indexes = [];
    if ($indexResult) {
        while ($row = $indexResult->fetch_assoc()) {
            $indexes[] = [
                'name' => $row['Key_name'],
                'unique' => !(bool) $row['Non_unique'],
                'sequence' => (int) $row['Seq_in_index'],
                'column' => $row['Column_name'],
                'cardinality' => isset($row['Cardinality']) ? (int) $row['Cardinality'] : null,
            ];
        }
    }

    $audit[$table] = [
        'rows' => isset($status['Rows']) ? (int) $status['Rows'] : null,
        'engine' => $status['Engine'] ?? null,
        'columns' => $columns,
        'indexes' => $indexes,
    ];
}

echo json_encode($audit, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
