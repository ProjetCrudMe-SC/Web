<?php
 $a = false;
$b = 12;
$c = 12.5;
$chaine = "Ceci est un String";
 var_dump($chaine);
 echo $chaine;
 // Comment
/*
 * plusieurs
 * ligne de
 * commentaire
 */

$var = "Ma phrase";
echo "$var <br/>";
echo '$var <br/>';
echo "la valeur de var = {$var}";

$prenom1 = "Emmanuel";
$prenom2 = "Sylvie";

echo "<p>Bonjour {$prenom1}, pense à appeler {$prenom2}</p>";

// Déclaration Tableau
$arrayHomme = array("Brice", "Benoit", "Romain", "Clément");
$arrayFemme = ["Marion", "Sylvie", "Laurianne", "Kelly"];

$arrayHomme[] = "Xavier";
var_dump($arrayHomme);
echo "$arrayHomme[1] a été embauché";