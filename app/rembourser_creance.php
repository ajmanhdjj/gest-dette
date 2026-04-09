<?php
require 'auth.php';
require_auth();
$authUser = current_user();
$userId = (int) $authUser['id'];

if (!isset($_POST['remb_creance'])) {
    header('Location: ../creance.php');
    exit();
}

$creance_id = (int) ($_POST['remBcrea'] ?? 0);
$montant_rembourse = floatval($_POST['montant_creance'] ?? 0);

require 'database.php';
$conn = db_connect_mysqli();

$stmtSolde = $conn->prepare("SELECT solde FROM Solde WHERE user_id = ? LIMIT 1");
$stmtSolde->bind_param('i', $userId);
$stmtSolde->execute();
$soldeResult = $stmtSolde->get_result();
$ancien_solde_banque = $soldeResult->num_rows > 0 ? (float)$soldeResult->fetch_assoc()['solde'] : 0;

$sql_select_creance = $conn->prepare("SELECT * FROM Creance WHERE id = ? AND user_id = ?");
$sql_select_creance->bind_param('ii', $creance_id, $userId);
$sql_select_creance->execute();
$result = $sql_select_creance->get_result();

if ($result->num_rows === 0) {
    die('Créance introuvable dans la base de données.');
}

$creance = $result->fetch_assoc();
$ancien_solde_creance = (float) $creance['ancien_solde'];
$montant_creance = (float) $creance['montant_creance'];
$nom_prenom_creancier = $creance['nom_prenom'];

if ($montant_rembourse > $montant_creance) {
    die('Le montant remboursé ne peut pas être supérieur au montant de la créance.');
}

$mtn_creance_payer = $ancien_solde_creance + $montant_rembourse;
$mtn_creance_restant = max(0, $montant_creance - $mtn_creance_payer);
$statut_creance = $mtn_creance_restant <= 0 ? 'remboursé' : 'en cours';

$sql_update_creance = $conn->prepare("UPDATE Creance SET ancien_solde = ?, nouveau_solde = ?, statut = ? WHERE id = ? AND user_id = ?");
$sql_update_creance->bind_param('ddsii', $mtn_creance_payer, $mtn_creance_restant, $statut_creance, $creance_id, $userId);
if (!$sql_update_creance->execute()) {
    die("Erreur lors de la mise à jour de la créance : " . $conn->error);
}

$nouveau_solde_banque = $ancien_solde_banque + $montant_rembourse;
$stmtUpdateSolde = $conn->prepare("UPDATE Solde SET solde = ? WHERE user_id = ?");
$stmtUpdateSolde->bind_param('di', $nouveau_solde_banque, $userId);
$stmtUpdateSolde->execute();

$type_transaction = "Créance remboursée";
$sql_insert_transaction = $conn->prepare("INSERT INTO Transaction (user_id, nom_prenom, montant_trans, ancien_solde, nouveau_solde, type) VALUES (?, ?, ?, ?, ?, ?)");
$sql_insert_transaction->bind_param('isddds', $userId, $nom_prenom_creancier, $montant_rembourse, $ancien_solde_banque, $nouveau_solde_banque, $type_transaction);
if ($sql_insert_transaction->execute()) {
    header('Location: ../creance.php');
    exit();
}

echo "Erreur lors de l'enregistrement de la transaction : " . $conn->error;
$conn->close();
