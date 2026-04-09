<?php

if (!function_exists('load_env_file')) {
    function load_env_file(string $path): void
    {
        if (!is_readable($path)) {
            return;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines === false) {
            return;
        }

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }

            [$key, $value] = array_pad(explode('=', $line, 2), 2, '');
            $key = trim($key);
            $value = trim($value);

            if ($key === '') {
                continue;
            }

            if ((str_starts_with($value, '"') && str_ends_with($value, '"')) || (str_starts_with($value, "'") && str_ends_with($value, "'"))) {
                $value = substr($value, 1, -1);
            }

            if (getenv($key) === false) {
                putenv($key . '=' . $value);
                $_ENV[$key] = $value;
                $_SERVER[$key] = $value;
            }
        }
    }
}

if (!defined('DB_CONFIG_LOADED')) {
    define('DB_CONFIG_LOADED', true);
    load_env_file(__DIR__ . '/../.env');
}

$servername = getenv('DB_HOST') ?: (getenv('AIVEN_HOST') ?: 'localhost');
$username = getenv('DB_USER') ?: (getenv('AIVEN_USER') ?: 'root');
$dbpassword = getenv('DB_PASSWORD');
if ($dbpassword === false) {
    $dbpassword = getenv('AIVEN_PASSWORD');
}
if ($dbpassword === false) {
    $dbpassword = 'root';
}
$password = $dbpassword;
$dbname = getenv('DB_NAME') ?: (getenv('AIVEN_DATABASE') ?: 'db_gest_dette');
$port = (int) (getenv('DB_PORT') ?: (getenv('AIVEN_PORT') ?: 3306));
$db_charset = getenv('DB_CHARSET') ?: 'utf8mb4';

$db_use_ssl_env = getenv('DB_USE_SSL');
if ($db_use_ssl_env === false) {
    $db_use_ssl = str_contains($servername, 'aivencloud.com');
} else {
    $db_use_ssl = filter_var($db_use_ssl_env, FILTER_VALIDATE_BOOLEAN);
}

$db_ssl_ca = getenv('DB_SSL_CA') ?: (__DIR__ . '/../database/ca.pem');

if (!function_exists('db_connection_attempts')) {
    /**
     * @return array<int, array{host: string, port: int}>
     */
    function db_connection_attempts(string $host, int $port, bool $useSsl): array
    {
        $attempts = [['host' => $host, 'port' => $port]];

        // Fallback utile pour MAMP local si DB_HOST/DB_PORT ne sont pas explicitement définis.
        $isDefaultLocal = getenv('DB_HOST') === false && getenv('AIVEN_HOST') === false && getenv('DB_PORT') === false && getenv('AIVEN_PORT') === false;
        if ($isDefaultLocal && !$useSsl && ($host === 'localhost' || $host === 'localhost')) {
            $attempts[] = ['host' => 'localhost', 'port' => 8889];
            $attempts[] = ['host' => 'localhost', 'port' => 8889];
            $attempts[] = ['host' => 'localhost', 'port' => 3306];
        }

        return $attempts;
    }
}

if (!function_exists('db_connect_mysqli')) {
    function db_connect_mysqli(): mysqli
    {
        global $servername, $username, $dbpassword, $dbname, $port, $db_charset, $db_use_ssl, $db_ssl_ca;

        $lastError = '';
        foreach (db_connection_attempts($servername, $port, $db_use_ssl) as $target) {
            $conn = mysqli_init();
            if ($conn === false) {
                throw new RuntimeException('Impossible d\'initialiser la connexion MySQLi.');
            }

            $flags = 0;
            if ($db_use_ssl) {
                mysqli_ssl_set($conn, null, null, $db_ssl_ca, null, null);
                $flags = MYSQLI_CLIENT_SSL;
            }

            $connected = @mysqli_real_connect($conn, $target['host'], $username, $dbpassword, $dbname, $target['port'], null, $flags);
            if ($connected) {
                mysqli_set_charset($conn, $db_charset);
                return $conn;
            }

            $lastError = mysqli_connect_error();
            mysqli_close($conn);
        }

        throw new RuntimeException('Connexion échouée : ' . $lastError);
    }
}

if (!function_exists('db_connect_pdo')) {
    function db_connect_pdo(): PDO
    {
        global $servername, $username, $dbpassword, $dbname, $port, $db_charset, $db_use_ssl, $db_ssl_ca;

        $lastException = null;
        foreach (db_connection_attempts($servername, $port, $db_use_ssl) as $target) {
            $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=%s', $target['host'], $target['port'], $dbname, $db_charset);
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ];

            if ($db_use_ssl) {
                $options[PDO::MYSQL_ATTR_SSL_CA] = $db_ssl_ca;
            }

            try {
                return new PDO($dsn, $username, $dbpassword, $options);
            } catch (PDOException $e) {
                $lastException = $e;
            }
        }

        throw $lastException ?? new PDOException('Connexion PDO échouée.');
    }
}
