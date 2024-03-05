<?php

declare(strict_types=1);
namespace src\Model;

use src\Utils\Guid\GuidGenerator;

class User
{
    private ?string $Id = null;
    private string $NomPrenom;
    private string $Mail;
    private string $Password;
    private array $Roles;

    public function getId(): ?string
    {
        return $this->Id;
    }

    public function setId(?string $Id): User
    {
        $this->Id = $Id;
        return $this;
    }

    public function getNomPrenom(): string
    {
        return $this->NomPrenom;
    }

    public function setNomPrenom(string $NomPrenom): User
    {
        $this->NomPrenom = $NomPrenom;
        return $this;
    }

    public function getMail(): string
    {
        return $this->Mail;
    }

    public function setMail(string $Mail): User
    {
        $this->Mail = $Mail;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->Password;
    }

    public function setPassword(string $Password): User
    {
        $this->Password = $Password;
        return $this;
    }

    public function getRoles(): array
    {
        return $this->Roles;
    }

    public function setRoles(array $Roles): User
    {
        $this->Roles = $Roles;
        return $this;
    }

    public static function SqlAdd(User $user): array
    {
        $bdd = BDD::getInstance();
        try {
            $req = $bdd->prepare("INSERT INTO Users (Id, NomPrenom, Email, Password, Roles) VALUES(:Id, :NomPrenom, :Email, :Password, :Roles)");
            $req->execute([
                "Id" => GuidGenerator::GUID(),
                "NomPrenom" => $user->getNomPrenom(),
                "Email" => $user->getMail(),
                "Password" => $user->getPassword(),
                "Roles" => json_encode($user->getRoles(), JSON_THROW_ON_ERROR),
            ]);

            return [0, "Insertion OK", $bdd->lastInsertId()];
        } catch (\Exception $e) {
            return [1, "ERROR => {$e->getMessage()}"];
        }
    }

    public static function SqlGetByMail($mail): ?User
    {
        $bdd = BDD::getInstance();
        $requete = $bdd->prepare('SELECT * FROM Users WHERE Email=:mail');
        $requete->execute([
            "mail" => $mail
        ]);

        $userSql = $requete->fetch(\PDO::FETCH_ASSOC);
        if ($userSql) {
            $user = new User();
            $user->setMail($userSql["Email"])
                ->setNomPrenom($userSql["NomPrenom"])
                ->setId($userSql["Id"])
                ->setPassword($userSql["Password"])
                ->setRoles(json_decode($userSql["Roles"]));
            return $user;
        }
        return null;
    }


}