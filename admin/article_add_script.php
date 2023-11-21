<?php
require ("../inc/config.php");

$requete = $bdd->prepare("INSERT INTO articles (Titre, Description, DatePublication, Auteur) VALUES(:Titre, :Description, :DatePublication, :Auteur)");

$execute = $requete->execute([
    "Titre" => "Troisième",
    "Description" => "Mon troisième article",
    "DatePublication" => "2023-11-11",
    "Auteur" => "Fabien",
]);
// Tester en allant sur www.cesi.local/admin/article_add_script.php
var_dump($execute);