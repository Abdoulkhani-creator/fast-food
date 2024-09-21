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

// Vérifier si le paramètre 'id' est passé dans l'URL
if (isset($_GET['id'])) {
    $id_employe = $_GET['id'];

    // Préparer la requête de suppression
    $query_delete_employe = "DELETE FROM employe WHERE NumeroTelephone = ?";
    $stmt_delete_employe = $connect->prepare($query_delete_employe);
    $stmt_delete_employe->bind_param("i", $id_employe); // i pour indiquer que c'est un entier

    // Exécuter la requête de suppression
    if ($stmt_delete_employe->execute()) {
        echo "L'employé a été supprimé avec succès.";
    } else {
        echo "Erreur lors de la suppression de l'employé : " . $stmt_delete_employe->error;
    }

    // Fermer la requête
    $stmt_delete_employe->close();
}

// Rediriger vers la page des employés après la suppression
header('location: employes_accounts.php');

// Fermer la connexion à la base de données
$connect->close();
?>
