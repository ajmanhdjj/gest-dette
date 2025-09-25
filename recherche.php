<?php
// Connectez-vous à la base de données (remplacez les valeurs avec vos informations de connexion)
require 'app/database.php';

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion à la base de données
if ($conn->connect_error) {
    die("La connexion à la base de données a échoué : " . $conn->connect_error);
}

if (isset($_POST['search_query'])) {
    $search_query = $_POST['search_query'];

    if ($_POST['type_recherche'] === 'creance') {
        // Requête SQL pour rechercher les créances en fonction de la valeur saisie
        $sql_search_creances = "SELECT * FROM Creance WHERE nom_prenom LIKE '%$search_query%' ORDER BY id DESC";

        // Exécuter la requête SQL
        $result_creances = $conn->query($sql_search_creances);
    } elseif ($_POST['type_recherche'] === 'transaction') {
        // Requête SQL pour rechercher les transactions en fonction de la valeur saisie
        $sql_search_transactions = "SELECT * FROM Transaction WHERE nom_prenom LIKE '%$search_query%' OR type LIKE '%$search_query%' ORDER BY id DESC";

        // Exécuter la requête SQL
        $result_transactions = $conn->query($sql_search_transactions);
    }
}

?>

<!DOCTYPE html>
<html lang="fr">

<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Recherche</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.png">
    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="dashboard">

<div id="preloader">
    <i>.</i>
    <i>.</i>
    <i>.</i>
</div>

<div id="main-wrapper">


    <div class="header">
    <div class="container">
       <div class="row">
          <div class="col-xxl-12">
             <div class="header-content">
                <div class="header-left">
                   <div class="brand-logo"><a class="mini-logo" href="index.php"><img src="images/logoi.png" alt="" width="40"></a></div>
                   <div class="search">
                        <form action="recherche.php" method="POST">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search_query" placeholder="Recherche" value="<?php echo $_POST['search_query'] ?>">
                                <select class="form-control" name="type_recherche">
                                    <option value="creance" <?php if ($_POST['type_recherche'] === 'creance') echo 'selected'; ?>>CRÉANCE</option>
                                    <option value="transaction" <?php if ($_POST['type_recherche'] === 'transaction') echo 'selected'; ?>>TRANSACTION</option>
                                </select>
                                <button type="submit" class="input-group-text"><i class="ri-search-line"></i></button>
                            </div>
                        </form>
                   </div>
                </div>
                <div class="header-right">
                   <div class="dark-light-toggle"><span class="dark"><i class="ri-moon-line"></i></span><span class="light"><i class="ri-sun-line"></i></span></div>
                   <div class="dropdown profile_log dropdown">
                      <div data-toggle="dropdown" aria-haspopup="true" class="" aria-expanded="false">
                         <div class="user icon-menu active"><span><i class="ri-user-line"></i></span></div>
                      </div>
                      <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu dropdown-menu-right">
                         <div class="user-email">
                            <div class="user">
                               <span class="thumb"><img src="images/profile/3.png" alt=""></span>
                               <div class="user-info">
                                  <h5>Ajman hdj</h5>
                                  <span>a.hadjiboudine2016@gmail.com</span>
                               </div>
                            </div>
                         </div>
                         <a class="dropdown-item" href="profile.php"><span><i class="ri-user-line"></i></span>Profile</a>
                         <a class="dropdown-item logout" href="signin.php"><i class="ri-logout-circle-line"></i>Logout</a>
                      </div>
                   </div>
                </div>
             </div>
          </div>
       </div>
    </div>
 </div>

    <div class="sidebar">
    <div class="brand-logo"><a class="full-logo" href="index.php"><img src="images/logoi.png" alt="" width="30"></a></div>
    <div class="menu">
        <ul>
            <li><a href="index.php">
                    <span><i class="ri-home-5-line"></i></span>
                    <span class="nav-text">Accueil</span>
                </a>
            </li>
            <li><a href="solde.php">
                    <span><i class="ri-wallet-line"></i></span>
                    <span class="nav-text">Solde</span>
                </a>
            </li>
            <li><a href="creance.php">
                    <span><i class="ri-secure-payment-line"></i></span>
                    <span class="nav-text">Créance</span>
                </a>
            </li>
        </ul>
    </div>
