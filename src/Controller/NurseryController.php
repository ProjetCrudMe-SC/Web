<?php

namespace src\Controller;

use DateTime;
use Exception;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;
use Random\RandomException;
use src\Model\Nursery;
use src\Service\MailService;
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

    public function delete(): void
    {
        UserController::protect(["Redacteur", "Administrateur", "Editeur"]);
        if (isset($_POST["id"])) {
            if ($_SESSION["token"] == $_POST["tokenCSRF"]) {
                Nursery::SqlDelete($_POST["id"]);
            }
        }
        header("Location: /Creche/all");
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws Exception
     */
    public function add()
    {
        UserController::protect(["Redacteur", "Administrateur", "Editeur"]);
        if (isset($_POST["Titre"]) && isset($_POST["Description"])) {
            $sqlRepository = null;
            $nomImage = null;

            if (!empty($_FILES["Image"]["name"])) {
                $tabExt = ["jpg", "jpeg", "gif", "png"]; // Extension autorisée
                $extension = pathinfo($_FILES["Image"]["name"], PATHINFO_EXTENSION);
                if (in_array(strtolower($extension), $tabExt)) {
                    //Fabriquer le répertoire d'auccil façon Wrodpress (YYYY/MM)
                    $dateNow = new DateTime();
                    $repository = "./uploads/images/{$dateNow->format("Y/m")}";
                    if (!is_dir($repository)) {
                        mkdir($repository, 0777, true);
                    }
                    $sqlRepository = $dateNow->format("Y/m");
                    //Renommer le fichier image à la volée
                    $nomImage = md5(uniqid()) . "." . $extension;
                    //Upload du fichier
                    move_uploaded_file($_FILES["Image"]["tmp_name"], $repository . "/" . $nomImage);
                }
            }

            $nursery = new Nursery();
            $nursery->setTown($_POST["Town"])
                ->setDescription($_POST["Description"])
                ->setDatePublication(new DateTime($_POST["DatePublication"]))
                ->setNameNursery($_POST["Name"])
                ->setImageRepository($sqlRepository)
                ->setImageFileName($nomImage);
            $result = $nursery->SqlAdd();

            //Envoi du mail
            $nursery->setId($result[2]);
            $mail = new MailService();
            $mail->send(
                from: "admin@votresite.com",
                to: "admin@votresite.com",
                subject: "Un nouvel Creche a été posté",
                bodyHtml: $this->getTwig()->render("Mail/nursery.add.html.twig", [
                    "crèche" => $nursery
                ])
            );

            header("Location: /Nursery/all");
        }
        return $this->getTwig()->render("Nursery/add.html.twig");
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
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
     */
    public function update(string $id)
    {
        $nursery = Nursery::SqlGetById($id);
        if ($nursery != null) {
            if (isset($_POST["Name"]) && isset($_POST["Description"]) && isset($_POST["DatePublication"]) && isset($_POST["Town"])) {
                // Repris de la version "classic"
                $sqlRepository = null;
                $nomImage = null;

                if (!empty($_FILES['Image']['name'])) {
                    $tabExt = ['jpg', 'gif', 'png', 'jpeg'];    // Extensions autorisees
                    $extension = pathinfo($_FILES['Image']['name'], PATHINFO_EXTENSION);
                    // strtolower = on compare ce qui est comparage (JPEG =! jpeg)
                    if (in_array(strtolower($extension), $tabExt)) {
                        // Fabrication du répertoire d'accueil façon "Wordpress" (YYYY/MM)
                        $dateNow = new DateTime();
                        $sqlRepository = $dateNow->format('Y/m');
                        $repository = './uploads/images/' . $dateNow->format('Y/m');
                        if (!is_dir($repository)) {
                            mkdir($repository, 0777, true);
                        }
                        // Renommage du fichier (d'où l'intéret d'avoir isolé l'extension
                        $nomImage = md5(uniqid()) . '.' . $extension;

                        //Upload du fichier, voilà c'est fini !
                        move_uploaded_file($_FILES['Image']['tmp_name'], $repository . '/' . $nomImage);

                        // suppression ancienne image si existante
                        if ($_POST['imageAncienne'] != '' && $_POST['imageAncienne'] != '/' && file_exists("../../public/uploads/images/{$_POST["imageAncienne"]}")) {
                            unlink("./uploads/images/{$_POST['imageAncienne']}");
                        }
                    }
                }

                //On réutilise l'objet Creche créé au début de la méthode
                $date = new DateTime($_POST["DatePublication"]);
                $nursery->setNameNursery($_POST["Name"])
                    ->setDescription($_POST["Description"])
                    ->setDatePublication($date)
                    ->setTown($_POST["Town"])
                    ->setImageRepository($sqlRepository)
                    ->setImageFileName($nomImage);
                $result = $nursery->SqlUpdate();

                if ($result[0] == "1") {
                    if ($nomImage != null) {
                        unlink($repository . '/' . $nomImage);
                    }
                }

                header("Location:/Nursery/update/$id");
            } else {
                return $this->getTwig()->render('Nursery/update.html.twig', [
                    "Nursery" => $nursery
                ]);
            }

        } else {
            header("Location:/Nursery/all");

        }
    }

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