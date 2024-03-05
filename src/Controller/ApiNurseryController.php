<?php
namespace src\Controller;

use src\Model\Nursery;
use src\Service\JwtService;

class ApiNurseryController{

    public function __construct(){
        header('Content-Type: application/json; charset=utf-8');
    }

    //GET ALL
    public function getAll(): false|string
    {
        if($_SERVER["REQUEST_METHOD"] != "GET"){
                header("HTTP/1.1 404 Not Found");
            return json_encode("Erreur de méthode (GET attendu)");
        }

        $result = JwtService::checkToken();
        if($result["code"] == 1){
            return json_encode($result);
        }

        $nursery = Nursery::SqlGetAll();
        return json_encode($nursery);
    }

    public function add(): false|string
    {
        if($_SERVER["REQUEST_METHOD"] != "POST"){
            header("HTTP/1.1 404 Not Found");
            return json_encode("Erreur de méthode (POST attendu)");
        }

        if(!isset($_POST["Titre"]) || !isset($_POST["Description"])){
            header("HTTP/1.1 404 Not Found");
            return json_encode("Erreur il manque des données)");
        }

        $article = new Nursery();
        $article->setNameNursery($_POST["Titre"])
            ->setDescription($_POST["Description"])
            ->setDatePublication(new \DateTime($_POST["DatePublication"]))
            ->setTown($_POST["Town"]);
        $result = $article->SqlAdd();
        return json_encode($result);
    }

    public function addBis(){
        if($_SERVER["REQUEST_METHOD"] != "POST"){
            header("HTTP/1.1 404 Not Found");
            return json_encode("Erreur de méthode (POST attendu)");
        }

        // Takes raw data from the request
        $json = file_get_contents('php://input');
        // Converts it into a PHP object
        $data = json_decode($json);

        if(!isset($data->Titre) || !isset($data->Description)){
            header("HTTP/1.1 404 Not Found");
            return json_encode("Erreur il manque des données)");
        }



        $nursery = new Nursery();
        $nursery->setNameNursery($data->Titre)
            ->setDescription($data->Description)
            ->setDatePublication(new \DateTime($data->DatePublication))
            ->setTown($data->Town);
        $result = $nursery->SqlAdd();
        return json_encode($result);
    }
}