</div>

    <div class="content-body">
        <div class="container">
            <div class="page-title">
                <div class="row align-items-center justify-content-between">
                    <div class="col-xl-4">
                        <div class="page-title-content">
                            <h3>Recherche</h3>
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="breadcrumbs"><a href="index.php">Accueil </a><span><i
                                    class="ri-arrow-right-s-line"></i></span><a href="recherche.php">Recherche</a></div>
                    </div>
                </div>
            </div>
            <div class="row">

                <?php
                if (isset($_POST['search_query'])) {
                    if ($_POST['type_recherche'] === 'creance') {
                        if ($result_creances->num_rows > 0) {
                            // Afficher les résultats de la recherche de créances
                            echo '<div class="col-xl-12">';
                            echo '<div class="card">';
                            echo '<div class="card-header flex-row">';
                            echo '<h4 class="card-title">Recherche créance : ' . $_POST['search_query'] . '</h4>';
                            echo '<a class="btn btn-primary" href="creer-creance.php"><span><i class="bi bi-plus"></i></span></a>';
                            echo '</div>';
                            echo '<div class="card-body">';
                            echo '<div class="invoice-table">';
                            echo '<div class="table-responsive">';
                            echo '<table class="table">';
                            echo '<thead>';
                            echo '<tr>';
                            echo '<th>Nom & prénom</th>';
                            echo '<th>Montant</th>';
                            echo '<th>Date d\'emprunt</th>';
                            echo '<th>mtn payé</th>';
                            echo '<th>mtn restant</th>';
                            echo '<th>Date de remboursement</th>';
                            echo '<th>Statut</th>';
                            echo '<th>Action</th>';
                            echo '</tr>';
                            echo '</thead>';
                            echo '<tbody>';

                            while ($creance = $result_creances->fetch_assoc()) {
                            $creance_id = $creance['id'];
                            $nom_prenom = $creance['nom_prenom'];
                            $montant_creance = $creance['montant_creance'];
                            $date_emprunt = $creance['date_emprunt'];
                            $ancien_solde = $creance['ancien_solde'];
                            $nouveau_solde = $creance['nouveau_solde'];
                            $date_remboursement = $creance['date_remboursement'];
                            $statut = $creance['statut'];

                            echo '<tr>';
                            echo '<td>' . $nom_prenom . '</td>';
                            echo '<td>' . $montant_creance . '</td>';
                            echo '<td>' . $date_emprunt . '</td>';
                            echo '<td>' . $ancien_solde . '</td>';
                            echo '<td>' . $nouveau_solde . '</td>';
                            echo '<td>' . $date_remboursement . '</td>';
                            echo '<td><span class="badge px-3 py-2 ' . ($statut === 'en cours' ? 'bg-warning' : 'bg-success') . '">' . $statut . '</span></td>';
                            echo '<td>';

                            if ($statut === 'en cours') {
                                echo '<a class="btn btn-outline-primary" href="rembourser_creance.php?id=' . $creance_id . '"><span>REMBOURSER</span></a>';
                            }

                            echo '</td>';
                            echo '</tr>';
                        }


                            echo '</tbody>';
                            echo '</table>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                        } else {
                            echo "Aucun résultat trouvé pour les créances.";
                        }
                    } elseif ($_POST['type_recherche'] === 'transaction') {
                        if ($result_transactions->num_rows > 0) {
                            // Afficher les résultats de la recherche de transactions
                            echo '<div class="col-xl-12">';
                            echo '<div class="card">';
                            echo '<div class="card-header flex-row">';
                            echo '<h4 class="card-title">Recherche transaction : ' . $_POST['search_query'] . '</h4>';
                            echo '</div>';
                            echo '<div class="card-body">';
                            echo '<div class="invoice-table">';
                            echo '<div class="table-responsive">';
                            echo '<table class="table">';
                            echo '<thead>';
                            echo '<tr>';
                            echo '<th>Nom & prénom</th>';
                            echo '<th>Montant</th>';
                            echo '<th>Ancien solde</th>';
                            echo '<th>Nouveau solde</th>';
                            echo '<th>Type</th>';
                            echo '<th>Date de transaction</th>';
                            echo '</tr>';
                            echo '</thead>';
                            echo '<tbody>';

                            while ($transaction = $result_transactions->fetch_assoc()) {
                                $transaction_id = $transaction['id'];
                                $nom_prenom = $transaction['nom_prenom'];
                                $montant_trans = $transaction['montant_trans'];
                                $ancien_solde = $transaction['ancien_solde'];
                                $nouveau_solde = $transaction['nouveau_solde'];
                                $type_transaction = $transaction['type'];
                                $date_transaction = $transaction['date_transaction'];

                                echo '<tr>';
                                echo '<td>' . $nom_prenom . '</td>';
                                echo '<td>' . ($type_transaction == 'Créance remboursée' || $type_transaction == 'Ajout de solde' ? '+' : '-') . $montant_trans . '</td>';
                                echo '<td>' . $ancien_solde . '</td>';
                                echo '<td>' . $nouveau_solde . '</td>';
                                echo '<td><span class="badge px-3 py-2 ' . ($type_transaction === 'Créance créée' ? 'bg-warning' : 'bg-success') . '">' . $type_transaction . '</span></td>';
                                echo '<td>' . $date_transaction . '</td>';
                                echo '</tr>';
                            }

                            echo '</tbody>';
                            echo '</table>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                        } else {
                            echo "Aucun résultat trouvé pour les transactions.";
                        }
                    }
                } else {
                    // Afficher le formulaire de recherche vide
                    echo '<div class="col-xl-12">';
                    echo '<div class="card">';
                    echo '<div class="card-header flex-row">';
                    echo '<h4 class="card-title">Recherche</h4>';
                    echo '</div>';
                    echo '<div class="card-body">';
                    echo '<form action="#" method="post">';
                    echo '<div class="input-group">';
                    echo '<input type="text" class="form-control" name="search_query" placeholder="Recherche">';
                    echo '<select class="form-control" name="type_recherche">';
                    echo '<option value="creance">CRÉANCE</option>';
                    echo '<option value="transaction">TRANSACTION</option>';
                    echo '</select>';
                    echo '<button type="submit" class="input-group-text"><i class="ri-search-line"></i></button>';
                    echo '</div>';
                    echo '</form>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
                ?>



            </div>
        </div>
    </div>



</div>




<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>


<script src="vendor/chartjs/chartjs.js"></script>



<script src="js/plugins/chartjs-line-init.js"></script>




<script src="js/plugins/chartjs-donut.js"></script>






<script src="vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="js/plugins/perfect-scrollbar-init.js"></script>



<script src="vendor/circle-progress/circle-progress.min.js"></script>
<script src="js/plugins/circle-progress-init.js"></script>







<script src="js/scripts.js"></script>


</body>

</html>