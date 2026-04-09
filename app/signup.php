<?php
require 'auth.php';
require 'database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /inscription');
    exit();
}

$nom = trim($_POST['nom_complet'] ?? '');
$email = trim($_POST['email'] ?? '');
$userPassword = $_POST['password'] ?? '';
$passwordConfirm = $_POST['password_confirm'] ?? '';

if ($nom === '' || $email === '' || $userPassword === '') {
    header('Location: /inscription?error=missing_fields');
    exit();
}

if ($userPassword !== $passwordConfirm) {
    header('Location: /inscription?error=password_mismatch');
    exit();
}

$conn = db_connect_mysqli();

$check = $conn->prepare('SELECT id FROM Utilisateur WHERE email = ? LIMIT 1');
$check->bind_param('s', $email);
$check->execute();
if ($check->get_result()->num_rows > 0) {
    header('Location: /inscription?error=email_exists');
    exit();
}

$hash = password_hash($userPassword, PASSWORD_DEFAULT);
$stmt = $conn->prepare('INSERT INTO Utilisateur (nom_complet, email, mot_de_passe) VALUES (?, ?, ?)');
$stmt->bind_param('sss', $nom, $email, $hash);

if (!$stmt->execute()) {
    die("Erreur lors de l'inscription : " . $conn->error);
}

$userId = $stmt->insert_id;
$initSolde = $conn->prepare('INSERT INTO Solde (user_id, solde) VALUES (?, 0)');
$initSolde->bind_param('i', $userId);
$initSolde->execute();

$_SESSION['user'] = [
    'id' => (int) $userId,
    'nom_complet' => $nom,
    'email' => $email,
];

header('Location: /dashboard');
exit();
