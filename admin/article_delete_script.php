<?php

require ("../inc/config.php");
$req = $bdd->prepare("DELETE FROM articles WHERE Id=:Id");
$req->execute([
    "Id"=>$_GET["Id"]
]);
header("Location: /admin");
