<?php
declare(strict_types=1);

namespace src\Model;

use DateTime;
use Exception;
use JsonSerializable;
use PDO;
use Random\RandomException;
use src\Utils\Guid\GuidGenerator;

class Nursery implements JsonSerializable
{
    private ?string $Id = null;
    private ?string $NameNursery = null;
    private ?string $Description = null;
    private ?string $Town = null;
    private ?Contact $Contact = null;
    private ?Coordinates $Coordinates = null;
    private ?DateTime $DatePublication = null;
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

    public function getDatePublication(): ?DateTime
    {
        return $this->DatePublication;
    }

    public function setDatePublication(?DateTime $DatePublication): Nursery
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

//    public function SqlAdd(?Coordinates $coordinates, ?Contact $contact): array
//    {
//        try {
//            $bdd = BDD::getInstance();
//
//            $requete = $bdd->prepare("INSERT INTO Coordinates (id, latitude, longitude) VALUES(:id, :latitude, :longitude)");
//            $execute = $requete->execute([
//                'id' => $coordinates->getId(),
//                'latitude' => $coordinates->getLatitude(),
//                'longitude' => $coordinates->getLongitude(),
//            ]);
//
//            $requete = $bdd->prepare("INSERT INTO Contacts (id, email, phone, Firstname, Lastname) VALUES(:id, :email, :phone, :firstname, :lastname)");
//            $execute = $requete->execute([
//                'id' => $contact->getId(),
//                'email' => $contact->getEmail(),
//                'phone' => $contact->getPhone(),
//                'firstname' => $contact->getFirstname(),
//                'lastname' => $contact->getLastname(),
//            ]);
//
//            $requete = $bdd->prepare("INSERT INTO Nurseries (id, name, description, datePublication, town, imageRepository, ImageFileName, ContactId, CoordinatesId) VALUES(:id, :name, :description, :datePublication, :town, :imageRepository, :imageFileName, :contactId, :coordinatesId)");
//
//            $execute = $requete->execute([
//                'id' => $this->getId(),
//                'name' => $this->getNameNursery(),
//                'description' => $this->getDescription(),
//                'town' => $this->getTown(),
//                'datePublication' => $this->getDatePublication()->format("Y-m-d"),
//                'imageRepository' => $this->getImageRepository(),
//                'imageFileName' => $this->getImageFileName(),
//                'contactId' => $contact->getId(),
//                'coordinatesId' => $coordinates->getId(),
//            ]);
//
//            return [0, "Insertion OK", $bdd->lastInsertId()];
//        } catch (Exception $e) {
//            return [1, $e->getMessage()];
//        }
//    }

