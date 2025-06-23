<?php
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo '<div class="alert alert-danger">Access Denied: Administrator privileges required.</div>';
    exit();
}

registerCss("assets/css/dashboard.css");

$logFile = __DIR__ . '/../logs/system_activity.log';
$logs = [];

if (file_exists($logFile)) {
    $logLines = file($logFile);

    foreach ($logLines as $line) {
        $line = trim($line);
        if (empty($line)) continue;

        if (preg_match('/\[(.*?)\]/', $line, $timeMatch)) {
            $timestamp = $timeMatch[1];
            $restOfLine = trim(substr($line, strlen($timeMatch[0])));

            // Parse the rest of the fields
            $parts = explode('|', $restOfLine);
            $logEntry = ['timestamp' => $timestamp];

            foreach ($parts as $part) {
                if (strpos($part, ':') !== false) {
                    [$key, $value] = explode(':', $part, 2);
                    $key = strtolower(trim($key));
                    $value = trim($value);

                    $logEntry[$key] = $value;
                }
            }

            if (!empty($logEntry)) {
                $logs[] = $logEntry;
            }
        }
    }
}

// Extract all unique usernames from logs
$allUsernames = [];
foreach ($logs as $log) {
    if (isset($log['username']) && !empty($log['username']) && !in_array($log['username'], $allUsernames)) {
        $allUsernames[] = $log['username'];
    }
}
sort($allUsernames); // Sort alphabetically

if (!empty($_GET['filter'])) {
    $filter = $_GET['filter'];
    $filteredLogs = [];

    foreach ($logs as $log) {
        $match = true;

        foreach ($filter as $key => $value) {
            if (!empty($value)) {
                if ($key === 'date') {
                    // Date specific filtering
                    $logDate = substr($log['timestamp'], 0, 10);
                    if ($logDate !== $value) {
                        $match = false;
                        break;
                    }
                }
                elseif (!isset($log[$key]) || stripos($log[$key], $value) === false) {
                    $match = false;
                    break;
                }
            }
        }

        if ($match) {
            $filteredLogs[] = $log;
        }
    }

    $logs = $filteredLogs;
}

$logs = array_reverse($logs);

$perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 20;
$totalLogs = count($logs);
$totalPages = ceil($totalLogs / $perPage);
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;

if ($currentPage < 1) {
    $currentPage = 1;
} elseif ($currentPage > $totalPages && $totalPages > 0) {
    $currentPage = $totalPages;
}

$offset = ($currentPage - 1) * $perPage;
$paginatedLogs = array_slice($logs, $offset, $perPage);

$logs = $paginatedLogs;

require 'view/logs.php';