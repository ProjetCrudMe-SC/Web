<?php
require ("./inc/config.php");
if(isset($_POST["Email"]) &&  isset($_POST["Password"])){
    $requete = $bdd->prepare('SELECT * FROM users where Email = :Email');
    $requete->execute([
        "Email" => $_POST["Email"],
    ]);
    $user = $requete->fetch(PDO::FETCH_ASSOC);
    if (password_verify($_POST["Password"], $user["Password"])) {
        session_start();
        $_SESSION['Login'] = [
            'NomPrenom' => $user["NomPrenom"]
            ,'Role' => $user["Role"]
        ];
        header("location:/admin");
    }else{
        header("location:/login.php");
    }
}else{
    header("location:/login.php");
}
