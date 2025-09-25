<?php
// Vérifier si le formulaire de remboursement a été soumis
if (isset($_POST['remb_creance'])) {
    // Récupérer l'identifiant de la créance à rembourser depuis le formulaire
    $creance_id = $_POST['remBcrea'];

    // Récupérer le montant remboursé depuis le formulaire
    $montant_rembourse = floatval($_POST['montant_creance']);

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
        $ancien_solde_banque = $row_solde['solde'];

        // Récupérer les informations de la créance depuis la base de données
        $sql_select_creance = "SELECT * FROM Creance WHERE id = $creance_id";
        $result = $conn->query($sql_select_creance);

        if ($result->num_rows > 0) {
            $creance = $result->fetch_assoc();
            $ancien_solde = $creance['ancien_solde'];
            $nouveau_solde = $creance['nouveau_solde'];
            $montant_creance = $creance['montant_creance'];
            $nom_prenom_creancier = $creance['nom_prenom'];
            $statut_creance = $creance['statut'];

            // Vérifier si le montant remboursé est valide (inférieur ou égal au montant de la créance)
            if ($montant_rembourse <= $montant_creance) {
                // Mettre à jour les informations de la créance dans la table "Creance"
                $mtn_creance_payer = $ancien_solde + $montant_rembourse;
                $mtn_creance_restant = $mtn_creance_payer - $montant_creance;

                    if ($mtn_creance_payer = $montant_creance) {
                        $statut_creance = 'remboursé';
                    } elseif ($mtn_creance_payer > $montant_creance) {
                        $statut_creance = 'en cours';
                    }


                $sql_update_creance = "UPDATE Creance SET montant_creance = $montant_creance, ancien_solde = $mtn_creance_payer, nouveau_solde = $mtn_creance_restant, statut = '$statut_creance' WHERE id = $creance_id";
                if ($conn->query($sql_update_creance) === TRUE) {
                    // Mettre à jour le solde de la banque dans la table "Solde"
                    $sql_update_solde = "UPDATE Solde SET solde = solde + $montant_rembourse";
                    if ($conn->query($sql_update_solde) === TRUE) {
                        // Enregistrement de la transaction dans la table "Transaction"
                        $nouveau_solde_banque = $ancien_solde_banque + $montant_rembourse;
                        $type_transaction = "Créance remboursée";

                        $sql_insert_transaction = "INSERT INTO Transaction (nom_prenom, montant_trans, ancien_solde, nouveau_solde, type) VALUES ('$nom_prenom_creancier', $montant_rembourse, $ancien_solde_banque, $nouveau_solde_banque, '$type_transaction')";
                        if ($conn->query($sql_insert_transaction) === TRUE) {
                            // Rediriger l'utilisateur vers la page d'affichage des créances après le remboursement réussi
                            header('Location: ../creance.php');
                            exit();
                        } else {
                            echo "Erreur lors de l'enregistrement de la transaction : " . $conn->error;
                        }
                    } else {
                        echo "Erreur lors de la mise à jour du solde du créancier : " . $conn->error;
                    }
                } else {
                    echo "Erreur lors de la mise à jour de la créance : " . $conn->error;
                }
            } else {
                echo "Le montant remboursé ne peut pas être supérieur au montant de la créance.";
            }
        } else {
            echo "Créance introuvable dans la base de données.";
        }

        // Fermer la connexion à la base de données
        $conn->close();
    } else {
        echo "Erreur lors de la récupération du solde de la banque.";
    }
} else {
    // Rediriger l'utilisateur vers la page d'affichage des créances si le formulaire n'a pas été soumis
    header('Location: ../rembourser_creance.php?id=' . $creance_id .'');
    exit();
}
?>
