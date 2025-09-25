<?php


//Database Settings
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "db_gest_dette";

$connection=mysqli_connect($servername, $username, $password, $dbname);
if (mysqli_connect_errno()) {
    die("Error connecting to database");
}

?>


<?php
// Informations d'identification
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'root');
define('DB_NAME', 'db_gest_dette');
 
// Connexion à la base de données MySQL 
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
// Vérifier la connexion
if($conn === false){
    die("ERREUR : Impossible de se connecter. " . mysqli_connect_error());
}
?>

