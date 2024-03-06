<?php

declare(strict_types=1);
namespace src\Controller;

use DateTime;
use Exception;
use Mpdf\Mpdf;
use Mpdf\MpdfException;
use Mpdf\Output\Destination;
use Random\RandomException;
use RuntimeException;
use src\Model\Contact;
use src\Model\Coordinates;
use src\Model\Nursery;
use src\Service\MailService;
use src\Utils\Guid\GuidGenerator;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class NurseryController extends AbstractController
{
    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws Exception
     */
    public function index(): string
    {
        $nurseries = Nursery::SqlGetLast(20);
        return $this->getTwig()->render('Nursery/index.html.twig', [
            "Nurseries" => $nurseries
        ]);
    }

    /**
     * @throws RandomException
     */
    public function fixtures(): string
    {
        Nursery::SqlFixtures();
        return "<p>Fixtures ok </p>";
    }

    /**
     * @throws SyntaxError
     * @throws RandomException
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function all(): string
    {
        $token = md5(random_bytes(32));
        $_SESSION["token"] = $token;
        $nurseries = Nursery::SqlGetAll();
        return $this->getTwig()->render('Nursery/all.html.twig', [
            "Nurseries" => $nurseries,
            "tokenCSRF" => $token
        ]);
    }

    /**
     * @throws Exception
     */
    public function delete(): void
    {
        UserController::protect(["Administrateur"]);
        if (isset($_POST["id"]) && $_SESSION["token"] === $_POST["tokenCSRF"]) {
            $result = Nursery::SqlDelete($_POST["id"]);
            var_dump($result);
        }
        header("Location: /Nursery/all");
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws Exception
     */
    public function add(): string
    {
        UserController::protect(["Redacteur", "Administrateur", "Editeur"]);
        if (isset($_POST["Name"], $_POST["Description"])) {
            $sqlRepository = null;
            $nomImage = null;

            if (!empty($_FILES["Image"]["name"])) {
                $tabExt = ["jpg", "jpeg", "gif", "png"];
                $extension = pathinfo($_FILES["Image"]["name"], PATHINFO_EXTENSION);
                if (in_array(strtolower($extension), $tabExt)) {
                    $dateNow = new DateTime();
                    $repository = "./uploads/images/{$dateNow->format("Y/m")}";
                    if (!is_dir($repository)) {
                        if (!mkdir($repository, 0777, true) && !is_dir($repository)) {
                            throw new \RuntimeException(sprintf('Directory "%s" was not created', $repository));
                        }
                    }
                    $sqlRepository = $dateNow->format("Y/m");
                    $nomImage = md5(uniqid('', true)) . "." . $extension;
                    move_uploaded_file($_FILES["Image"]["tmp_name"], $repository . "/" . $nomImage);
                }
            }

            $coordinates = new Coordinates();
            $coordinates
                ->setId(GuidGenerator::GUID())
                ->setLatitude((float)$_POST["Latitude"])
                ->setLongitude((float)$_POST["Longitude"]);
            Coordinates::SqlAdd($coordinates->getId(), $coordinates->getLatitude(), $coordinates->getLongitude());
            $contact = new Contact();
            $contact
                ->setId(GuidGenerator::GUID())
                ->setLastName($_POST["Lastname"])
                ->setFirstName($_POST["Firstname"])
                ->setEmail($_POST["Email"])
                ->setPhone($_POST["Phone"]);
            Contact::SqlAdd($contact->getId(), $contact->getFirstName(), $contact->getLastName(), $contact->getEmail(), $contact->getPhone());
            $nursery = new Nursery();
            $nursery
                ->setId(GuidGenerator::GUID())
                ->setTown($_POST["Town"])
                ->setDescription($_POST["Description"])
                ->setDatePublication(new DateTime($_POST["DatePublication"]))
                ->setNameNursery($_POST["Name"])
                ->setImageRepository($sqlRepository)
                ->setImageFileName($nomImage);
            Nursery::SqlAdd(
                $nursery->getNameNursery(),
                $nursery->getDescription(),
                $nursery->getTown(),
                $nursery->getImageRepository(),
                $nursery->getImageFileName(),
                $nursery->getDatePublication(),
                $coordinates->getId(),
                $contact->getId());

//            $nursery->setId($result[2]);
//            $mail = new MailService();
//            $mail->send(
//                from: "admin@votresite.com",
//                to: "admin@votresite.com",
//                subject: "Une nouvelle crèche a été posté",
//                bodyHtml: $this->getTwig()->render("Mail/nursery.add.html.twig", [
//                    "crèche" => $nursery
//                ])
//            );

            header("Location: /Nursery/all");
        }
        return $this->getTwig()->render("Nursery/add.html.twig");
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     * @throws Exception
     */
    public function show(string $id): string
    {
        $nursery = Nursery::SqlGetById($id);
        if ($nursery == null) {
            header("Location: /Nursery/all");
        }
        return $this->getTwig()->render("Nursery/show.html.twig", [
            "Nursery" => $nursery
        ]);
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws Exception
     */
    public function update(string $id)
    {
        error_reporting(E_ALL);
        ob_start();
        $nursery = Nursery::SqlGetById($id);
        if ($nursery !== null) {
            if (isset($_POST["NameNursery"], $_POST["Description"], $_POST["DatePublication"], $_POST["Town"], $_POST["Firstname"], $_POST["Lastname"], $_POST["Email"], $_POST["Phone"], $_POST["Latitude"], $_POST["Longitude"])) {
                $sqlRepository = null;
                $nomImage = null;
                if (!empty($_FILES['Image']['name'])) {
                    $tabExt = ['jpg', 'gif', 'png', 'jpeg'];    // Extensions autorisees
                    $extension = pathinfo($_FILES['Image']['name'], PATHINFO_EXTENSION);
                    if (in_array(strtolower($extension), $tabExt)) {
                        $dateNow = new DateTime();
                        $sqlRepository = $dateNow->format('Y/m');
                        $repository = './uploads/images/' . $dateNow->format('Y/m');
                        if (!is_dir($repository)) {
                            if (!mkdir($repository, 0777, true) && !is_dir($repository)) {
                                throw new RuntimeException(sprintf('Directory "%s" was not created', $repository));
                            }
                        }
                        $nomImage = md5(uniqid('', true)) . '.' . $extension;

                        move_uploaded_file($_FILES['Image']['tmp_name'], $repository . '/' . $nomImage);

                        if ($_POST['imageAncienne'] !== '' && $_POST['imageAncienne'] !== '/' && file_exists("../../public/uploads/images/{$_POST["imageAncienne"]}")) {
                            unlink("./uploads/images/{$_POST['imageAncienne']}");
                        }
                    }
                }

                $coordinates = $nursery->getCoordinates();
                if($coordinates === null){
                    error_log("Coordinates est null");
                }
                Coordinates::SqlUpdate($_POST["CoordinatesId"], $_POST["Latitude"], $_POST["Longitude"]);

                $contact = $nursery->getContact();
                if($contact === null){
                    error_log("Contact est null");
                }
               Contact::SqlUpdate($_POST["ContactId"], $_POST["Firstname"], $_POST["Lastname"], $_POST["Phone"], $_POST["Email"]);

                $date = new DateTime($_POST["DatePublication"]);
                $nursery
                    ->setNameNursery($_POST["NameNursery"])
                    ->setDescription($_POST["Description"])
                    ->setDatePublication($date)
                    ->setTown($_POST["Town"])
                    ->setImageRepository($sqlRepository)
                    ->setImageFileName($nomImage);
                var_dump('here');
                $result = $nursery->SqlUpdate();
                if (($result[0] === "1") && $nomImage !== null) {
                    unlink($repository . '/' . $nomImage);
                }
                error_log("Valeur de $_POST : " . print_r($_POST, true));
                header("Location: /Nursery/update/$id");
                ob_end_flush();
                exit();
            }

            return $this->getTwig()->render('Nursery/update.html.twig', [
                "nursery" => $nursery,
                'post' => $_POST
            ]);
        }

        header("Location: /Nursery/all");
        exit();
    }

    /**
     * @throws MpdfException
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws Exception
     */
    public function pdf(string $id): void
    {
        $nursery = Nursery::SqlGetById($id);
        $mpdf = new Mpdf([
            "tempDir" => $_SERVER["DOCUMENT_ROOT"] . "/../var/cache/pdf"
        ]);
        $mpdf->WriteHTML($this->getTwig()->render("Nursery/pdf.html.twig", [
            "Nursery" => $nursery
        ]));
        $mpdf->Output(name: "Creche.pdf", dest: Destination::DOWNLOAD);
    }


}