<?php
namespace src\Service;

use Firebase\JWT\JWT;

class JwtService{
    public static String $secretKey = "cesiblog";

    public static function createToken(array $datas) : String {
        $issuedAt = new \DateTime(); //Date de publication
        $expire = new \DateTime();
        $expire->modify('+6 minutes');

        $data = [
          "iat" => $issuedAt->getTimestamp(), // Date création
            "iss" => "cesi.local",
            "nbf" => $issuedAt->getTimestamp(), //Utilisable pas avant ...
            "exp" => $expire->getTimestamp(),
            "datas" => $datas
        ];

        $jwt = JWT::encode($data, self::$secretKey, "HS512");

        return $jwt;

    }

}