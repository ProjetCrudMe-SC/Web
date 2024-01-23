<?php

namespace src\Model;

use JsonSerializable;
use src\Utils\Guid\GuidGenerator;

class Nursery implements JsonSerializable
{
    private ?string $Id = null;
    private ?string $NameNursery = null;
    private ?string $Description = null;
    private ?string $Town = null;
    private ?Contact $Contact = null;
    private ?Coordinates $Coordinates = null;
    private ?\DateTime $DatePublication = null;
    private ?string $ImageRepository = null;
    private ?string $ImageFileName = null;

    public function getId(): ?string
    {
        return $this->Id;
    }

    public function setId(?string $Id): Nursery
    {
        $this->Id = $Id;
        return $this;
    }

    public function getNameNursery(): ?string
    {
        return $this->NameNursery;
    }

    public function setNameNursery(?string $NameNursery): Nursery
    {
        $this->NameNursery = $NameNursery;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(?string $Description): Nursery
    {
        $this->Description = $Description;
        return $this;
    }

    public function getTown(): ?string
    {
        return $this->Town;
    }

    public function getCoordinates(): ?Coordinates
    {
        return $this->Coordinates;
    }

    public function getContact(): ?Contact
    {
        return $this->Contact;
    }

    public function setTown(?string $Town): Nursery
    {
        $this->Town = $Town;
        return $this;
    }

    public function getDatePublication(): ?\DateTime
    {
        return $this->DatePublication;
    }

    public function setDatePublication(?\DateTime $DatePublication): Nursery
    {
        $this->DatePublication = $DatePublication;
        return $this;
    }

    public function getImageRepository(): ?string
    {
        return $this->ImageRepository;
    }

    public function setImageRepository(?string $ImageRepository): Nursery
    {
        $this->ImageRepository = $ImageRepository;
        return $this;
    }

    public function getImageFileName(): ?string
    {
        return $this->ImageFileName;
    }

    public function setCoordinates(?Coordinates $Coordinates): Nursery
    {
        $this->Coordinates = $Coordinates;
        return $this;
    }

    public function setContact(?Contact $Contact): Nursery
    {
        $this->Contact = $Contact;
        return $this;
    }

    public function setImageFileName(?string $ImageFileName): Nursery
    {
        $this->ImageFileName = $ImageFileName;
        return $this;
    }

    public function premiersMots(int $n): string
    {
        preg_match('/^(\S+\s+){0,' . ($n - 1) . '}\S+/', $this->Description, $matches);
        $resultat = $matches[0];
        return $resultat;
    }

    public function strlen()
    {
        return \strlen($this->NameNursery) + 1;
    }

    public function SqlAdd(): array
    {
        try {
            $bdd = BDD::getInstance();
            $requete = $bdd->prepare("INSERT INTO Nurseries (id, name, description, datePublication, town, imageRepository, imageFileName) VALUES(:id, :name, :description, :datePublication, :town, :imageRepository, :imageFileName)");

            $execute = $requete->execute([
                "id" => $this->getId(),
                "name" => $this->getNameNursery(),
                "description" => $this->getDescription(),
                "town" => $this->getTown(),
                "datePublication" => $this->getDatePublication()->format("Y-m-d"),
                "imageRepository" => $this->getImageRepository(),
                "imageFileName" => $this->getImageFileName(),
            ]);

            $requete = $bdd->prepare("INSERT INTO Coordinates (id, latitude, longitude) VALUES(:id, :latitude, :longitude)");
            $execute = $requete->execute([
                "id" => GuidGenerator::GUID(),
                "latitude" => $this->getCoordinates()->getLatitude(),
                "longitude" => $this->getCoordinates()->getLongitude(),
                "nurseryId" => $this->getId(),
            ]);

            $requete = $bdd->prepare("INSERT INTO Contacts (id, email, phone) VALUES(:id, :email, :phone)");
            $execute = $requete->execute([
                "id" => GuidGenerator::GUID(),
                "email" => $this->getContact()->getEmail(),
                "phone" => $this->getContact()->getPhone(),
                "nurseryId" => $this->getId(),
            ]);
            return [0, "Insertion OK", $bdd->lastInsertId()];
        } catch (\Exception $e) {
            return [1, $e->getMessage()];
        }
    }


    /**
     * @param int $nb
     * @return array<int,Nursery>
     * @throws \Exception
     */
    public static function SqlGetLast(int $nb): array
    {
        $bdd = BDD::getInstance();
        $requete = $bdd->prepare('SELECT * FROM Nurseries 
            LEFT JOIN Coordinates ON Nurseries.coordinatesId = Coordinates.Id
            LEFT JOIN Contacts ON Nurseries.contactId = Contacts.Id
            ORDER BY name DESC LIMIT :nb');
        $requete->bindValue('nb', $nb, \PDO::PARAM_INT);
        $requete->execute();
        $nurserySQL = $requete->fetchAll(\PDO::FETCH_ASSOC);
        $nurseryObjet = [];
        foreach ($nurserySQL as $nurserySQL) {
            $nursery = new Nursery();
            $date = new \DateTime($nurserySQL["DatePublication"]);
            $nursery->setNameNursery($nurserySQL["Name"])
                ->setId($nurserySQL["Id"])
                ->setDescription($nurserySQL["Description"])
                ->setDatePublication($date)
                ->setTown($nurserySQL["Town"]);
            $nurseryObjet[] = $nursery;
        }
        return $nurseryObjet;
    }

    public static function SqlGetAll()
    {
        $bdd = BDD::getInstance();
        $requete = $bdd->prepare('SELECT
        Nurseries.Id AS NurseryId,
        Nurseries.Name,
        Nurseries.Description,
        Nurseries.DatePublication,
        Nurseries.Town,
        Coordinates.Id AS CoordinatesId,
        Coordinates.Latitude,
        Coordinates.Longitude,
        Contacts.Id AS ContactId,
        Contacts.Firstname,
        Contacts.Lastname,
        Contacts.Email,
        Contacts.Phone
        FROM Nurseries
        LEFT JOIN Coordinates ON Nurseries.CoordinatesId = Coordinates.Id
        LEFT JOIN Contacts ON Nurseries.ContactId = Contacts.Id;');
        $requete->execute();
        $nurserySQL = $requete->fetchAll(\PDO::FETCH_ASSOC);
        $nurserysObjet = [];
        foreach ($nurserySQL as $nurserySQL) {
            $nursery = new Nursery();
            $date = new \DateTime($nurserySQL["DatePublication"]);
            $nursery->setNameNursery($nurserySQL["Name"])
                ->setId($nurserySQL["NurseryId"])
                ->setDescription($nurserySQL["Description"])
                ->setDatePublication($date)
                ->setTown($nurserySQL["Town"]);
            $nurserysObjet[] = $nursery;
        }
        return $nurserysObjet;
    }

    public static function SqlFixtures()
    {
        $bdd = BDD::getInstance();
        $requete = $bdd->prepare('TRUNCATE TABLE Nurseries');
        $requete->execute();
        $arrayTown = array('Mont-Saint-Aignan', 'Rouen', 'Le Havre', 'Paris');
        $arrayTitre = array('Crescendo', 'Lumière d\'Étoiles Crèche', 'Douce Harmonie Maternelle', 'Sourires Radieux Nurserie');
        $dateajout = new \DateTime();
        for ($i = 1; $i <= 200; $i++) {
            shuffle($arrayTown);
            shuffle($arrayTitre);

            $dateajout->modify('+1 day');
            $nursery = new Nursery();
            $nursery
                ->setNameNursery($arrayTitre[0])
                ->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla euismod, nisl nec aliquam ultricies, nunc nisl aliquet nunc, quis aliquam nisl')
                ->setDatePublication($dateajout)
                ->setTown($arrayTown[0]);
            $nursery->sqlAdd();
        }
    }

    public static function SqlDelete(string $id): void
    {
        $bdd = BDD::getInstance();
        $req = $bdd->prepare("DELETE FROM Nurseries WHERE Id=:Id");
        $req->execute([
            "Id" => $id
        ]);
    }

    public static function SqlGetById(string $id): ?Nursery
    {
        $bdd = BDD::getInstance();
        $req = $bdd->prepare("SELECT * FROM Nurseries WHERE Id=:Id");
        $req->bindParam(':Id', $id, \PDO::PARAM_STR);
        $req->execute();
        $nurserySql = $req->fetch(\PDO::FETCH_ASSOC);
        if ($nurserySql) {
            $nursery = new Nursery();
            $nursery->setNameNursery($nurserySql["Name"])
                ->setId($id)
                ->setDescription(($nurserySql["Description"]))
                ->setDatePublication(new \DateTime($nurserySql["DatePublication"]))
                ->setTown($nurserySql["Town"])
                ->setImageRepository($nurserySql["ImageRepository"])
                ->setImageFileName($nurserySql["ImageFileName"]);
            return $nursery;
        }
        return null;
    }

    public function SqlUpdate(): array
    {
        $bdd = BDD::getInstance();
        try {
            $requete = $bdd->prepare('UPDATE Nurseries SET Name=:Name, Description=:description, DatePublication=:datePublication, Town=:town, ImageRepository=:imageRepository, ImageFileName=:imageFileName WHERE Id=:id');
            $result = $requete->execute([
                'name' => $this->getNameNursery(), 'description' => $this->getDescription(), 'datePublication' => $this->getDatePublication()->format("Y-m-d"), 'town' => $this->getTown(), 'imageRepository' => $this->getImageRepository(), 'imageFileName' => $this->getImageFileName(), 'id' => $this->getId()
            ]);
            return array(0, "[OK] Mise à jour");
        } catch (\Exception $e) {
            return array(1, "[ERREUR] " . $e->getMessage());
        }
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->Id,
            'name' => $this->NameNursery,
            'description' => $this->Description,
            'town' => $this->Town,
            'contact' => $this->Contact,
            'coordinates' => $this->Coordinates,
            'datePublication' => $this->DatePublication->format("Y-m-d"),
            'imageRepository' => $this->ImageRepository,
            'imageFileName' => $this->ImageFileName,
        ];
    }
}