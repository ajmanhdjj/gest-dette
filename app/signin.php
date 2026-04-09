<?php
require 'auth.php';
require 'database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /connexion');
    exit();
}

$email = trim($_POST['email'] ?? '');
$userPassword = $_POST['password'] ?? '';

$conn = db_connect_mysqli();

$stmt = $conn->prepare('SELECT id, nom_complet, email, mot_de_passe FROM Utilisateur WHERE email = ? LIMIT 1');
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user || !password_verify($userPassword, $user['mot_de_passe'])) {
    header('Location: /connexion?error=invalid_credentials');
    exit();
}

$_SESSION['user'] = [
    'id' => (int) $user['id'],
    'nom_complet' => $user['nom_complet'],
    'email' => $user['email'],
];

header('Location: /dashboard');
exit();
