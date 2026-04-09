<?php
require 'app/auth.php';
require_auth();
$authUser = current_user();
$userId = (int) $authUser['id'];
require 'app/database.php';

$pdo = db_connect_pdo();
$stmt = $pdo->prepare("SELECT * FROM Transaction WHERE user_id = :user_id ORDER BY id DESC LIMIT 4");
$stmt->execute(['user_id' => $userId]);
$tr = $stmt->fetchAll(PDO::FETCH_ASSOC);

$conn = db_connect_mysqli();

$stmtSolde = $conn->prepare("SELECT solde FROM Solde WHERE user_id = ? LIMIT 1");
$stmtSolde->bind_param('i', $userId);
$stmtSolde->execute();
$result_solde = $stmtSolde->get_result();
$solde = 0;
if ($result_solde->num_rows > 0) {
    $row_solde = $result_solde->fetch_assoc();
    $solde = $row_solde['solde'];
}

$stmtCreanceTotal = $conn->prepare("SELECT COALESCE(SUM(montant_creance), 0) AS montant_total_creances_en_cours FROM Creance WHERE statut = 'en cours' AND user_id = ?");
$stmtCreanceTotal->bind_param('i', $userId);
$stmtCreanceTotal->execute();
$montant_total_creances_en_cours = $stmtCreanceTotal->get_result()->fetch_assoc()['montant_total_creances_en_cours'] ?? 0;

$stmtCreanceCount = $conn->prepare("SELECT COUNT(*) AS nb_creances_en_cours FROM Creance WHERE statut = 'en cours' AND user_id = ?");
$stmtCreanceCount->bind_param('i', $userId);
$stmtCreanceCount->execute();
$nb_creances_en_cours = $stmtCreanceCount->get_result()->fetch_assoc()['nb_creances_en_cours'] ?? 0;

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">

<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Acceuil</title>
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
                                <input type="text" class="form-control" name="search_query" placeholder="Recherche">
                                <select class="form-control" name="type_recherche">
                                    <option value="creance">CRÉANCE</option>
                                    <option value="transaction">TRANSACTION</option>
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
                                  <h5><?php echo htmlspecialchars($authUser['nom_complet']); ?></h5>
                                  <span><?php echo htmlspecialchars($authUser['email']); ?></span>
                               </div>
                            </div>
                         </div>
                         <a class="dropdown-item" href="profile.php"><span><i class="ri-user-line"></i></span>Profile</a>
                         <a class="dropdown-item logout" href="app/logout.php"><i class="ri-logout-circle-line"></i>Logout</a>
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
                            <h3>Tableau de bord</h3>
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="breadcrumbs"><a href="index.php">Accueil </a><span><i
                                    class="ri-arrow-right-s-line"></i></span><a href="index.php">Tableau de bord</a></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-6 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Stats</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                    <div class="stat-widget d-flex align-items-center">
                                        <div class="widget-icon me-3 bg-primary"><span>
                                            <i class="ri-wallet-line"></i></span></div>
                                        <div class="widget-content">
                                            <h3>
                                                <span id="soldeValue"><?php echo $solde; ?></span>
                                                <span id="maskedSolde" style="display: none;">**********</span>
                                                <a id="toggleButton" href="javascript:void(0);"><i class="bi bi-eye-slash-fill"></i></a>
                                            </h3>
                                            <p>Solde</p>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                                    <div class="stat-widget d-flex align-items-center">
                                        <div class="widget-icon me-3 bg-success"><span>
                                            <i class="ri-wallet-3-line"></i></span></div>
                                        <div class="widget-content">
                                            <h3>
                                                <h3>
                                                    <span id="totalCreancesValue"><?php echo $montant_total_creances_en_cours; ?></span>
                                                    <span id="maskedTotalCreances" style="display: none;">**********</span>
                                                    <a id="toggleTotalCreancesButton" href="javascript:void(0);"><i class="bi bi-eye-slash-fill"></i></a>
                                                </h3>
                                            </h3>
                                            <p>Total créance</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                                    <div class="stat-widget d-flex align-items-center">
                                        <div class="widget-icon me-3 bg-secondary"><span>
                                            <i class="ri-user-line"></i></span></div>
                                        <div class="widget-content">
                                            <h3><?php echo $nb_creances_en_cours; ?></h3>
                                            <p>Personne créance</p>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Historique des transactions</h4>
                            <a href="solde.php">Voir plus</a>
                        </div>
                        <div class="card-body">
                            <div class="invoice-content budget-content">
                                <ul>
                                    <?php foreach($tr as $transaction):; ?>
                                    <li class="d-flex justify-content-between active">
                                        <div class="d-flex align-items-center">
                                            <div class="invoice-info">
                                                <h5 class="mb-0"><?php echo $transaction['nom_prenom'] ?></h5>
                                                <p><?php echo $transaction['date_transaction'] ?></p>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <h5 class="mb-2"><?php echo ($transaction['type'] === 'Créance créée' ? '-' : '+') . $transaction['montant_trans']; ?></h5>
                                            <span class="text-white <?php echo ($transaction['type'] === 'Créance créée' ? 'bg-warning' : 'bg-success'); ?>"><?php echo $transaction['type'] ?></span>

                                        </div>
                                    </li>
                                    <?php endforeach;?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
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

<script src="js/script-eye.js"></script>



</body>

</html>