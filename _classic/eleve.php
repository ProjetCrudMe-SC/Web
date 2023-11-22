<?php
var_dump($_GET);
if(isset($_GET["eleve"]) && isset($_GET["note"])){
    echo "bonjour {$_GET["eleve"]} ta note est {$_GET["note"]}";
}else{
    echo "il manque des paramètres dans l'url";
}

/*
 * Créer une page eleve.php qui affiche « Bonjour $eleve, ta note est $note » en récupérant les valeurs dans le $_GET
Vous devrez donc à ce moment là définir ce qui doit être passé dans l’url pour que ça coincide avec ce qu’attend $_GET. Par exemple si vous attendez $_GET["prenom"], l’url devra contenir « prenom=xxxxx » et non pas « firstname=xxxxx » ou non pus « nom=xxxxxx ».
Il est nécessaire que la clef doit être identique.

Pour chaque élément (foreach donc) créer un lien « Voir la note de xxxx (prénom de l’élève) » dont l’attribut href envoi vers eleve.php avec des éléments dans l’URL permettant à la page eleve.php de s’afficher correctement.

 */