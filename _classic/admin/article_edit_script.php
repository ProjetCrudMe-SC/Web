<?php
require ("../inc/config.php");
//Modifier le script ci dessous pour faire une mise à jour de l'article
$requete = $bdd->prepare('UPDATE articles SET Titre=:Titre, Description=:Description, DatePublication=:DatePublication, Auteur=:Auteur WHERE Id=:Id');

$execute = $requete->execute([
    'Id' => $_POST["articleId"]
    ,'Titre' => $_POST["titre"]
    , 'Description' => $_POST["description"]
    , 'DatePublication' => $_POST["datepublication"]
    , 'Auteur' => $_POST["auteur"]
]);

header("Location: /admin");