<?php
require 'app/auth.php';
require_auth();
$authUser = current_user();
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mon profil</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body class="dashboard">
<div class="authincation section-padding">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-xl-6 col-md-8">
        <div class="card">
          <div class="card-body">
            <h4 class="mb-4">Mon profil</h4>
            <?php if (isset($_GET['success'])): ?><div class="alert alert-success">Profil mis à jour.</div><?php endif; ?>
            <form action="/profil/update" method="POST">
              <div class="mb-3"><label>Nom complet</label><input type="text" name="nom_complet" class="form-control" value="<?php echo htmlspecialchars($authUser['nom_complet']); ?>" required></div>
              <div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($authUser['email']); ?>" required></div>
              <button class="btn btn-primary" type="submit">Modifier</button>
              <a href="/dashboard" class="btn btn-outline-primary">Retour</a>
              <a href="/deconnexion" class="btn btn-danger">Se déconnecter</a>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
