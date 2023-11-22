<?php

$notes_apprenants = ["Mohamed" => "16", "Ahmed" => "14", "Rafika" => "13", "Aicha" => "15", "Samir" => "13"
    , "Samar" => "13", "Rafik" => "10", "Samiha" => "09", "Fourat" => "07", "Sami" => "07", "Noura" => "14"];

asort($notes_apprenants);

echo "<table>
        <thead>
        <tr>
            <td>Prénom</td>
            <td>Note</td>
        </tr>
        </thead>";
foreach ($notes_apprenants as $prenom => $note){
    echo "<tr>
            <td>{$prenom}</td>
            <td>{$note}</td>
        </tr>";
}
echo "</table>";