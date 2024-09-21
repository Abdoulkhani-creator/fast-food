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

if (isset($_POST['modifier_employe'])) {
    $employe_num = $_POST['employe_num'];
    $employe_name = $_POST['employe_name'];
    $employe_prenom = $_POST['employe_prenom'];
    $employee_role = $_POST['employee_role'];
    $specialite = $_POST['specialite'];

    $query_modifier = "UPDATE employe SET Nom=?, Prenom=?, Statut=?, Specialite=? WHERE NumeroTelephone=?";
    $stmt_modifier = $connect->prepare($query_modifier);
    $stmt_modifier->bind_param("sssss", $employe_name, $employe_prenom, $employee_role, $specialite, $employe_num);

    if ($stmt_modifier->execute()) {
        echo "L'employé a été modifié avec succès.";
    } else {
        echo "Erreur lors de la modification de l'employé : " . $stmt_modifier->error;
    }

    $stmt_modifier->close();
}

$connect->close();
?>
