<?php require ("./inc/config.php"); ?>
<?php require ("./inc/header.php"); ?>
<?php
var_dump($_GET);
?>
<h1>Bienvenue sur notre Blog</h1>
<?php
$variableduchampinvisible = "123";
var_dump($_POST);
?>
<form name="recherche" method="post">
    <input placeholder="ID Sql" name="search" type="text">
    <input type="hidden" name="champInvisible" value="<?php echo $variableduchampinvisible; ?>">
</form>

<form name="recherche" method="post" action="submitform.php">
    <input type="text" name="prenom">
    <input type="text" name="nom">
    <input type="date" name="date">
    <input type="submit">
</form>

<?php require ("./inc/footer.php"); ?>