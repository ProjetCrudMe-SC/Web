<?php
declare(strict_types=1);
namespace src\Service;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtService{
    public static String $secretKey = "cesiblog";

    public static function createToken(array $datas) : String {
        $issuedAt = new \DateTime(); //Date de publication
        $expire = new \DateTime();
        $expire->modify('+12 hours');

        $data = [
          "iat" => $issuedAt->getTimestamp(),
            "iss" => "cesi.local",
            "nbf" => $issuedAt->getTimestamp(),
            "exp" => $expire->getTimestamp(),
            "datas" => $datas
        ];

        return JWT::encode($data, self::$secretKey, "HS512");

    }

    public static function checkToken() : array {

        if (! preg_match('/Bearer\s(\S+)/', $_SERVER['HTTP_AUTHORIZATION'], $matches)) {
            return [
                "code" => 1,
                "body" => "Token non trouvé dans la requête"
            ];
        }

        $jwt = $matches[1];
        if (! $jwt) {
            return [
                "code" => 1,
                "body" => "Aucun jeton n'a pu être extrait de l'en-tête d'autorisation."
            ];
        }

        try{
            //ça remonte une exception dès qu'il trouve une erreur on on veut catch l'erreur pour la donner en JSON
            $token = JWT::decode($jwt, new Key(self::$secretKey, 'HS512'));
        }catch (\Exception$e){
            return [
                "code" => 1,
                "body" => "Les données du jeton ne sont pas compatibles : {$e->getMessage()}"
            ];
        }

        $now = new \DateTimeImmutable();
        $serverName = "cesi.local";

        if ($token->iss !== $serverName ||
            $token->nbf > $now->getTimestamp() ||
            $token->exp < $now->getTimestamp())
        {
            return [
                "code" => 1,
                "body" => "Les données du jeton ne sont pas compatibles"
            ];
        }

        return [
            "code" => 0,
            "body" => "Token OK"
        ];

    }

}