<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestion-restauration";
$connect = mysqli_connect($servername, $username, $password, $dbname);

if (!$connect) {
    die("La connexion à la base de données a échoué : " . mysqli_connect_error());
}

// Vérifier si le formulaire a été soumis
if (isset($_POST['add_product'])) {
    // Récupération des données du formulaire
    $nom_produit = $_POST["name"];
    $prix_produit = $_POST["price"];
    $commentaire = $_POST["comment"];
    $num = $_POST["num"];
    $categorie = $_POST["categorie"];

    // Gestion de l'image
    $image = time() . $_FILES["image"]['name'];
    $upload_directory = __DIR__ . DIRECTORY_SEPARATOR . 'photos' . DIRECTORY_SEPARATOR;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_directory . $image)) {
        $image_path = 'photos' . '/'. $image;

        // Préparer la requête SQL d'insertion
        $requete_insertion = "INSERT INTO plat (IdentifiantPlat, NomPlat, Categorie, Prix, DescriptionPlat, CheminImage) VALUES ('$num', '$nom_produit','$categorie', $prix_produit, '$commentaire', '$image_path')";

        // Exécuter la requête
        if (mysqli_query($connect, $requete_insertion)) {
            echo "Le produit a été ajouté avec succès.";
        } else {
            echo "Erreur lors de l'ajout du produit : " . mysqli_error($connect);
        }
    } else {
        echo "Erreur lors de l'upload de l'image.";
    }
}

// Fermer la connexion à la base de données
mysqli_close($connect);
?>
