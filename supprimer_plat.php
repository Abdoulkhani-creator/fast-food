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

// Vérifier si l'ID du plat à supprimer est passé en paramètre
if (isset($_GET['id'])) {
    $plat_id = $_GET['id'];

    // Préparer la requête de suppression
    $query_delete_plat = "DELETE FROM plat WHERE IdentifiantPlat = ?";
    $stmt_delete_plat = $connect->prepare($query_delete_plat);
    $stmt_delete_plat->bind_param("i", $plat_id); // i pour indiquer que c'est un entier

    // Exécuter la requête de suppression
    if ($stmt_delete_plat->execute()) {
        echo "Le plat a été supprimé avec succès.";
    } else {
        echo "Erreur lors de la suppression du plat : " . $stmt_delete_plat->error;
    }

    // Fermer la requête
    $stmt_delete_plat->close();
}

// Rediriger vers la page des produits après la suppression
header('location: produit_account.php');

// Fermer la connexion à la base de données
$connect->close();
?>
