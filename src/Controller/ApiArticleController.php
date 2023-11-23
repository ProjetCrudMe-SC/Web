<?php
namespace src\Controller;

use src\Model\Article;

class ApiArticleController{

    public function __construct(){
        header('Content-Type: application/json; charset=utf-8');
    }

    //GET ALL
    public function getAll(){
        if($_SERVER["REQUEST_METHOD"] != "GET"){
                header("HTTP/1.1 404 Not Found");
            return json_encode("Erreur de méthode (GET attendu)");
        }

        $articles = Article::SqlGetAll();
        return json_encode($articles);
    }

    public function add(){
        if($_SERVER["REQUEST_METHOD"] != "POST"){
            header("HTTP/1.1 404 Not Found");
            return json_encode("Erreur de méthode (POST attendu)");
        }

        if(!isset($_POST["Titre"]) || !isset($_POST["Description"])){
            header("HTTP/1.1 404 Not Found");
            return json_encode("Erreur il manque des données)");
        }

        $article = new Article();
        $article->setTitre($_POST["Titre"])
            ->setDescription($_POST["Description"])
            ->setDatePublication(new \DateTime($_POST["DatePublication"]))
            ->setAuteur($_POST["Auteur"]);
        $result = $article->SqlAdd();
        return json_encode($result);
    }
}










