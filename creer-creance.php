<!DOCTYPE html>
<html lang="fr">

<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Creer créance</title>
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
                            <h3>Créer créance</h3>
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="breadcrumbs"><a href="index.php">Accueil </a><span><i
                                    class="ri-arrow-right-s-line"></i></span><a href="creer-creance.php">Créer créance</a></div>
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
                            <form class="invoice-form" action="app/creer_creance.php" method="POST" enctype="multipart/form-data">
                                    <div class="row justify-content-between">
                                        <div class="col-xl-3">
                                            <div class="mb-3"><label class="form-label">Nom & prénom</label>
                                                <input type="text" name="nom_prenom" class="form-control" placeholder="Nom & prénom">
                                            </div>
                                        </div>
                                        <div class="col-xl-3">
                                            <div class="mb-3"><label class="form-label">Date de remboursement</label>
                                                <input type="date" name="date_remboursement" class="form-control" placeholder="date de remboursement">
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="mb-3 col-xl-12"><label class="form-label">Montant</label>
                                            <input type="text" name="montant_creance" class="form-control" placeholder="Montant">
                                        </div>
                                    </div>
                                    <div class="add-reset d-flex justify-content-between">
                                        <button class="btn btn-outline-primary">
                                            <span><i class="ri-close-line"></i></span>Annuler
                                        </button>
                                        <button class="btn btn-primary" name="creer_creance">
                                            <span><i class="bi bi-plus"></i></span>Créer
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