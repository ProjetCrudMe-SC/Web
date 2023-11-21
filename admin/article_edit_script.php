<?php
require ("../inc/config.php");
//Modifier le script ci dessous pour faire une mise à jour de l'article
$requete = $bdd->prepare("INSERT INTO articles (Titre, Description, DatePublication, Auteur) VALUES(:Titre, :Description, :DatePublication, :Auteur)");

$execute = $requete->execute([
"Titre" => $_POST["titre"],
    "Description" => $_POST["description"],
    "DatePublication" => $_POST["datepublication"],
    "Auteur" => $_POST["auteur"],
]);
header("Location: /admin");