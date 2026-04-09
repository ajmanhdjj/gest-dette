<?php

$servername = "mysql-675a44e-ajmanhdj.h.aivencloud.com";
$username = "avnadmin";
$password = "AVNS_nFUFjrCHKteVKGgRVRn";
$dbname = "defaultdb";
$port = 17559;

$conn = mysqli_init();

mysqli_ssl_set($conn, NULL, NULL, "../database/ca.pem", NULL, NULL);

mysqli_real_connect(
    $conn,
    $servername,
    $username,
    $password,
    $dbname,
    $port,
    NULL,
    MYSQLI_CLIENT_SSL
);

if (!$conn) {
    die("Connexion échouée : " . mysqli_connect_error());
}

echo "Connexion réussie";

?>