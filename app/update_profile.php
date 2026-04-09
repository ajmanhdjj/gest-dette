<?php
require 'auth.php';
require_auth();
require 'database.php';

$userId = (int) $_SESSION['user']['id'];
$nom = trim($_POST['nom_complet'] ?? '');
$email = trim($_POST['email'] ?? '');

if ($nom === '' || $email === '') {
    header('Location: /profil?error=missing_fields');
    exit();
}

$conn = db_connect_mysqli();

$stmt = $conn->prepare('UPDATE Utilisateur SET nom_complet = ?, email = ? WHERE id = ?');
$stmt->bind_param('ssi', $nom, $email, $userId);
if (!$stmt->execute()) {
    header('Location: /profil?error=update_failed');
    exit();
}

$_SESSION['user']['nom_complet'] = $nom;
$_SESSION['user']['email'] = $email;

header('Location: /profil?success=1');
exit();
