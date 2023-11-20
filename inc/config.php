<?php
/*
 * Dans le répertoire « inc » créer le fichier « config.php ». Dans ce fichier créer une fonction qui retourne les N premiers mots d’un paragraphe de texte (dans le cadre d’un blog = un extrait de l’article)
Ensuite intégrez ce fichier config.php dans /index.php dès la première ligne (ce fichier sera prioritaire sur le reste des opérations
Appelez cette fonction dans un paragraphe sur le fichier index.php (mettez une très grande phrase)
Aide : Sur Google tapez « php get first x words of string »

 */

function premiersMots($phrase, $n) {
    preg_match('/^(\S+\s+){0,' . ($n - 1) . '}\S+/', $phrase, $matches);
    $resultat = $matches[0];
    return $resultat;
}
