<?php
declare(strict_types=1);

namespace src\Controller;

use DateTime;
use Exception;
use finfo;
use JsonException;
use Random\RandomException;
use RuntimeException;
use src\Model\Contact;
use src\Model\Coordinates;
use src\Model\Nursery;
use src\Model\User;
use src\Service\JwtService;
use src\Utils\Guid\GuidGenerator;

class ApiController
{
    public function __construct()
    {
        header('Content-Type: application/json; charset=utf-8');
    }

    /**
     * @throws JsonException
     */
    public function nurseries(): false|string
    {
        $page = $_GET['page'] ?? '1';
        $limit = $_GET['limit'] ?? '10';
        if ($_SERVER["REQUEST_METHOD"] !== "GET") {
            header("HTTP/1.1 405 Method Not Allowed");
            return json_encode("Erreur de méthode (GET attendu)", JSON_THROW_ON_ERROR);
        }

        if ($page < 1 || $limit < 1 || $limit > 100) {
            header("HTTP/1.1 404 Not Found");
            return json_encode("Erreur de paramètres", JSON_THROW_ON_ERROR);
        }
        $nurseries = Nursery::SqlGetPaginated((int)$page, (int)$limit);
        return json_encode($nurseries, JSON_THROW_ON_ERROR);
    }

    /**
     * @throws JsonException
     * @throws RandomException
     * @throws Exception
     */
    public function nursery(): bool|string
    {
        $this->checkToken();
        $json = file_get_contents('php://input');

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $json = file_get_contents('php://input');
            $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);


            if (!isset($data["nameNursery"], $data["description"], $data["date"], $data["town"], $data["latitude"], $data["longitude"], $data["firstName"], $data["lastName"], $data["email"], $data["picture"], $data["phone"])) {
                header("HTTP/1.1 404 Not Found");
                return json_encode("Erreur il manque des données", JSON_THROW_ON_ERROR);
            }

            $imagesDetails = $this->getImage($data["picture"]);
            $date = new DateTime($data["date"]);
            $idCoordinates = GuidGenerator::GUID();
            $idContact = GuidGenerator::GUID();

