<?php
function chargerClasse($classe){
    //$classe va ressembler à src\Model\Article
    $ds = DIRECTORY_SEPARATOR;
    // JE veux le document ROOT (c'est à dire www)
    $dir = __DIR__."$ds.."; // Je remonte d'un cran pour wwww

    //Remplacer les séparateur du namespace "src\Model\Article"
    $className = str_replace('\\', $ds, $classe);

    $file = "{$dir}{$ds}{$className}.php";
    if(is_readable($file)){
        require_once $file;
    }
}
spl_autoload_register("chargerClasse");




$controller = new src\Controller\ArticleController();
echo $controller->index();



