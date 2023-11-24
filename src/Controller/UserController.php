<?php
namespace src\Controller;

use src\Model\User;

class UserController extends AbstractController {

    public function create(){
        if(isset($_POST["nomprenom"]) && isset($_POST["mail"]) && isset($_POST["password"]) && isset($_POST["roles"])){
            $user = new User();
            $hashpass = password_hash($_POST["password"], PASSWORD_BCRYPT, ["cost"=>12]);
            $user->setNomPrenom($_POST["nomprenom"])
                ->setMail($_POST["mail"])
                ->setPassword($hashpass)
                ->setRoles($_POST["roles"]);
            $result = User::SqlAdd($user);

            header("location:/");
        }
        return $this->getTwig()->render("User/create.html.twig");
    }

}