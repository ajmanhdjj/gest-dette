<?php
require 'app/auth.php';
require_auth();
$authUser = current_user();
$userId = (int) $authUser['id'];
require 'app/database.php';

$conn = db_connect_pdo();
$stmt = $conn->prepare("SELECT * FROM Creance WHERE id = :id AND user_id = :user_id");
$stmt->bindValue(':id', (int)($_GET['id'] ?? 0), PDO::PARAM_INT);
$stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
$stmt->execute();
$creance = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$creance) {
    header('Location: /creances');
    exit();
}
?>


<!DOCTYPE html>
<html lang="fr">

<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rembourser creance</title>
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
                   <div class="brand-logo"><a class="mini-logo" href="/dashboard"><img src="images/logoi.png" alt="" width="40"></a></div>
                   <div class="search">
                        <form action="/recherche" method="POST">
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
                      <div data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                         <div class="user icon-menu active"><span><i class="ri-user-line"></i></span></div>
                      </div>
                      <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu dropdown-menu-end">
                         <div class="user-email">
                            <div class="user">
                               <span class="thumb"><img src="images/profile/3.png" alt=""></span>
                               <div class="user-info">
                                  <h5><?php echo htmlspecialchars($authUser['nom_complet']); ?></h5>
                                  <span><?php echo htmlspecialchars($authUser['email']); ?></span>
                               </div>
                            </div>
                         </div>
                         <a class="dropdown-item" href="/profil"><span><i class="ri-user-line"></i></span>Profile</a>
                         <a class="dropdown-item logout" href="/deconnexion"><i class="ri-logout-circle-line"></i>Logout</a>
                      </div>
                   </div>
                </div>
             </div>
          </div>
       </div>
    </div>
 </div>

    <div class="sidebar">
    <div class="brand-logo"><a class="full-logo" href="/dashboard"><img src="images/logoi.png" alt="" width="30"></a></div>
    <div class="menu">
        <ul>
            <li><a href="/dashboard">
                    <span><i class="ri-home-5-line"></i></span>
                    <span class="nav-text">Accueil</span>
                </a>
            </li>
            <li><a href="/solde">
                    <span><i class="ri-wallet-line"></i></span>
                    <span class="nav-text">Solde</span>
                </a>
            </li>
            <li><a href="/creances">
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
                            <h3>Créer créance</h3>
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="breadcrumbs"><a href="/dashboard">Accueil </a><span><i
                                    class="ri-arrow-right-s-line"></i></span><a href="/creances/nouvelle">Créer créance</a></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Créer créance </h4>
                        </div>
                        <div class="card-body">
                            <form class="invoice-form" action="/creances/rembourser/action" method="POST" enctype="multipart/form-data">
                                    <div class="row justify-content-between">
                                        <div class="col-xl-3">
                                            <div class="mb-3"><label class="form-label">Nom & prénom</label>
                                                <input type="text" name="nom_prenom" class="form-control" placeholder="Nom & prénom" value="<?php echo $creance['nom_prenom'] ?>" disabled>
                                            </div>
                                        </div>
                                        <div class="col-xl-3">
                                            <div class="mb-3"><label class="form-label">Montant due</label>
                                                <input type="text" name="montant_creance" class="form-control" placeholder="Nom & prénom" value="<?php echo $creance['montant_creance'] ?>" disabled>
                                            </div>
                                        </div>
                                        <input type="hidden" name="remBcrea" value="<?php echo $creance['id'] ?>">
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="mb-3 col-xl-12"><label class="form-label">Montant de remboursement</label>
                                            <input type="text" name="montant_creance" class="form-control" placeholder="Montant">
                                        </div>
                                    </div>
                                    <div class="add-reset d-flex justify-content-between">
                                        <button class="btn btn-outline-primary">
                                            <span><i class="ri-close-line"></i></span>Annuler
                                        </button>
                                        <button class="btn btn-primary" name="remb_creance">
                                            <span><i class="bi bi-plus"></i></span>Rembourser
                                        </button>
                                    </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



</div>




<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>



<script src="js/plugins/chartjs-line-init.js"></script>




<script src="js/plugins/chartjs-donut.js"></script>






<script src="https://cdn.jsdelivr.net/npm/perfect-scrollbar@1.5.6/dist/perfect-scrollbar.min.js"></script>
<script src="js/plugins/perfect-scrollbar-init.js"></script>



<script src="https://cdn.jsdelivr.net/npm/jquery-circle-progress@1.2.2/dist/circle-progress.min.js"></script>
<script src="js/plugins/circle-progress-init.js"></script>







<script src="js/scripts.js"></script>


</body>

</html>