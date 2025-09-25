<?php
// Vérifier si le formulaire d'ajout de solde a été soumis
if (isset($_POST['ajouter_solde'])) {
    // Récupérer le montant ajouté depuis le formulaire
    $montant_ajoute = floatval($_POST['montant_creance']);

    // Connectez-vous à la base de données (remplacez les valeurs avec vos informations de connexion)
    require 'database.php';

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Vérifier la connexion à la base de données
    if ($conn->connect_error) {
        die("La connexion à la base de données a échoué : " . $conn->connect_error);
    }

    // Récupérer le solde actuel de la banque depuis la table "Solde"
    $sql_select_solde = "SELECT solde FROM Solde";
    $result_solde = $conn->query($sql_select_solde);

    if ($result_solde->num_rows > 0) {
        $row_solde = $result_solde->fetch_assoc();
        $ancien_solde_banque = $row_solde['solde'];

        // Mettre à jour le solde de la banque dans la table "Solde"
        $nouveau_solde_banque = $ancien_solde_banque + $montant_ajoute;
        $sql_update_solde = "UPDATE Solde SET solde = $nouveau_solde_banque";
        if ($conn->query($sql_update_solde) !== TRUE) {
            echo "Erreur lors de la mise à jour du solde de la banque : " . $conn->error;
            $conn->close();
            exit();
        }

        // Enregistrement de la transaction dans la table "Transaction"
        $type_transaction = "Ajout de solde";
        $ancien_montant_banque = $ancien_solde_banque;
        $nouveau_montant_banque = $nouveau_solde_banque;
        $sql_insert_transaction = "INSERT INTO Transaction (nom_prenom, montant_trans, ancien_solde, nouveau_solde, type) VALUES ('MOI', $montant_ajoute, $ancien_montant_banque, $nouveau_montant_banque, '$type_transaction')";
        if ($conn->query($sql_insert_transaction) === TRUE) {
            // Rediriger l'utilisateur vers la page d'affichage des transactions après l'ajout de solde réussi
            header('Location: ../solde.php');
            exit();
        } else {
            echo "Erreur lors de l'enregistrement de la transaction : " . $conn->error;
        }
    } else {
        echo "Erreur : impossible de récupérer le solde de la banque.";
    }

    // Fermer la connexion à la base de données
    $conn->close();
}
?>
