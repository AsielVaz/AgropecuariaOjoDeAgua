<?php
require_once __DIR__ . '/adminFacturas.php';
require_once __DIR__ . '/adminEvidencias.php';
require_once __DIR__ . '/adminLog.php';

$facturas = new AdministradorFacturas();
$evidencias = new AdministradorEvidencias();
$logs = new AdministradorLog();

$tests = [
    'facturas_semana' => fn() => $facturas->dameFacturasEstaSemana(),
    'suma_semana' => fn() => $facturas->sumaFacturasEstaSemana(),
    'facturas_programadas' => fn() => $facturas->dameFacturas(),
    'suma_programadas' => fn() => $facturas->sumaTodo(),
    'facturas_pagadas' => fn() => $facturas->dameFacturasPagadas(),
    'evidencias' => fn() => $evidencias->dameEvidencias(),
    'logs' => fn() => $logs->dameLog(),
];

$resultados = [];
foreach ($tests as $name => $test) {
    gc_collect_cycles();
    $start = hrtime(true);
    $value = $test();
    $milliseconds = (hrtime(true) - $start) / 1e6;
    $resultados[$name] = [
        'ms' => round($milliseconds, 3),
        'rows' => is_array($value) ? count($value) : null,
        'value_type' => gettype($value),
    ];
}

echo json_encode($resultados, JSON_PRETTY_PRINT);
