<?php
require 'auth.php';
require_auth();
$authUser = current_user();
$userId = (int) $authUser['id'];

if (isset($_POST['creer_creance'])) {
    $nom_prenom = trim($_POST['nom_prenom'] ?? '');
    $date_remboursement = $_POST['date_remboursement'] ?? null;
    $montant_creance = floatval($_POST['montant_creance'] ?? 0);

    require 'database.php';
    $conn = new mysqli($servername, $username, $dbpassword ?? $password, $dbname);
    if ($conn->connect_error) {
        die("La connexion à la base de données a échoué : " . $conn->connect_error);
    }

    $stmtSolde = $conn->prepare("SELECT solde FROM Solde WHERE user_id = ? LIMIT 1");
    $stmtSolde->bind_param('i', $userId);
    $stmtSolde->execute();
    $result_solde = $stmtSolde->get_result();

    if ($result_solde->num_rows === 0) {
        $init = $conn->prepare("INSERT INTO Solde (user_id, solde) VALUES (?, 0)");
        $init->bind_param('i', $userId);
        $init->execute();
        $ancien_solde = 0;
    } else {
        $ancien_solde = (float) $result_solde->fetch_assoc()['solde'];
    }

    $nouveau_solde = $ancien_solde - $montant_creance;
    $sql_update_solde = $conn->prepare("UPDATE Solde SET solde = ? WHERE user_id = ?");
    $sql_update_solde->bind_param('di', $nouveau_solde, $userId);
    if (!$sql_update_solde->execute()) {
        die("Erreur lors de la mise à jour du solde de la banque : " . $conn->error);
    }

    $insertCreance = $conn->prepare("INSERT INTO Creance (user_id, nom_prenom, montant_creance, nouveau_solde, date_remboursement, statut) VALUES (?, ?, ?, ?, ?, 'en cours')");
    $insertCreance->bind_param('isdds', $userId, $nom_prenom, $montant_creance, $montant_creance, $date_remboursement);

    if ($insertCreance->execute()) {
        $type_transaction = "Créance créée";
        $insertTrans = $conn->prepare("INSERT INTO Transaction (user_id, nom_prenom, montant_trans, ancien_solde, nouveau_solde, type) VALUES (?, ?, ?, ?, ?, ?)");
        $insertTrans->bind_param('isddds', $userId, $nom_prenom, $montant_creance, $ancien_solde, $nouveau_solde, $type_transaction);

        if ($insertTrans->execute()) {
            header('Location: ../creance.php');
            exit();
        }
        echo "Erreur lors de l'enregistrement de la transaction : " . $conn->error;
    } else {
        echo "Erreur lors de l'enregistrement de la créance : " . $conn->error;
    }

    $conn->close();
}