    public static function SqlGetPaginated(int $pageNumber, int $pageSize): array
    {
        $offset = $pageSize * ($pageNumber - 1);
        $bdd = BDD::getInstance();
        $req = $bdd->prepare('SELECT Nurseries.Id AS NurseryId, Nurseries.Name, Nurseries.Description, Nurseries.Town, Nurseries.DatePublication, Nurseries.ImageRepository, Nurseries.ImageFileName, Coordinates.Id AS CoordinatesId, Coordinates.Latitude, Coordinates.Longitude, Contacts.Id AS ContactId, Contacts.Firstname, Contacts.Lastname, Contacts.Email, Contacts.Phone FROM Nurseries 
                      LEFT JOIN Coordinates ON Nurseries.CoordinatesId = Coordinates.Id 
                      LEFT JOIN Contacts ON Nurseries.ContactId = Contacts.Id 
                      LIMIT :pageSize OFFSET :offset');
        $req->bindParam(':pageSize', $pageSize, PDO::PARAM_INT);
        $req->bindParam(':offset', $offset, PDO::PARAM_INT);
        $req->execute();
        $nurseries = $req->fetchAll(PDO::FETCH_ASSOC);
        $nurseriesObjet = [];
        foreach ($nurseries as $nursery) {
            $nurseryObj = new self();
            $nurseryObj
                ->setId($nursery["NurseryId"])
                ->setNameNursery($nursery["Name"])
                ->setDescription($nursery["Description"])
                ->setTown($nursery["Town"])
                ->setDatePublication(new \DateTime($nursery["DatePublication"]))
                ->setImageRepository($nursery["ImageRepository"])
                ->setImageFileName($nursery["ImageFileName"])
                ->setCoordinates((new Coordinates())
                    ->setId($nursery["CoordinatesId"])
                    ->setLatitude($nursery["Latitude"])
                    ->setLongitude($nursery["Longitude"]))
                ->setContact((new Contact())
                    ->setId($nursery["ContactId"])
                    ->setFirstname($nursery["Firstname"])
                    ->setLastname($nursery["Lastname"])
                    ->setEmail($nursery["Email"])
                    ->setPhone($nursery["Phone"]));
            $nurseriesObjet[] = $nurseryObj;
        }
        return $nurseriesObjet;
    }

    /**
     * @param int $nb
     * @return array<int,Nursery>
     * @throws Exception
     */
    public static function SqlGetLast(int $nb): array
    {
        $bdd = BDD::getInstance();
        $requete = $bdd->prepare('SELECT * FROM Nurseries
            LEFT JOIN Coordinates ON Nurseries.coordinatesId = Coordinates.Id
            LEFT JOIN Contacts ON Nurseries.contactId = Contacts.Id
            ORDER BY name DESC LIMIT :nb');
        $requete->bindValue('nb', $nb, PDO::PARAM_INT);
        $requete->execute();
        $nurseriesSQL = $requete->fetchAll(PDO::FETCH_ASSOC);
        $nurseryObjet = [];
        foreach ($nurseriesSQL as $nurserySQL) {
            $nursery = new self();
            $date = new DateTime($nurserySQL["DatePublication"]);
            $nursery->setNameNursery($nurserySQL["Name"])
                ->setId($nurserySQL["Id"])
                ->setDescription($nurserySQL["Description"])
                ->setDatePublication($date)
                ->setTown($nurserySQL["Town"]);
            $nurseryObjet[] = $nursery;
        }
        return $nurseryObjet;
    }

    /**
     * @throws Exception
     */
    public static function SqlGetAll(): array
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
        $nurserysSQL = $requete->fetchAll(PDO::FETCH_ASSOC);
        $nurserysObjet = [];
        foreach ($nurserysSQL as $nurserySQL) {
            $nursery = new Nursery();
            $date = new DateTime($nurserySQL["DatePublication"]);
            $nursery
                ->setNameNursery($nurserySQL["Name"])
                ->setId($nurserySQL["NurseryId"])
                ->setDescription($nurserySQL["Description"])
                ->setDatePublication($date)
                ->setTown($nurserySQL["Town"]);
            $nurserysObjet[] = $nursery;
        }
        return $nurserysObjet;
    }

    /**
     * @throws RandomException
     */
    public static function SqlFixtures(): void
    {
        $bdd = BDD::getInstance();
//        $bdd->query('TRUNCATE TABLE Nurseries');
//        $bdd->query('TRUNCATE TABLE Contacts');
//        $bdd->query('TRUNCATE TABLE Coordinates');


        $arrayTown = array('Mont-Saint-Aignan', 'Rouen', 'Le Havre', 'Paris');
        $arrayTitre = array('Crescendo', 'Lumière d\'Étoiles Crèche', 'Douce Harmonie Maternelle', 'Sourires Radieux Nurserie');
        $arrayEmail = array('crescendo@creche.fr', 'lec@creche.fr', 'douce-harmonie@creche.fr', 'sourires@creches.fr');
        $arrayFirstName = array('Jean', 'Pierre', 'Paul', 'Jacques');
        $arrayLastName = array('Dupont', 'Durand', 'Martin', 'Lefebvre');


        $dateajout = new DateTime();
        for ($i = 1; $i <= 200; $i++) {
            shuffle($arrayTown);
            shuffle($arrayTitre);
            shuffle($arrayEmail);
            shuffle($arrayFirstName);
            shuffle($arrayLastName);
            $coordinates = new Coordinates();
            $latitude = random_int(490000, 500000) / 10000;
            $longitude = random_int(490000, 500000) / 10000;
            $coordinates
                ->setId(GuidGenerator::GUID())
                ->setLatitude($latitude)
                ->setLongitude($longitude);
            Coordinates::SqlAdd($coordinates->getId(), $coordinates->getLatitude(), $coordinates->getLongitude());
            $contact = new Contact();
            $contact
                ->setId(GuidGenerator::GUID())
                ->setEmail($arrayEmail[0])
                ->setPhone('06' . random_int(10000000, 99999999))
                ->setFirstname($arrayFirstName[0])
                ->setLastname($arrayLastName[0]);
            Contact::SqlAdd($contact->getId(), $contact->getFirstname(), $contact->getLastname(), $contact->getEmail(), $contact->getPhone());
            $id = GuidGenerator::GUID();
            $dateajout->modify('+1 day');
            $nursery = new Nursery();
            $nursery
                ->setId($id)
                ->setCoordinates($coordinates)
                ->setContact($contact)
                ->setNameNursery($arrayTitre[0])
                ->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla euismod, nisl nec aliquam ultricies, nunc nisl aliquet nunc, quis aliquam nisl')
                ->setDatePublication($dateajout)
                ->setTown($arrayTown[0]);
            Nursery::SqlAdd(
                $nursery->getNameNursery(),
                $nursery->getDescription(),
                $nursery->getTown(),
                $nursery->getImageRepository() ?? "",
                $nursery->getImageFileName() ?? "",
                $nursery->getDatePublication(),
                $coordinates->getId(),
                $contact->getId());
        }
    }

    public static function SqlDelete(string $id): bool
    {
        $bdd = BDD::getInstance();
        $req = $bdd->prepare("SELECT * FROM Nurseries WHERE Id=:Id");
        $req->bindParam(':Id', $id);
        $req->execute();
        $nurserySql = $req->fetch(PDO::FETCH_ASSOC);
        if (!$nurserySql) {
            return false;
        }
        $req = $bdd->prepare("DELETE FROM Nurseries WHERE Id=:Id");
        $req->execute([
            'Id' => $id
        ]);
        return true;
    }

    /**
     * @throws Exception
     */
    public static function SqlGetById(string $id): ?Nursery
    {
        $bdd = BDD::getInstance();
        $req = $bdd->prepare('
        SELECT
        Nurseries.Id AS NurseryId,
        Nurseries.Name,
        Nurseries.Description,
        Nurseries.DatePublication,
        Nurseries.Town,
        Nurseries.ImageRepository,
        Nurseries.ImageFileName,
        Coordinates.Id AS CoordinatesId,
        Coordinates.Latitude,
        Coordinates.Longitude,
        Contacts.Id AS ContactId,
        Contacts.Firstname,
        Contacts.Lastname,
        Contacts.Email,
        Contacts.Phone
        FROM Nurseries
        LEFT JOIN Coordinates ON Nurseries.coordinatesId = Coordinates.Id
        LEFT JOIN Contacts ON Nurseries.contactId = Contacts.Id
        WHERE Nurseries.Id=:Id');
        $req->bindParam(':Id', $id);
        $req->execute();
        $nurserySql = $req->fetch(PDO::FETCH_ASSOC);
        if ($nurserySql) {
            $nursery = new Nursery();
            $nursery
                ->setId($id)
                ->setNameNursery($nurserySql["Name"])
                ->setDescription(($nurserySql["Description"]))
                ->setDatePublication(new DateTime($nurserySql["DatePublication"]))
                ->setTown($nurserySql["Town"])
                ->setImageRepository($nurserySql["ImageRepository"])
                ->setImageFileName($nurserySql["ImageFileName"])
                ->setCoordinates((new Coordinates())
                    ->setId($nurserySql["CoordinatesId"])
                    ->setLatitude($nurserySql["Latitude"])
                    ->setLongitude($nurserySql["Longitude"]))
                ->setContact((new Contact())
                    ->setId($nurserySql["ContactId"])
                    ->setFirstname($nurserySql["Firstname"])
                    ->setLastname($nurserySql["Lastname"])
                    ->setEmail($nurserySql["Email"])
                    ->setPhone($nurserySql["Phone"]));
            return $nursery;
        }
        return null;
    }

    public static function SqlAdd(string $name, string $description, string $town, string $imageRepository, string $imageFileName, DateTime $datePublication, string $coordinatesId, string $contactId): array
    {
        $bdd = BDD::getInstance();
        try {
            $requete = $bdd->prepare(
                'INSERT INTO Nurseries VALUES (:Id, :ContactId, :CoordinatesId, :Name, :Description, :Town, :ImageRepository, :ImageFileName, :DatePublication)');
            $id = GuidGenerator::GUID();
            $requete->bindParam(':Id', $id);
            $requete->bindParam(':Name', $name);
            $requete->bindParam(':Description', $description);
            $requete->bindParam(':Town', $town);
            $requete->bindParam(':ImageRepository', $imageRepository);
            $requete->bindParam(':ImageFileName', $imageFileName);
            $datePublicationFormatted = $datePublication->format('Y-m-d');
            $requete->bindParam(':DatePublication', $datePublicationFormatted);
            $requete->bindParam(':ContactId', $contactId);
            $requete->bindParam(':CoordinatesId', $coordinatesId);
            $requete->execute();

            return array(0, "Insertion OK", $bdd->lastInsertId());
        } catch (Exception $e) {
            return array(1, $e->getMessage());
        }
    }

    public function SqlUpdate(): array
    {
        $bdd = BDD::getInstance();
        try {
            $requete = $bdd->prepare(
                'UPDATE Nurseries SET 
                    Name=:Name, 
                    Description=:Description, 
                    Town=:Town, 
                    ImageRepository=:ImageRepository, 
                    ImageFileName=:ImageFileName,
                    DatePublication=:DatePublication
                 WHERE Id=:Id');

            $id = $this->getId();
            $nameNursery = $this->getNameNursery();
            $description = $this->getDescription();
            $town = $this->getTown();
            $imageFileName = $this->getImageFileName();
            $imageRepository = $this->getImageRepository();
            $format = $this->getDatePublication()?->format("Y-m-d");

            $requete->bindParam(':Id', $id);
            $requete->bindParam(':Name', $nameNursery);
            $requete->bindParam(':Description', $description);
            $requete->bindParam(':Town', $town);
            $requete->bindParam(':ImageRepository', $imageRepository);
            $requete->bindParam(':ImageFileName', $imageFileName);
            $requete->bindParam(':DatePublication', $format);

            $requete->execute();

            return array(0, "[OK] Mise à jour");
        } catch (Exception $e) {
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