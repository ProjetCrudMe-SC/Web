<?php
namespace src\Controller;

use src\Service\MailService;

class ContactController extends AbstractController {

    public function form(){
        return $this->getTwig()->render("Contact/form.html.twig");
    }

    public function send(){
        $mail = new MailService();
        $mail->send(
            from: $_POST["mail"],
            to: "admin@votresite.com",
            subject: "Contact depuis le formulaire principal",
            bodyHtml: "<h1>Bonjour vous avez reçu un mail</h1>"
        );
        header("location:/Contact/form");
    }
}