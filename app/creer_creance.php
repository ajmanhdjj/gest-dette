<?php
// Vérifier si le formulaire de création de créance a été soumis
if (isset($_POST['creer_creance'])) {
    // Récupérer les données du formulaire
    $nom_prenom = $_POST['nom_prenom'];
    $date_remboursement = $_POST['date_remboursement'];
    $montant_creance = floatval($_POST['montant_creance']);

    // Connectez-vous à la base de données (remplacez les valeurs avec vos informations de connexion)
    require 'database.php';

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Vérifier la connexion à la base de données
    if ($conn->connect_error) {
        die("La connexion à la base de données a échoué : " . $conn->connect_error);
    }

    // Récupérer le solde de la banque avant la création de la créance depuis la table "Solde"
    $sql_select_solde = "SELECT solde FROM Solde";
    $result_solde = $conn->query($sql_select_solde);

    if ($result_solde->num_rows > 0) {
        $row_solde = $result_solde->fetch_assoc();
        $ancien_solde = $row_solde['solde'];

        // Mettre à jour le solde de la banque dans la table "Solde"
        $nouveau_solde = $ancien_solde - $montant_creance;
        $sql_update_solde = "UPDATE Solde SET solde = $nouveau_solde";
        if ($conn->query($sql_update_solde) !== TRUE) {
            echo "Erreur lors de la mise à jour du solde de la banque : " . $conn->error;
            $conn->close();
            exit();
        }

        // Enregistrement de la créance dans la table "Creance" avec le solde de la banque avant et après la création
        $sql_insert_creance = "INSERT INTO Creance (nom_prenom, montant_creance, nouveau_solde, date_remboursement, statut) VALUES ('$nom_prenom', $montant_creance, $montant_creance, '$date_remboursement', 'en cours')";
        if ($conn->query($sql_insert_creance) === TRUE) {
            // Enregistrement de la transaction dans la table "Transaction" avec l'ancien et le nouveau solde
            $type_transaction = "Créance créée";
            $sql_insert_transaction = "INSERT INTO Transaction (nom_prenom, montant_trans, ancien_solde, nouveau_solde, type) VALUES ('$nom_prenom', $montant_creance, $ancien_solde, $nouveau_solde, '$type_transaction')";
            if ($conn->query($sql_insert_transaction) === TRUE) {
                // Rediriger l'utilisateur vers la page d'affichage des créances après la création réussie
                header('Location: ../creance.php');
                exit();
            } else {
                echo "Erreur lors de l'enregistrement de la transaction : " . $conn->error;
            }
        } else {
            echo "Erreur lors de l'enregistrement de la créance : " . $conn->error;
        }
    } else {
        echo "Erreur : impossible de récupérer le solde de la banque.";
    }

    // Fermer la connexion à la base de données
    $conn->close();
}
?>
