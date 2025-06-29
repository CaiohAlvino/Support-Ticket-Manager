<?php
// config/Logger.php

class Logger
{
    private $logDir;
    private $weekDir;
    private $allLogFile;
    private $errorLogFile;
    private $logLevel;
    private $logLevels = [
        'DEBUG' => 0,
        'INFO' => 1,
        'WARNING' => 2,
        'ERROR' => 3
    ];
    private $jsonLogFile;

    public function __construct($logDir = __DIR__ . '/../logs', $logLevel = 'DEBUG')
    {
        $this->logDir = $logDir;
        $this->logLevel = $logLevel;
        $this->weekDir = $this->getCurrentWeekDir();
        $this->allLogFile = $this->weekDir . '/all.log';
        $this->errorLogFile = $this->weekDir . '/error.log';
        $this->jsonLogFile = $this->weekDir . '/all.json';
        $this->ensureLogFiles();
    }

    private function getCurrentWeekDir()
    {
        $now = new DateTime();
        $now->setTime(0, 0, 0);
        // Domingo = 0, segunda = 1, ...
        $startOfWeek = clone $now;
        $startOfWeek->modify('last sunday');
        $endOfWeek = clone $startOfWeek;
        $endOfWeek->modify('+6 days');
        $dirName = $startOfWeek->format('Y-m-d') . '_a_' . $endOfWeek->format('Y-m-d');
        $weekDir = $this->logDir . '/' . $dirName;
        if (!is_dir($weekDir)) {
            mkdir($weekDir, 0777, true);
        }
        return $weekDir;
    }

    private function ensureLogFiles()
    {
        if (!file_exists($this->allLogFile)) {
            file_put_contents($this->allLogFile, "");
        }
        if (!file_exists($this->errorLogFile)) {
            file_put_contents($this->errorLogFile, "");
        }
        if (!file_exists($this->jsonLogFile)) {
            file_put_contents($this->jsonLogFile, "");
        }
    }

    public function setLogLevel($level)
    {
        if (isset($this->logLevels[$level])) {
            $this->logLevel = $level;
        }
    }

    public function log($message, $type = 'INFO', $user = null, $context = [])
    {
        if ($this->logLevels[$type] < $this->logLevels[$this->logLevel]) {
            return;
        }
        $date = date('Y-m-d H:i:s');
        $userStr = $user ? "[User: $user] " : "";
        $logLine = "[$date] [$type] $userStr$message\n";
        file_put_contents($this->allLogFile, $logLine, FILE_APPEND);
        if ($type === 'ERROR') {
            file_put_contents($this->errorLogFile, $logLine, FILE_APPEND);
        }
        // Log JSON
        $jsonData = [
            'date' => $date,
            'type' => $type,
            'user' => $user,
            'message' => $message,
            'context' => $context
        ];
        file_put_contents($this->jsonLogFile, json_encode($jsonData) . "\n", FILE_APPEND);
    }

    public function logException($exception, $user = null, $context = [])
    {
        $user = $user ?: (isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : null);

        $msg = $exception->getMessage() . ' in ' . $exception->getFile() . ':' . $exception->getLine();
        $context['trace'] = $exception->getTraceAsString();
        $this->log($msg, 'ERROR', $user, $context);
    }

    public function cleanOldLogs($months = 6)
    {
        $dirs = glob($this->logDir . '/*', GLOB_ONLYDIR);
        $now = time();
        foreach ($dirs as $dir) {
            $dirName = basename($dir);
            $parts = explode('_a_', $dirName);
            if (count($parts) === 2) {
                $start = strtotime($parts[0]);
                if ($start && ($now - $start) > ($months * 30 * 24 * 60 * 60)) {
                    $this->deleteDir($dir);
                }
            }
        }
    }

    private function deleteDir($dir)
    {
        if (!file_exists($dir)) return;
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') continue;
            $path = $dir . DIRECTORY_SEPARATOR . $item;
            if (is_dir($path)) {
                $this->deleteDir($path);
            } else {
                unlink($path);
            }
        }
        rmdir($dir);
    }
}
