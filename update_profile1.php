<?php
// Assurez-vous de démarrer la session si ce n'est pas déjà fait
session_start();


// Incluez le fichier de configuration de la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestion-restauration";

$connect = mysqli_connect($servername, $username, $password, $dbname);

if (!$connect) {
    die("La connexion à la base de données a échoué : " . mysqli_connect_error());
}

// Vérifiez si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérez les données du formulaire
    $id_directeur = $_POST['id_directeur'];
    $new_nom = $_POST['new_nom'];
    $new_pass = $_POST['new_pass'];

    // Vérifiez si l'ID du directeur existe
    $check_directeur_query = "SELECT * FROM directeur WHERE IdentifiantDirecteur = $id_directeur";
    $check_directeur_result = mysqli_query($connect, $check_directeur_query);

    if (mysqli_num_rows($check_directeur_result) > 0) {
        // Mettez à jour le nom et le mot de passe dans la base de données     
        // Utilisation de password_hash pour hacher le mot de passe
        $hashedPassword = password_hash($new_pass, PASSWORD_DEFAULT);
        $update_query = "UPDATE directeur SET Nom = '$new_nom', motdepasse = '$hashedPassword' WHERE IdentifiantDirecteur = $id_directeur";
        $update_result = mysqli_query($connect, $update_query);

        if ($update_result) {
            echo "Profil mis à jour avec succès.";
        } else {
            echo "Erreur lors de la mise à jour du profil : " . mysqli_error($connect);
        }
    } else {
        echo "ID du directeur non trouvé.";
    }
}

// Fermez la connexion à la base de données
mysqli_close($connect);
?>
