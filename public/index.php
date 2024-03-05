<?php
ini_set('display_errors', 1);
session_start();
require_once "../vendor/autoload.php";
if (isset($_SERVER['HTTP_ORIGIN'])) {
    // should do a check here to match $_SERVER['HTTP_ORIGIN'] to a whitelist of safe domains
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');
}
// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
}


function chargerClasse($classe): void
{
    //$classe va ressembler à src\Model\Nursery
    $ds = DIRECTORY_SEPARATOR;
    // JE veux le document ROOT (c'est à dire www)
    $dir = __DIR__."$ds.."; // Je remonte d'un cran pour wwww

    //Remplacer les séparateur du namespace "src\Model\Nursery"
    $className = str_replace('\\', $ds, $classe);

    $file = "{$dir}{$ds}{$className}.php";
    if(is_readable($file)){
        require_once $file;
    }
}
spl_autoload_register("chargerClasse");

if(isset($_GET["url"])) {
    $urls = explode("/", $_GET["url"]);
} else {
    $urls = array();
}
$controller = $urls[0] ?? '';
$action = $urls[1] ?? '';
$param = $urls[2] ?? '';

if($controller !== ''){
    try {
        $class = "src\Controller\\".$controller."Controller";
        if (class_exists($class)) {
            $controller = new $class();
            if (method_exists($class, $action)) {
                echo $controller->$action($param);
            } else { echo'Action n\'existe pas pour ce controller !';}
         }else { echo 'Le controlleur n\'existe pas pour cette url !';}
    }
    catch(Exception $e) {
        // Penser à Gérer l’exception
        echo $e->getMessage();
    }
} else {
    //Route par défaut (/)
    $controller = new \src\Controller\NurseryController();
    echo $controller->index();
}