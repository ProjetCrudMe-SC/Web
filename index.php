<?php require ("./inc/config.php"); ?>
<?php require ("./inc/header.php"); ?>
<?php
var_dump($_GET);
?>
<h1>Bienvenue sur notre Blog</h1>
<p>
    <?php
        $phrase = "Bonjour nous allons pourquoi il est intéressant de programmer en Flutter pour les applications mobiles X-Platform";
        $extrait = premiersMots($phrase,10);
        echo $extrait;
    ?>
    <a href="#">Lire la suite ...</a>
</p>

<?php
$prenomsNote = [
    "Brice" => "C",
    "Julie" => "B",
    "Aegir" => "D",
    "Emilie" => "A"
];
foreach ($prenomsNote as $eleve => $note) {
    // Faire le lien a.href vers eleve.php
    echo "<p><a href='eleve.php?eleve=$eleve&note=$note'>Voir la note de $eleve</a></p>";
}
?>

<?php require ("./inc/footer.php"); ?>