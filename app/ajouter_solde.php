<?php
require 'auth.php';
require_auth();
$authUser = current_user();
$userId = (int) $authUser['id'];

if (isset($_POST['ajouter_solde'])) {
    $montant_ajoute = floatval($_POST['montant_creance'] ?? 0);
    require 'database.php';

    $conn = db_connect_mysqli();

    $stmtSolde = $conn->prepare("SELECT solde FROM Solde WHERE user_id = ? LIMIT 1");
    $stmtSolde->bind_param('i', $userId);
    $stmtSolde->execute();
    $result_solde = $stmtSolde->get_result();

    if ($result_solde->num_rows === 0) {
        $init = $conn->prepare("INSERT INTO Solde (user_id, solde) VALUES (?, 0)");
        $init->bind_param('i', $userId);
        $init->execute();
        $ancien_solde_banque = 0;
    } else {
        $ancien_solde_banque = (float) $result_solde->fetch_assoc()['solde'];
    }

    $nouveau_solde_banque = $ancien_solde_banque + $montant_ajoute;
    $stmtUpdate = $conn->prepare("UPDATE Solde SET solde = ? WHERE user_id = ?");
    $stmtUpdate->bind_param('di', $nouveau_solde_banque, $userId);
    if (!$stmtUpdate->execute()) {
        die("Erreur lors de la mise à jour du solde de la banque : " . $conn->error);
    }

    $type_transaction = "Ajout de solde";
    $sql_insert_transaction = $conn->prepare("INSERT INTO Transaction (user_id, nom_prenom, montant_trans, ancien_solde, nouveau_solde, type) VALUES (?, 'MOI', ?, ?, ?, ?)");
    $sql_insert_transaction->bind_param('iddds', $userId, $montant_ajoute, $ancien_solde_banque, $nouveau_solde_banque, $type_transaction);
    if ($sql_insert_transaction->execute()) {
        header('Location: ../solde.php');
        exit();
    }

    echo "Erreur lors de l'enregistrement de la transaction : " . $conn->error;
    $conn->close();
}
