<?php
require 'auth.php';
require_auth();
require 'database.php';

$userId = (int) $_SESSION['user']['id'];
$nom = trim($_POST['nom_complet'] ?? '');
$email = trim($_POST['email'] ?? '');

if ($nom === '' || $email === '') {
    header('Location: ../profile.php?error=missing_fields');
    exit();
}

$conn = new mysqli($servername, $username, $dbpassword ?? $password, $dbname);
if ($conn->connect_error) {
    die('Connexion échouée : ' . $conn->connect_error);
}

$stmt = $conn->prepare('UPDATE Utilisateur SET nom_complet = ?, email = ? WHERE id = ?');
$stmt->bind_param('ssi', $nom, $email, $userId);
if (!$stmt->execute()) {
    header('Location: ../profile.php?error=update_failed');
    exit();
}

$_SESSION['user']['nom_complet'] = $nom;
$_SESSION['user']['email'] = $email;

header('Location: ../profile.php?success=1');
exit();
