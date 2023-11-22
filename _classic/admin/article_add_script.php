<?php
require ("../inc/config.php");

$sqlRepository = null;
$nomImage = null;

if(!empty($_FILES["Image"]["name"])){
    $tabExt = ["jpg", "jpeg", "gif", "png"]; // Extension autorisée
    $extension = pathinfo($_FILES["Image"]["name"], PATHINFO_EXTENSION);
    if(in_array(strtolower($extension), $tabExt)){
        //Fabriquer le répertoire d'auccil façon Wrodpress (YYYY/MM)
        $dateNow = new DateTime();
        $repository = "../uploads/images/{$dateNow->format("Y/m")}";
        if(!is_dir($repository)) {
            mkdir($repository, 0777, true);
        }
        $sqlRepository = $dateNow->format("Y/m");
        //Renommer le fichier image à la volée
        $nomImage = md5(uniqid()).".".$extension;

        //Upload du fichier
        move_uploaded_file($_FILES["Image"]["tmp_name"], $repository."/".$nomImage);
    }
}

$requete = $bdd->prepare("INSERT INTO articles (Titre, Description, DatePublication, Auteur, ImageRepository, ImageFileName) VALUES(:Titre, :Description, :DatePublication, :Auteur, :ImageRepository, :ImageFileName)");

$execute = $requete->execute([
"Titre" => $_POST["titre"],
    "Description" => $_POST["description"],
    "DatePublication" => $_POST["datepublication"],
    "Auteur" => $_POST["auteur"],
    "ImageRepository" => $sqlRepository,
    "ImageFileName" => $nomImage,
]);
header("Location: /admin");