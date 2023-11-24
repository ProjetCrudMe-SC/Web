<?php
namespace src\Controller;

class UserController extends AbstractController {

    public function create(){
        return $this->getTwig()->render("User/create.html.twig");
    }

}