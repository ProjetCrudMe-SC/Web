<?php require ("../inc/config.php"); ?>
<?php require ("../inc/header.php"); ?>
<h1><?php echo $_POST["titre"] ?></h1>
<p><strong>Description :</strong><?php echo $_POST["description"] ?></p>
<p><strong>Date :</strong><?php echo $_POST["datepublication"] ?></p>
<p><strong>Auteur :</strong><?php echo $_POST["auteur"] ?></p>
<?php require ("../inc/footer.php"); ?>