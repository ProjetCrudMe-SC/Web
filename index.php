<?php require ("./inc/config.php"); ?>
<?php require ("./inc/header.php"); ?>
<h1>Bienvenue sur notre Blog</h1>
<p>
    <?php
        $phrase = "Bonjour nous allons pourquoi il est intéressant de programmer en Flutter pour les applications mobiles X-Platform";
        $extrait = premiersMots($phrase,10);
        echo $extrait;
    ?>
    <a href="#">Lire la suite ...</a>
</p>

<?php require ("./inc/footer.php"); ?>