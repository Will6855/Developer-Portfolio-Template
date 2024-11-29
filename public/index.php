<?php
use App\Kernel;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

// Load environment variables from .env.local.php if it exists
$envFile = dirname(__DIR__).'/.env.local.php';
if (file_exists($envFile)) {
    $_ENV = array_merge($_ENV, require $envFile);
}

// Determine the environment based on client IP
$developerIps = json_decode($_ENV['DEVELOPER_IPS'] ?? '[]', true);
$isDeveloper = in_array($_SERVER['REMOTE_ADDR'], $developerIps);

$_ENV['APP_ENV'] = $isDeveloper ? 'dev' : 'prod';
$_ENV['APP_DEBUG'] = $isDeveloper ? '1' : '0';

return fn(array $context) => new Kernel($_ENV['APP_ENV'], (bool) $_ENV['APP_DEBUG']);
