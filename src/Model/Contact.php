<?php

declare(strict_types=1);
namespace src\Model;

use Exception;
use JsonSerializable;
use src\Utils\Guid\GuidGenerator;

class Contact implements JsonSerializable
{
    private ?string $id = null;
    private ?string $firstName = null;
    private ?string $lastName = null;
    private ?string $phone = null;
    private ?string $email = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): Contact
    {
        $this->id = $id;
        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): Contact
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): Contact
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): Contact
    {
        $this->phone = $phone;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): Contact
    {
        $this->email = $email;
        return $this;
    }

    public static function SqlUpdate(String $id, String $firstName, String $lastName, String $phone, String $email): array
    {
        $bdd = BDD::getInstance();
        try {
            $requete = $bdd->prepare('UPDATE Contacts SET Firstname=:Firstname, Lastname=:Lastname, Phone=:Phone, Email=:Email WHERE Id=:Id');
            $requete->execute([
                'Id' => $id,
                'Firstname' => $firstName,
                'Lastname' => $lastName,
                'Phone' => $phone,
                'Email' => $email
            ]);

            return [0, "[OK] Mise à jour"];
        } catch (\Exception $e) {
            return [1, "[ERREUR] " . $e->getMessage()];
        }
    }

    public static function SqlAdd(String $id, String $firstName, String $lastName, String $phone, String $email) :array {
        $bdd = BDD::getInstance();
        try {
            $requete = $bdd->prepare("
            INSERT INTO Contacts (Id, Firstname, Lastname, Phone, Email)
            VALUES(:id, :Firstname, :Lastname, :Phone, :Email)");
            $requete->bindParam(":id", $id);
            $requete->bindParam(":Firstname", $firstName);
            $requete->bindParam(":Lastname", $lastName);
            $requete->bindParam(":Phone", $phone);
            $requete->bindParam(":Email", $email);
            $requete->execute();

            return [0, "[OK] Ajout"];
        } catch (Exception $e) {
            return [1, "[ERREUR] " . $e->getMessage()];
        }
    }

    public function SqlRemove(string $id): array
    {
        try {
            $bdd = BDD::getInstance();
            $requete = $bdd->prepare("DELETE FROM Contacts WHERE Id = :id");
            $requete->execute([
                "id" => $id,
            ]);

            return [0, "[OK] Suppression effectuée"];
        } catch (\Exception $e) {
            return [1, "[ERREUR] " . $e->getMessage()];
        }
    }




    #[\Override] public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'firstname' => $this->firstName,
            'lastname' => $this->lastName,
            'phone' => $this->phone,
            'email' => $this->email
        ];
    }


}