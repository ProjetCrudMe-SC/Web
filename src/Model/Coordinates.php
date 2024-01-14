<?php

namespace src\Model;

use JsonSerializable;

class Coordinates implements JsonSerializable
{
    private ?int $Id = null;
    private ?float $Latitude = null;
    private ?float $Longitude = null;

    public function getId(): ?int
    {
        return $this->Id;
    }

    public function setId(?int $Id): Coordinates
    {
        $this->Id = $Id;
        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->Latitude;
    }

    public function setLatitude(?float $Latitude): Coordinates
    {
        $this->Latitude = $Latitude;
        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->Longitude;
    }

    public function setLongitude(?float $Longitude): Coordinates
    {
        $this->Longitude = $Longitude;
        return $this;
    }


    public static function getById(string $id): ?Coordinates
    {
        try {
            $bdd = BDD::getInstance();
            $requete = $bdd->prepare("SELECT * FROM Coordinates WHERE Id = :id");
            $requete->execute([
                "id" => $id,
            ]);

            $coordinatesSql = $requete->fetch(\PDO::FETCH_ASSOC);

            if ($coordinatesSql) {
                $coordinates = new Coordinates();
                $coordinates->setId($coordinatesSql["Id"])
                    ->setLatitude($coordinatesSql["Latitude"])
                    ->setLongitude($coordinatesSql["Longitude"]);

                return $coordinates;
            }

            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function getAll(): array
    {
        $bdd = BDD::getInstance();
        $requete = $bdd->prepare("SELECT * FROM Coordinates");
        $requete->execute();

        $coordinatesSQL = $requete->fetchAll(\PDO::FETCH_ASSOC);
        $coordinatesObjects = [];

        foreach ($coordinatesSQL as $coordinatesSQL) {
            $coordinates = new Coordinates();
            $coordinates->setId($coordinatesSQL["Id"])
                ->setLatitude($coordinatesSQL["Latitude"])
                ->setLongitude($coordinatesSQL["Longitude"]);

            $coordinatesObjects[] = $coordinates;
        }

        return $coordinatesObjects;
    }

    public function SqlAdd(): array
    {
        try {
            $bdd = BDD::getInstance();
            $requete = $bdd->prepare("
                INSERT INTO Coordinates (Id, Latitude, Longitude)
                VALUES(:id, :latitude, :longitude)");
            $requete->execute([
                "id" => $this->getId(),
                "latitude" => $this->getLatitude(),
                "longitude" => $this->getLongitude(),
            ]);

            return [0, "[OK] Ajout effectué"];
        } catch (\Exception $e) {
            return [1, "[ERREUR] " . $e->getMessage()];
        }
    }

    public function SqlUpdate(): array
    {
        try {
            $bdd = BDD::getInstance();
            $requete = $bdd->prepare('UPDATE Coordinates SET Latitude=:latitude, Longitude=:longitude WHERE Id=:id');
            $result = $requete->execute([
                'id' => $this->getId(),
                'latitude' => $this->getLatitude(),
                'longitude' => $this->getLongitude(),
            ]);

            return [0, "[OK] Mise à jour"];
        } catch (\Exception $e) {
            return [1, "[ERREUR] " . $e->getMessage()];
        }
    }

    public static function SqlDelete(string $id): array
    {
        try {
            $bdd = BDD::getInstance();
            $req = $bdd->prepare("DELETE FROM Coordinates WHERE Id=:id");
            $req->execute([
                "id" => $id,
            ]);

            return [0, "[OK] Suppression effectuée"];
        } catch (\Exception $e) {
            return [1, "[ERREUR] " . $e->getMessage()];
        }
    }

    #[\Override]
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'latitude' => $this->getLatitude(),
            'longitude' => $this->getLongitude(),
        ];
    }
}