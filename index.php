<?php

declare(strict_types=1);

use App\{Connection, Service, Query};

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

const PATH = __DIR__;
const RUNTIME_DIR = '/runtime/';

require_once PATH . '/vendor/autoload.php';

if(php_sapi_name() !== 'cli') return;

$logs = PATH . RUNTIME_DIR;
if (!is_dir($logs)) mkdir($logs);

try {

    $fileName = $argv[1] ?? null;
    if (!$fileName)
        throw new RuntimeException('Provide filename as argument.');

    $filePath = PATH . "/data/$fileName";
    if (!file_exists($filePath))
        throw new RuntimeException("File is not found: $filePath");

    $data = file($filePath);
    $data = array_map(fn($item) => str_replace("\n","", $item), $data);

    $conn = Connection::get()->connect();
    $service = new Service(new Query($conn));
    $service->import($data);
    echo "Done" . PHP_EOL;
} catch (Exception $exception) {
    die($exception->getMessage() . PHP_EOL);
}
