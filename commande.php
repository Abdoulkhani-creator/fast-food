<?php
session_start();

$admin_id = $_SESSION['admin_id'] ?? null;

// Si l'ID de l'administrateur n'est pas présent dans la session, rediriger vers la page de connexion
if (!$admin_id) {
    header('location: connexion.php');
    exit();
}

// Remplacez ces informations de connexion par les vôtres
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestion-restauration";

// Créer une connexion
$connect = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
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

    // Traitement de la mise à jour du statut de livraison
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_statut"])) {
        $commande_id = $_POST["commande_id"];
        $nouveau_statut = $_POST["nouveau_statut"];

        // Mettez à jour le statut de livraison dans la base de données
        $update_sql = "UPDATE commande SET StatutLivraison = ? WHERE NumeroCommande = ?";
        $stmt_update = $connect->prepare($update_sql);
        $stmt_update->bind_param("si", $nouveau_statut, $commande_id);

        if ($stmt_update->execute()) {
            echo "Statut de livraison mis à jour avec succès.";
        } else {
            echo "Erreur lors de la mise à jour du statut de livraison : " . $stmt_update->error;
        }
    }

  
} else {
    // Gérer l'échec de la requête
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste Des Commandes</title>
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
    <section>
        <h2 class="heading">Liste des commandes</h2>
        <table class="accounts" border="2">
            <thead>
                <tr>
                    <th>ID Commande</th>
                    <th>Numero Tel Client</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Status de Livraison</th>
                    <th>Actions</th>
                    <!-- Ajoutez d'autres colonnes selon votre structure de base de données -->
                </tr>
            </thead>
            <tbody>
                <?php
                  // Requête SQL pour récupérer les informations des commandes
    $sql = "SELECT * FROM commande";
    $result = $connect->query($sql);
    // Vérifier si la requête s'est exécutée avec succès
if ($result !== false) {
                // Afficher les données dans le tableau
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["NumeroCommande"] . "</td>";
                        echo "<td>" . $row["NumeroTelephoneClient"] . "</td>";
                        echo "<td>" . $row["DateCommande"] . "</td>";
                        echo "<td>" . $row["MontantTotal"] . "</td>";
                        echo "<td>" . $row["StatutLivraison"] . "</td>";
                        echo "<td>";
                        echo "<form method='post' action=''>";
                        echo "<input type='hidden' name='commande_id' value='" . $row["NumeroCommande"] . "'>";
                        echo "<select name='nouveau_statut'>";
                        echo "<option value='En attente'>En attente</option>";
                        echo "<option value='En cours de livraison'>En cours de livraison</option>";
                        echo "<option value='Livré'>Livré</option>";
                        echo "</select>";
                        echo "<button type='submit' name='update_statut'>Mettre à jour</button>";
                        echo "</form>";
                        echo "</td>";
                        // Ajoutez d'autres colonnes selon votre structure de base de données
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>Aucune commande trouvée</td></tr>";
                }
            } else {
                    // Gérer l'échec de la requête
                    echo "Erreur lors de l'exécution de la requête : " . $connect->error;
                }
                ?>
            </tbody>
        </table>
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

<?php
// Fermer la connexion
$connect->close();
?>
