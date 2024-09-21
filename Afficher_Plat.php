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

// Récupérer tous les produits de la table "plat"
$query_select_produits = "SELECT * FROM plat";
$result_select_produits = $connect->query($query_select_produits);

// Vérifier si la requête s'est exécutée avec succès
if ($result_select_produits) {
    // Afficher les produits
    echo '<!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Liste des Produits</title>
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
                <div class="profile">'?>
                    <?php
                    if ($result_profile && $result_profile->num_rows > 0) {
                    ?>
                        <p><?= $fetch_profile['Nom']; ?></p>
                        <a href="update_profile.php" class="btn">Mettre à jour le profil</a>
                        <div class="flex-btn">
                            <!-- Vous pouvez retirer ces liens de connexion et d'enregistrement ici s'ils ne sont pas nécessaires -->
                            <!-- <a href="admin_login.html" class="option-btn">Connexion</a>
                            <a href="Enregister_admin.php" class="option-btn">Enregistrer</a> -->
                        </div>
                        <a href="components/admin_logout.php" onclick="return confirm('Déconnexion de ce site ?');" class="delete-btn">Déconnexion</a>
                    <?php
                    } else {
                        echo '<p class="empty">Aucun profil disponible</p>';
                    }
                    ?>
                </div>
            </section>
        </header>';
        <?php

    echo '<h2>Liste des Produits</h2>';
    echo '<table border="2">';
    echo '<tr><th>Identifiant</th><th>Nom du Plat</th><th>Categorie</th><th>Prix</th><th>Description</th><th>Image</th></tr>';

    while ($row = $result_select_produits->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . $row['IdentifiantPlat'] . '</td>';
        echo '<td>' . $row['NomPlat'] . '</td>';
        echo '<td>' . $row['Categorie'] . '</td>';
        echo '<td>' . $row['Prix'] . '</td>';
        echo '<td>' . $row['DescriptionPlat'] . '</td>';
        echo '<td><img src="' . $row['CheminImage'] . '" alt="Image du plat" style="max-width: 100px; max-height: 100px;"></td>';
        echo '</tr>';
    }

    echo '</table>';?>
    
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

<?php
    echo'</body>';
    echo'</html>';
} else {
    echo "Erreur lors de la récupération des produits : " . $connect->error;
}

// Fermer la connexion à la base de données
$connect->close();
?>
