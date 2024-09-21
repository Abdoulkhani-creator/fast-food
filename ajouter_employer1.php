<?php
session_start();

$admin_id = $_SESSION['admin_id'] ?? null;

if (!$admin_id) {
    header('location: connexion.php');
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestion-restauration";

$connect = new mysqli($servername, $username, $password, $dbname);

if ($connect->connect_error) {
    die("La connexion à la base de données a échoué : " . $connect->connect_error);
}

if (isset($_POST['submit'])) {
    $employe_num = $_POST['employe_num'];
    $employe_name = $_POST['employe_name'];
    $employe_prenom = $_POST['employe_prenom'];
    $employee_role = $_POST['employee_role'];
    $specialite = $_POST['specialité'];

    $query_ajouter = "INSERT INTO employe (NumeroTelephone, Nom, Prenom, Statut, Specialite) VALUES (?, ?, ?, ?, ?)";
    $stmt_ajouter = $connect->prepare($query_ajouter);
    $stmt_ajouter->bind_param("issss", $employe_num, $employe_name, $employe_prenom, $employee_role, $specialite);

    if ($stmt_ajouter->execute()) {
        echo "L'employé a été ajouté avec succès.";
    } else {
        echo "Erreur lors de l'ajout de l'employé : " . $stmt_ajouter->error;
    }

    $stmt_ajouter->close();
}

$connect->close();
?>
