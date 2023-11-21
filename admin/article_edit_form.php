<?php
    require ("../inc/config.php");
    $requete = $bdd->prepare("SELECT * FROM articles WHERE Id = :Id");
    $requete->execute([
            "Id" => $_GET["articleId"]
    ]);
    $article = $requete->fetch(PDO::FETCH_ASSOC);
?>
<?php require ("../inc/header.php"); ?>
<h1>Modifier un article - Partie Admin</h1>
<form action="article_edit_script.php" method="post">
    <input type="hidden" name="articleId" value="<?php echo $article["Id"]?>">
    <input type="text" name="titre" value="<?php echo $article["Titre"] ?>">
    <textarea name="description">
        <?php echo $article["Description"] ?>
    </textarea>
    <input type="date" name="datepublication" value="<?php echo $article["DatePublication"] ?>">
    <select name="auteur">
        <option value="Fabien" <?php echo ($article["Auteur"] == "Fabien") ? "selected" : "" ?>>Fabien</option>
        <option value="Brice"<?php echo ($article["Auteur"] == "Brice") ? "selected" : "" ?>>Brice</option>
        <option value="Bruno"<?php echo ($article["Auteur"] == "Bruno") ? "selected" : "" ?>>Bruno</option>
        <option value="Benoit"<?php echo ($article["Auteur"] == "Benoit") ? "selected" : "" ?>>Benoit</option>
    </select>
    <input type="submit">
</form>
<?php require ("../inc/footer.php"); ?>
