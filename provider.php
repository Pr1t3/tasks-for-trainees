<?php

require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

if (!isset($_ENV['YANDEX_TOKEN'])) {
    echo "Please set the YANDEX_TOKEN environment variable\n";
    exit(1);
}

$argvParser = new \samejack\PHP\ArgvParser();
$config = $argvParser->parseConfigs($argv);
$config['token'] = $_ENV['YANDEX_TOKEN'];
$appType = $config['type'] ?? $config['t'] ?? '';

if (empty($appType)) {
    echo "Please specify the application type: cli or web\n";
    exit(1);
}

if ($appType === 'cli') {
    $cli = new \ConsoleApp\YandexDiskCli($config);
    $cli->execute();
} else if ($appType === 'web') {
    $host = $config['host'] ?? $config['h'] ?? '127.0.0.1';
    $port = $config['port'] ?? $config['p'] ?? 8000;
    $port = intval($port);

    $envVars = '';
    if (isset($_ENV['YANDEX_TOKEN'])) {
        $envVars = sprintf('YANDEX_TOKEN=%s ', escapeshellarg($_ENV['YANDEX_TOKEN']));
    }

    $command = sprintf(
        '%sphp -S %s:%d -t %s %s',
        $envVars,
        $host,
        $port,
        __DIR__,
        escapeshellarg(__DIR__ . '/src/WebApp/index.php')
    );

    passthru($command);
}


?>