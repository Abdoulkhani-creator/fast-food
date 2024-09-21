<?php
session_start();

$admin_id = $_SESSION['admin_id'] ?? null;

// Si l'ID de l'administrateur n'est pas présent dans la session, rediriger vers la page de connexion
if (!$admin_id) {
    header('location: connexion.php');
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestion-restauration";

$connect = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion à la base de données
if ($connect->connect_error) {
    die("La connexion à la base de données a échoué : " . $connect->connect_error);
}

// Récupérer le profil de l'administrateur
$query_profile = "SELECT * FROM directeur WHERE Nom = ?";
$stmt_profile = $connect->prepare($query_profile);
$stmt_profile->bind_param("s", $admin_id);
$stmt_profile->execute();
$result_profile = $stmt_profile->get_result();

// Vérifier si la requête s'est exécutée avec succès
if ($result_profile && $result_profile->num_rows > 0) {
    $fetch_profile = $result_profile->fetch_assoc();
    $nom_directeur = $fetch_profile['Nom'];
} else {
    // Gérer l'échec de la requête
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produits</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="admin_style.css">

</head>

<body>
    <header class="header">
        <section class="flex">
            <a href="Accueil.php" class="logo">Admin<span>Panel</span></a>
            <nav class="navbar">
                <a href="Accueil.php">Accueil</a>
                <a href="admin_accounts.php">Admins</a>
                <a href="produit_account.php">Produits</a>
                <a href="commande.php">Commandes</a>
                <a href="Ajouter_commande_plat.php">Stock</a>
                <a href="employes_accounts.php">Employés</a>
            </nav>
            <div class="icons">
                <div id="menu-btn" class="fas fa-bars"></div>
                <div id="user-btn" class="fas fa-user"></div>
            </div>
            <div class="profile">
    <?php
    $query_profile = "SELECT * FROM `directeur` WHERE Nom = ?";
    $stmt_profile = $connect->prepare($query_profile);
    $stmt_profile->bind_param("s", $admin_id); // s pour indiquer que c'est un String
    $stmt_profile->execute();
    $result_profile = $stmt_profile->get_result();

    if ($result_profile && $result_profile->num_rows > 0) {
        $fetch_profile = $result_profile->fetch_assoc();
    ?>
        <p><?= $fetch_profile['Nom']; ?></p>
        <a href="update_profile.php" class="btn">Mettre à jour le profil</a>
        <div class="flex-btn">
            <a href="admin_login.html" class="option-btn">Connexion</a>
            <a href="Enregister_admin.php" class="option-btn">Enregistrer</a>
        </div>
        <a href="components/admin_logout.php" onclick="return confirm('Déconnexion de ce site ?');" class="delete-btn">Déconnexion</a>
    <?php
    } else {
        echo '<p class="empty">Aucun profil disponible</p>';
    }
    ?>
</div>

        </section>
    </header>

<section class="accounts">

   <h1 class="heading">Comptes Produits</h1>

   <div class="box-container">

      <div class="box">
         <p>Ajouter un nouvel produit</p>
         <a href="Ajouter_Produit.php" class="option-btn">Ajouter</a>
      </div>
      <?php
$username = "root";
$servername = "localhost";
$dbname = "gestion-restauration";
$password = "";

$connect = mysqli_connect($servername, $username, $password, $dbname);

if (!$connect) {
    die("La connexion à la base de données a échoué : " . mysqli_connect_error());
}

$select_account = mysqli_query($connect, "SELECT IdentifiantPlat as id_plat, NomPlat, Categorie, Prix as prix FROM plat");

if ($select_account && mysqli_num_rows($select_account) > 0) {
    while ($fetch_accounts = mysqli_fetch_assoc($select_account)) {
        ?>
        <div class="box">
            <p>Numero de plat : <span><?= $fetch_accounts['id_plat']; ?></span></p>
            <p>Nom du plat : <span><?= $fetch_accounts['NomPlat']; ?></span></p>
            <p>Categorie : <span><?= $fetch_accounts['Categorie']; ?></span></p>
            <p>Prix : <span><?= $fetch_accounts['prix']; ?></span><span>DJF</span></p>
            <div class="flex-btn">
                <a href="Modifier_plat.php?id=<?= $fetch_accounts['id_plat']; ?>" class="option-btn">Modifier</a>
                <a href="supprimer_plat.php?id=<?= $fetch_accounts['id_plat']; ?>" class="delete-btn" onclick="return confirm('Supprimer ce plat ?');">Supprimer</a>
            </div>
        </div>
        <?php
    }
} else {
    echo '<p class="empty">Aucun compte disponible</p>';
}

mysqli_close($connect);
?>


   </div>

</section>
<script>
    let navbar = document.querySelector('.header .flex .navbar');
    let profile = document.querySelector('.header .flex .profile');

    document.querySelector('#user-btn').onclick = () => {
        profile.classList.toggle('active');
        navbar.classList.remove('active');
    }

    document.querySelector('#menu-btn').onclick = () => {
        navbar.classList.toggle('active');
        profile.classList.remove('active');
    }

    window.onscroll = () => {
        profile.classList.remove('active');
        navbar.classList.remove('active');
    }
</script>
</body>
</html>
