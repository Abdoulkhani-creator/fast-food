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
    <title>Tableau de bord</title>
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

    <section class="dashboard">
        <h1 class="heading">Tableau de bord</h1>

        <div class="box-container">
            <div class="box">
                <h3>Bienvenue!</h3>
                <p><?= isset($fetch_profile['Nom']) ? $fetch_profile['Nom'] : "Nom non disponible"; ?></p>
                <a href="update_profile.php" class="btn">Mettre à jour le profil</a>
            </div>
            <div class="box">
                <?php
                $query_orders = "SELECT * FROM `commande`";
                $result_orders = mysqli_query($connect, $query_orders);
                $numbers_of_orders = mysqli_num_rows($result_orders);
                ?>
                <h3><?= $numbers_of_orders; ?></h3>
                <p>Total des commandes</p>
                <a href="commande.php" class="btn">Commandes</a>
            </div>
            <!-- Pour les employés -->
            <?php
            $query_employes = "SELECT COUNT(*) AS total_employes FROM `employe`";
            $result_employes = mysqli_query($connect, $query_employes);
            $row_employes = mysqli_fetch_assoc($result_employes);
            $total_employes = $row_employes['total_employes'];
            ?>
            <div class="box">
                <h3><?= $total_employes; ?></h3>
                <p>Total des employés</p>
                <a href="employes_accounts.php" class="btn">voir les employés</a>
            </div>
            <?php
            $query_plats = "SELECT COUNT(*) AS total_plats FROM `plat`";
            $result_plats = mysqli_query($connect, $query_plats);
            $row_plats = mysqli_fetch_assoc($result_plats);
            $total_plats = $row_plats['total_plats'];
            ?>
            <!-- Section pour le nombre total de plats -->
            <div class="box">
                <h3><?= $total_plats; ?></h3>
                <p>Total des plats</p>
                <a href="Afficher_Plat.php" class="btn">voir les plats</a>
            </div>
            <?php
            $query_clients = "SELECT COUNT(*) AS total_clients FROM `client`";
            $result_clients = mysqli_query($connect, $query_clients);
            $row_clients = mysqli_fetch_assoc($result_clients);
            $total_clients = $row_clients['total_clients'];
            ?>
            <!-- Section pour le nombre total de clients -->
            <div class="box">
                <h3><?= $total_clients; ?></h3>
                <p>nombre total de clients</p>
                <a href="liste_clients.php" class="btn">voir les clients</a>
            </div>
            <!-- Pour la modification d'un plat -->
            <div class="box">
                <h3>Modifier un plat</h3>
                <p>Modifier un plat</p>
                <a href="Modifier_plat.php" class="btn">Modifier un plat</a>
            </div>
            <!-- Pour l'ajout d'un employé -->
            <div class="box">
                <h3>Ajouter employé</h3>
                <p>Ajouter un employés</p>
                <a href="ajouter_employe.php" class="btn">Ajouter un employé</a>
            </div>
            <?php
            $query_admins = "SELECT COUNT(*) AS total_admins FROM `directeur`";
            $result_admins = mysqli_query($connect, $query_admins);
            $row_admins = mysqli_fetch_assoc($result_admins);
            $total_admins = $row_admins['total_admins'];
            ?>
            <!-- Section pour le nombre total de admin -->
            <div class="box">
                <h3><?= $total_admins; ?></h3>
                <p>Total des admins</p>
                <a href="admin_accounts.php" class="btn">voir les admins</a>
            </div>
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
