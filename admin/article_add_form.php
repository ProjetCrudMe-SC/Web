<?php require ("../inc/config.php"); ?>
<?php require ("../inc/header.php"); ?>
<h1>Ajouter un article - Partie Admin</h1>
<form action="article_add_script.php" method="post" enctype="multipart/form-data">
    <input type="text" name="titre">
    <textarea name="description"></textarea>
    <input type="date" name="datepublication">
    <select name="auteur">
        <option value="Fabien">Fabien</option>
        <option value="Brice">Brice</option>
        <option value="Bruno">Bruno</option>
        <option value="Benoit">Benoit</option>
    </select>
    <input type="file" name="Image">
    <input type="submit">
</form>
<?php require ("../inc/footer.php"); ?>
