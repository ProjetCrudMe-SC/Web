<?php

declare(strict_types=1);
namespace src\Model;

use PDO;

class BDD
{
    private static $_instance = null;
    private const _DBHOSTNAME_ = "lamp-mariadb106";
    private const _DBUSERNAME_ = "root";
    private const _DBPASSWORD_ = "tiger";
    private const _DBNAME_ = "docker";
    private const _DBPORT_ = 3306;

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    public static function getInstance(): PDO
    {
        if (SELF::$_instance == null) {
            /* Database Connexion */
            try {
                SELF::$_instance = new PDO(
                    dsn: "mysql:host=" . SELF::_DBHOSTNAME_ . ";port=" . SELF::_DBPORT_ . ";dbname=" . SELF::_DBNAME_ . ";charset=utf8",
                    username: SELF::_DBUSERNAME_,
                    password: SELF::_DBPASSWORD_
                );
                SELF::$_instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (Exception $e) {
                die("Erreur : {$e->getMessage()}");
            }
        }

        return SELF::$_instance;
    }
}