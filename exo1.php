<?php
echo "hello";
//Consigne 1 : Tableau avec 5 fruits et légumes
$fruitsLegumes = ["Pommes", "Salade", "Carotte", "Fraise", "Kiwi"];

//Consigne 2 : AJouter un fruit au tableau existant
$fruitsLegumes[] = "Framboises";
var_dump($fruitsLegumes);

//Consigne 3 : Supprimer un fruit ou légume du tableau
unset($fruitsLegumes[2]);
var_dump($fruitsLegumes);

// Afficher en HTML sous forme de liste
echo "<ul>";
    echo "<li>{$fruitsLegumes[0]}</li>";
    echo "<li>{$fruitsLegumes[1]}</li>";
    echo "<li>{$fruitsLegumes[3]}</li>";
    echo "<li>{$fruitsLegumes[4]}</li>";
    echo "<li>{$fruitsLegumes[5]}</li>";
echo "</ul>";


