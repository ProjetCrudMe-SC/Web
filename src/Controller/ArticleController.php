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

}