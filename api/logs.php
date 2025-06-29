<?php
// api/logs.php
// Endpoint para visualização de logs (apenas para administradores)

require_once __DIR__ . '/../config/Logger.php';

// Simulação de autenticação/admin (ajuste conforme seu sistema)
function isAdmin()
{
    // Exemplo: verifique sessão, token, etc
    return true; // Troque para sua lógica real
}

header('Content-Type: application/json');

if (!isAdmin()) {
    http_response_code(403);
    echo json_encode(['error' => 'Acesso negado']);
    exit;
}

$logger = new Logger();

// Parâmetros opcionais: semana, tipo, limite, formato
$week = isset($_GET['week']) ? $_GET['week'] : null;
$type = isset($_GET['type']) ? strtoupper($_GET['type']) : null;
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 100;
$format = isset($_GET['format']) ? strtolower($_GET['format']) : 'json'; // json ou csv

$logDir = __DIR__ . '/../logs';
$dirs = glob($logDir . '/*', GLOB_ONLYDIR);
$weeks = array_map('basename', $dirs);

if ($week && in_array($week, $weeks)) {
    $jsonFile = $logDir . '/' . $week . '/all.json';
    if (!file_exists($jsonFile)) {
        http_response_code(404);
        echo json_encode(['error' => 'Arquivo de log não encontrado']);
        exit;
    }
    $lines = file($jsonFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $logs = array_reverse($lines); // logs mais recentes primeiro
    $result = [];
    foreach ($logs as $line) {
        $entry = json_decode($line, true);
        if (!$entry) continue;
        if ($type && $entry['type'] !== $type) continue;
        $result[] = $entry;
        if (count($result) >= $limit) break;
    }

    if ($format === 'csv') {
        // Exporta como CSV
        $csv = [];
        if (!empty($result)) {
            // Cabeçalho
            $header = array_keys($result[0]);
            $csv[] = implode(';', $header);
            foreach ($result as $row) {
                // Converte context para string JSON
                $row['context'] = json_encode($row['context'], JSON_UNESCAPED_UNICODE);
                $csv[] = implode(';', array_map(function ($v) {
                    return is_array($v) ? json_encode($v, JSON_UNESCAPED_UNICODE) : $v;
                }, $row));
            }
        }
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="logs_' . $week . '.csv"');
        echo implode("\n", $csv);
        exit;
    }

    // Exporta como JSON (padrão)
    echo json_encode([
        'week' => $week,
        'logs' => $result
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

// Se não passar semana, retorna lista de semanas disponíveis
echo json_encode([
    'weeks' => $weeks
]);
