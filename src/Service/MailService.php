<?php
namespace src\Service;


use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;

class MailService{
    private Mailer $mailer;

    public function __construct(){
        $transport = Transport::fromDsn("");
        $this->mailer = new Mailer($transport);
    }

    public function send(array|String $from,array|String $to, String $subject, String $bodyHtml){
        $email = new Email();
        $email->from($from)
            ->to($to)
            ->subject($subject)
            ->html($bodyHtml);

        $this->mailer->send($email);
    }
}