            Coordinates::SqlAdd($idCoordinates, (float)$data["latitude"], (float)$data["longitude"]);
            Contact::SqlAdd($idContact, $data["firstName"], $data["lastName"], $data["phone"], $data["email"]);
            Nursery::SqlAdd($data["nameNursery"], $data["description"], $data["town"], $imagesDetails[1], $imagesDetails[0], $date, $idCoordinates, $idContact);
            return ("true");
        }

        if ($_SERVER["REQUEST_METHOD"] === "DELETE") {
            return $this->delete($json);
        }

        if ($_SERVER["REQUEST_METHOD"] === "PUT") {
            return $this->update($json);
        }

        $json = file_get_contents('php://input');
        return json_encode(Nursery::SqlGetById($json["id"]), JSON_THROW_ON_ERROR);
    }

    /**
     * @throws JsonException
     */
    public function token()
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header("HTTP/1.1 404 Not Found");
            return json_encode("Erreur de méthode (POST attendu)", JSON_THROW_ON_ERROR);
        }

        if (!isset($_POST["login"], $_POST["password"])) {
            header("HTTP/1.1 404 Not Found");
            return json_encode("Erreur il manque des données", JSON_THROW_ON_ERROR);
        }

        $login = $_POST["login"];
        $password = $_POST["password"];

        $user = User::SqlGetByMail($login);
        if ($user === null) {
            header("HTTP/1.1 404 Not Found");
            return json_encode("Utilisateur inconnu", JSON_THROW_ON_ERROR);
        }

        $datas = [
            "login" => $login,
        ];

        $jwt = JwtService::createToken($datas);
        return json_encode($jwt, JSON_THROW_ON_ERROR);
    }

    private function checkToken(): array
    {
        if (!isset($_SERVER["HTTP_AUTHORIZATION"])) {
            header("HTTP/1.1 401 Unauthorized");
            return ["code" => '401', "message" => "Token manquant"];
        }

        $token = $_SERVER["HTTP_AUTHORIZATION"];
        $result = JwtService::checkToken();
        if ($result["code"] === 1) {
            header("HTTP/1.1 401 Unauthorized");
            return ["code" => '401', "message" => "Token invalide"];
        }
        return ["code" => 0, "message" => "Token valide"];
    }

    private function getImageExtensionFromBase64($base64String): string
    {
        $data = base64_decode($base64String);
        $f = finfo_open();

        $mime_type = finfo_buffer($f, $data, FILEINFO_MIME_TYPE);
        finfo_close($f);

        $extensions = [
            'image/jpeg' => 'jpg',
            'image/gif' => 'gif',
            'image/png' => 'png',
            'image/jpg' => 'jpg',
            // Add more MIME types and their corresponding extensions as needed
        ];

        // Use a default extension if the MIME type is not recognized
        $defaultExtension = 'jpg';

        return $extensions[$mime_type] ?? $defaultExtension;
    }

    private function getImage(string $base64String): array
    {
        $extension = $this->getImageExtensionFromBase64($base64String);

        $tabExt = ['jpg', 'gif', 'png', 'jpeg',];
        if (in_array(strtolower($extension), $tabExt)) {
            $dateNow = new DateTime();
            $sqlRepository = $dateNow->format('Y/m');
            $repository = './uploads/images/' . $sqlRepository;

            if (!is_dir($repository) && !mkdir($repository, 0777, true) && !is_dir($repository)) {
                throw new RuntimeException(sprintf('Directory "%s" was not created', $repository));
            }

            $nomImage = md5(uniqid('', true)) . '.' . $extension;

            $decodedImage = base64_decode($base64String);
            file_put_contents($repository . '/' . $nomImage, $decodedImage);
            return [$nomImage, $sqlRepository];
        }
        return [];
    }

    /**
     * @throws JsonException
     */
    private function delete(String $json) : bool {
        $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        $response = Nursery::SqlDelete($data["id"]);
        if($response === false) {
            header("HTTP/1.1 404 Not Found");
            return false;
        }
        return true;
    }

    /**
     * @throws JsonException
     * @throws Exception
     */
    private function update(String $json) : bool {
        $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

        $imagesDetails = $this->getImage($data["picture"]);

        $date = new DateTime($data["date"]);
        Coordinates::SqlUpdate($data["idCoordinates"], (float)$data["latitude"], (float)$data["longitude"]);
        Contact::SqlUpdate($data["idContact"], $data["firstName"], $data["lastName"], $data["phone"], $data["email"]);
        $nursery = new Nursery();
        $nursery
            ->setId($data["id"])
            ->setNameNursery($data["nameNursery"])
            ->setDescription($data["description"])
            ->setTown($data["town"])
            ->setImageFileName($imagesDetails[0])
            ->setImageRepository($imagesDetails[1])
            ->setDatePublication($date);

        $nursery->SqlUpdate();
        return true;
    }

    /**
     * @throws RandomException
     * @throws JsonException
     * @throws Exception
     */
    private function add(String $json) {
        $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);


        if (!isset($data["nameNursery"], $data["description"], $data["date"], $data["town"], $data["latitude"], $data["longitude"], $data["firstName"], $data["lastName"], $data["email"], $data["picture"], $data["phone"])) {
            header("HTTP/1.1 404 Not Found");
            return json_encode("Erreur il manque des données", JSON_THROW_ON_ERROR);
        }

        $imagesDetails = $this->getImage($data["picture"]);
        $date = new DateTime($data["date"]);
        $idCoordinates = GuidGenerator::GUID();
        $idContact = GuidGenerator::GUID();

        Coordinates::SqlAdd($idCoordinates, (float)$data["latitude"], (float)$data["longitude"]);
        Contact::SqlAdd($idContact, $data["firstName"], $data["lastName"], $data["phone"], $data["email"]);
        Nursery::SqlAdd($data["nameNursery"], $data["description"], $data["town"], $imagesDetails[1], $imagesDetails[0], $date, $idCoordinates, $idContact);
        return ("true");
    }

}