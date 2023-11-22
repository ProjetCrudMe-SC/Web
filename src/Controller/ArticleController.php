<?php
namespace src\Controller;

use src\Model\Article;

class ArticleController extends AbstractController {

    public  function index(){
        $articles = Article::SqlGetLast(20);
        return $this->twig->render('Article/index.html.twig',[
            "articles" => $articles
        ]);
    }

    public function fixtures(): string{

        Article::SqlFixtures();
        return "<p>Fixtures ok </p>";
    }

    public function all(){
        //Todo retourner tous les articles !
        // Modifier le Modèle
        // Créer la vue
        // Controller récupère les données, les trasnmets à la vue
        // url = ?controller=Article&action=all
    }
}