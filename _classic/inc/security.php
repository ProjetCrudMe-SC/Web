<?php

function have_good_role(array $rolesCompatibles) :bool {
    session_start();
    if(isset($_SESSION["Login"]) && isset($_SESSION["Login"]["Role"])){
        $result =  in_array($_SESSION["Login"]["Role"], $rolesCompatibles);
        if(!$result){
            $_SESSION["MSG"] = [
                "Type" => "Error",
                "Message" => "Vous n'avez pas le bon role"
            ];
            return false;
        }else{
            return true;
        }
    }else{
        return false;
    }
}