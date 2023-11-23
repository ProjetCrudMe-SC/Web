<?php
namespace src\Controller;

use src\Model\Article;

class ArticleController extends AbstractController {

    public  function index(){
        $articles = Article::SqlGetLast(20);
        return $this->getTwig()->render('Article/index.html.twig',[
            "articles" => $articles
        ]);
    }

    public function fixtures(): string{

        Article::SqlFixtures();
        return "<p>Fixtures ok </p>";
    }

    public function all(){
        $articles = Article::SqlGetAll();
        return $this->getTwig()->render('Article/all.html.twig',[
            "articles" => $articles
        ]);
    }

    public function delete(int $id){
        Article::SqlDelete($id);
        header("Location: /?controller=Article&action=all");
    }

    public function add(){
        if(isset($_POST["Titre"]) && isset($_POST["Description"])){
            $sqlRepository = null;
            $nomImage = null;


            if(!empty($_FILES["Image"]["name"])){
                $tabExt = ["jpg", "jpeg", "gif", "png"]; // Extension autorisée
                $extension = pathinfo($_FILES["Image"]["name"], PATHINFO_EXTENSION);
                if(in_array(strtolower($extension), $tabExt)){
                    //Fabriquer le répertoire d'auccil façon Wrodpress (YYYY/MM)
                    $dateNow = new \DateTime();
                    $repository = "./uploads/images/{$dateNow->format("Y/m")}";
                    if(!is_dir($repository)) {
                        mkdir($repository, 0777, true);
                    }
                    $sqlRepository = $dateNow->format("Y/m");
                    //Renommer le fichier image à la volée
                    $nomImage = md5(uniqid()).".".$extension;
                    var_dump($repository);
                    var_dump($nomImage);
                    die();
                    //Upload du fichier
                    move_uploaded_file($_FILES["Image"]["tmp_name"], $repository."/".$nomImage);
                }
            }

           $article = new Article();
            $article->setTitre($_POST["Titre"])
                ->setDescription($_POST["Description"])
                ->setDatePublication(new \DateTime($_POST["DatePublication"]))
                ->setAuteur($_POST["Auteur"])
                ->setImageRepository($sqlRepository)
                ->setImageFileName($nomImage);
            $result = $article->SqlAdd();


            header("Location: /?controller=Article&action=all");
        }
        return $this->getTwig()->render("Article/add.html.twig");
    }

    public function show(int $id){
        $article = Article::SqlGetById($id);
        if($article==null){
            header("Location: /?controller=Article&action=all");
        }
        return $this->getTwig()->render("Article/show.html.twig", [
            "article" => $article
        ]);
    }




}