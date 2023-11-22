<?php
namespace src\Controller;

use src\Model\Article;

class ArticleController{

    public  function index(){
        $articles = Article::SqlGetLast(20);
        $html = '<h1>Bonjour voici la liste des 20 derniers articles</h1>';
        foreach ($articles as $article){
            $html.="<p>{$article->getTitre()}</p>";
        }
        return $html;
    }

    public function fixtures(): string{
        //copier coller le contenu de fixtures.php
        // et l'adapter ici (connexion à la BDD)
        // Puis l'appeler depuis le index.php

        // Pour ajouter un article pensez à utiliser
        // la méthode SqlAdd (du model Article)
        Article::SqlFixtures();
        return "<p>Fixtures ok </p>";
    }

}