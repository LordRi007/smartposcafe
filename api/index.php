<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// 1. Prepare temporary storage in /tmp (writable in Vercel serverless)
$storageBase = '/tmp/storage';
$subDirs = [
    $storageBase . '/app/public',
    $storageBase . '/framework/cache/data',
    $storageBase . '/framework/sessions',
    $storageBase . '/framework/views',
    $storageBase . '/logs',
];

foreach ($subDirs as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
}

// 2. Set environment variables for storage, view compiled path and fallbacks
putenv("VIEW_COMPILED_PATH={$storageBase}/framework/views");
$_ENV['VIEW_COMPILED_PATH'] = "{$storageBase}/framework/views";
$_SERVER['VIEW_COMPILED_PATH'] = "{$storageBase}/framework/views";

// Fallback APP_KEY if not configured in Vercel settings
$currentKey = getenv('APP_KEY') ?: ($_ENV['APP_KEY'] ?? null);
if (empty($currentKey)) {
    $fallbackKey = 'base64:dgkL7QAOKCA7O0U6mWA/6wQDDOiaJarbOJIbqtanF5Y=';
    putenv("APP_KEY={$fallbackKey}");
    $_ENV['APP_KEY'] = $fallbackKey;
    $_SERVER['APP_KEY'] = $fallbackKey;
}

// Prevent database crashes for session & cache if cloud DB is not connected yet
if (empty(getenv('SESSION_DRIVER')) && empty($_ENV['SESSION_DRIVER'])) {
    putenv('SESSION_DRIVER=cookie');
    $_ENV['SESSION_DRIVER'] = 'cookie';
    $_SERVER['SESSION_DRIVER'] = 'cookie';
}

if (empty(getenv('CACHE_STORE')) && empty($_ENV['CACHE_STORE'])) {
    putenv('CACHE_STORE=array');
    $_ENV['CACHE_STORE'] = 'array';
    $_SERVER['CACHE_STORE'] = 'array';
}

// If SQLite is used and default path is inside read-only root, point to /tmp
$dbConn = getenv('DB_CONNECTION') ?: ($_ENV['DB_CONNECTION'] ?? null);
if ($dbConn === 'sqlite' || empty($dbConn)) {
    $sqliteFile = '/tmp/database.sqlite';
    $seedFile = __DIR__ . '/../database/seed_database.sqlite';

    if (!file_exists($sqliteFile) || filesize($sqliteFile) === 0) {
        if (file_exists($seedFile)) {
            @copy($seedFile, $sqliteFile);
        } else {
            @touch($sqliteFile);
        }
    }
    putenv('DB_CONNECTION=sqlite');
    $_ENV['DB_CONNECTION'] = 'sqlite';
    $_SERVER['DB_CONNECTION'] = 'sqlite';

    putenv("DB_DATABASE={$sqliteFile}");
    $_ENV['DB_DATABASE'] = $sqliteFile;
    $_SERVER['DB_DATABASE'] = $sqliteFile;
}

// 3. Register autoloader
require __DIR__ . '/../vendor/autoload.php';

// 4. Bootstrap Laravel application
/** @var Application $app */
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Bind storage path to /tmp/storage
$app->useStoragePath($storageBase);

// 5. Run request
try {
    $app->handleRequest(Request::capture());
} catch (\Throwable $e) {
    // Show detailed error output if something fails
    http_response_code(500);
    header('Content-Type: text/html; charset=UTF-8');
    echo '<!DOCTYPE html><html><head><title>Deployment Error</title>';
    echo '<style>body{font-family:sans-serif;padding:30px;background:#f8fafc;color:#1e293b;}h1{color:#e11d48;}pre{background:#0f172a;color:#f8fafc;padding:15px;border-radius:8px;overflow-x:auto;font-size:13px;}</style>';
    echo '</head><body>';
    echo '<h1>Application Error (500)</h1>';
    echo '<p><strong>Message:</strong> ' . htmlspecialchars($e->getMessage()) . '</p>';
    echo '<p><strong>Location:</strong> ' . htmlspecialchars($e->getFile()) . ':' . $e->getLine() . '</p>';
    echo '<h3>Stack Trace</h3>';
    echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
    echo '</body></html>';
    exit;
}
