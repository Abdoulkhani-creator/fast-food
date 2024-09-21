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

// Vérifier si le paramètre 'delete' est passé dans l'URL
if (isset($_GET['delete'])) {
    $id_directeur = $_GET['delete'];

    // Préparer la requête de suppression
    $query_delete_directeur = "DELETE FROM directeur WHERE IdentifiantDirecteur = ?";
    $stmt_delete_directeur = $connect->prepare($query_delete_directeur);
    $stmt_delete_directeur->bind_param("i", $id_directeur); // i pour indiquer que c'est un entier

    // Exécuter la requête de suppression
    if ($stmt_delete_directeur->execute()) {
        echo "L'administrateur a été supprimé avec succès.";
    } else {
        echo "Erreur lors de la suppression de l'administrateur : " . $stmt_delete_directeur->error;
    }

    // Fermer la requête
    $stmt_delete_directeur->close();
}

// Rediriger vers la page des administrateurs après la suppression
header('location: admin_accounts.php');

// Fermer la connexion à la base de données
$connect->close();
?>
