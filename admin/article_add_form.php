<?php require ("../inc/config.php"); ?>
<?php require ("../inc/header.php"); ?>
<h1>Ajouter u article - Partie Admin</h1>
<form action="article_show.php" method="post">
    <input type="text" name="titre">
    <textarea name="description"></textarea>
    <input type="date" name="datepublication">
    <select name="auteur">
        <option value="Samuel">Samuel</option>
        <option value="Killian">Killian</option>
        <option value="Noé">Noé</option>
        <option value="Dylan">Dylan</option>
        <option value="Thomas B">Thomas B</option>
    </select>
    <input type="submit">
</form>

<?php require ("../inc/footer.php"); ?>
