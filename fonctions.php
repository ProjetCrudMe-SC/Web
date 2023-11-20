<?php
function parler(string $prenom, $age) : string{
    //$prenom = "Bruno";
    $phrase = "Bonjour $prenom, comment ça va à $age ans?";
    return $phrase;
}

echo parler(age: 12, prenom: "Toto");

/*
 * Faire une fonction qui converti les °C en °F

Pour convertir en degrés Celsius une température donnée en degrés Fahrenheit, il suffit de soustraire 32 et de diviser par 1,8 (9/5 = 1,8) le nombre ainsi obtenu. Pour 50 °F , on obtient : 50 − 32 = 18, puis 18/1,8 = 10 ; donc 50 °F = 10 °C .

Ensuite adapter la fonction pour qu’elle puisse convertir un degré (Fahrenheit, Celsius) en un autre (Fahrenheit, Celsius). Attention vous devez mettre en place vos talents de programmeur pour créer une fonction dont la signature (paramètres d’entrés et type de sortie) ne puisse plus bouger dans le temps. De telle sorte que plus tard on puisse ajouter les « Kelvin » dans le système

 */