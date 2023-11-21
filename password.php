<?php
$cost = 10;
$hash = password_hash("azerty", PASSWORD_BCRYPT, ["cost" => $cost]);

var_dump($hash);

if(password_verify("azerty2", $hash)){
    echo "Mot de passe valide";
}else{
    echo "Mot de passe non valide";
}