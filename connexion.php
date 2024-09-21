<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestion-restauration";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
   die("La connexion à la base de données a échoué : " . mysqli_connect_error());
}

$Nom = $_POST["name"];
$motdepasse = $_POST["pass"];

$query = "SELECT motdepasse FROM directeur WHERE Nom='$Nom'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 1) {
   $row = mysqli_fetch_assoc($result);
   $hashedPassword = $row['motdepasse'];
      // Vérification du mot de passe
    if (password_verify($motdepasse, $hashedPassword)) {
   $_SESSION['admin_id'] = $Nom;
   header("Location: Accueil.php");
   exit();
} else {
   echo "Le nom ou le mot de passe est incorrect";
}
} else {
   echo "Le nom ou le mot de passe est incorrect";
}
?>
