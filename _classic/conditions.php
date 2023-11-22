<?php
$bool = false;
$age = 9   ;
$ville = "Lille";
// IF Classique
if($bool){
    echo "<p>bool est à true</p>";
}else if($age >= 13 AND ($ville == 'PARIS' OR $ville == 'Lille')){
    echo "<p>Supérieur ou égal à 13 ans et habite en ville de Paris ou Lille</p>";
}else{
    echo ("<p>Rien de to10ut ça</p>");
}

$majeur = ($age >= 18) ? "Oui" : (($age <10) ? "Gamin" : "Ado");
var_dump($majeur);

$note = 18;
switch ($note)
{
    case 0:
        echo "Mauvais";
        break;
    case ($note >=5 && $note <= 10):
        echo "Rattrapage";
        break;
    case ($note >=18):
        echo "Bravo";
        break;
    default:
        echo "Désolé je n'ai pas de message pour toi";
}

