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
        $articles = Article::SqlGetAll();
        return $this->twig->render('Article/all.html.twig',[
            "articles" => $articles
        ]);
    }
}