<?php
require_once "../vendor/autoload.php";
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



$controller = (isset($_GET['controller'])) ? $_GET['controller'] : '';
$action = (isset($_GET['action'])) ? $_GET['action'] : '';
$param = (isset($_GET['param'])) ? $_GET['param'] : '';

if($controller != ''){
    try {
        $class = "src\Controller\\".$controller."Controller";
        if (class_exists($class)) {
            $controller = new $class();
            if (method_exists($class, $action)) {
                echo $controller->$action($param);
            }else { echo'Acion n\'existe pas pour ce controller !';}
        }else { echo 'Le controlleur n\'existe pas pour cette url !';}
    }
    catch(Exception $e) {
        // Penser à Gérer l’exception
    }
}else {
    //Route par défaut (/)
    $controller = new \src\Controller\ArticleController();
    echo $controller->index();
}


