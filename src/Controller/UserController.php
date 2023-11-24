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

    public function login(){
        if(isset($_POST["mail"]) && isset($_POST["password"])) {
            $user = User::SqlGetByMail($_POST["mail"]);
            if($user!=null){
                //Comparaison des mots de passe
                if (password_verify($_POST["password"], $user->getPassword())) {
                    session_start();
                    $_SESSION["login"] = [
                        "mail" => $user->getMail(),
                        "nomprenom" => $user->getNomPrenom(),
                        "roles" => $user->getRoles()
                    ];
                    header("location:/Article/all");
                } else {
                    throw new \Exception("Erreur User/Password");
                }
            }else{
                throw new \Exception("Aucun user avec ce mail");
            }
        }else{
            return $this->getTwig()->render("User/login.html.twig");
        }

    }

}