<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestion-restauration";

$connect = mysqli_connect($servername, $username, $password, $dbname);

if (!$connect) {
    die("La connexion à la base de données a échoué : " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($connect, $_POST['name']);
    $pass = mysqli_real_escape_string($connect, $_POST['pass']);
    $cpass = mysqli_real_escape_string($connect, $_POST['cpass']);
    $num = mysqli_real_escape_string($connect, $_POST['num']);

    if ($pass != $cpass) {
        echo "Les mots de passe ne correspondent pas.";
    } else {
        // Utilisation de password_hash pour hacher le mot de passe
        $hashedPassword = password_hash($cpass, PASSWORD_DEFAULT);       
        $insert_query = "INSERT INTO `directeur` (Nom, motdepasse, IdentifiantDirecteur) VALUES ('$name', '$hashedPassword' , '$num')";
        $insert_result = mysqli_query($connect, $insert_query);

        if ($insert_result) {
            echo "Enregistrement réussi.";
        } else {
            echo "Erreur lors de l'enregistrement : " . mysqli_error($connect);
        }
    }
}

mysqli_close($connect);
?>
