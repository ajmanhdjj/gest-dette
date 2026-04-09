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

$servername = getenv('DB_HOST') ?: 'localhost';
$username = getenv('DB_USER') ?: 'root';
$dbpassword = getenv('DB_PASSWORD') !== false ? getenv('DB_PASSWORD') : 'root';
$password = $dbpassword;
$dbname = getenv('DB_NAME') ?: 'db_gest_dette';
$port = (int) (getenv('DB_PORT') ?: 3306);
$db_charset = getenv('DB_CHARSET') ?: 'utf8mb4';
$db_use_ssl = filter_var(getenv('DB_USE_SSL') ?: 'false', FILTER_VALIDATE_BOOLEAN);
$db_ssl_ca = getenv('DB_SSL_CA') ?: (__DIR__ . '/../database/ca.pem');

if (!function_exists('db_connect_mysqli')) {
    function db_connect_mysqli(): mysqli
    {
        global $servername, $username, $dbpassword, $dbname, $port, $db_charset, $db_use_ssl, $db_ssl_ca;

        $conn = mysqli_init();
        if ($conn === false) {
            throw new RuntimeException('Impossible d\'initialiser la connexion MySQLi.');
        }

        $flags = 0;
        if ($db_use_ssl) {
            mysqli_ssl_set($conn, null, null, $db_ssl_ca, null, null);
            $flags = MYSQLI_CLIENT_SSL;
        }

        $connected = mysqli_real_connect($conn, $servername, $username, $dbpassword, $dbname, $port, null, $flags);
        if (!$connected) {
            throw new RuntimeException('Connexion échouée : ' . mysqli_connect_error());
        }

        mysqli_set_charset($conn, $db_charset);

        return $conn;
    }
}

if (!function_exists('db_connect_pdo')) {
    function db_connect_pdo(): PDO
    {
        global $servername, $username, $dbpassword, $dbname, $port, $db_charset, $db_use_ssl, $db_ssl_ca;

        $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=%s', $servername, $port, $dbname, $db_charset);
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];

        if ($db_use_ssl) {
            $options[PDO::MYSQL_ATTR_SSL_CA] = $db_ssl_ca;
        }

        return new PDO($dsn, $username, $dbpassword, $options);
    }
}
