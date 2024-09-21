<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestion-restauration";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
   die("La connexion à la base de données a échoué : " . mysqli_connect_error());
}

session_start();
session_unset();
session_destroy();

header('location:../admin_login.html');

?>