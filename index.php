<?php require ("./inc/config.php"); ?>
<?php
if(isset($_POST["search"])){
    $requete = $bdd->prepare("SELECT * FROM articles WHERE Id = :IDARTICLE OR Titre like :TITREARTICLE");
    $requete->execute([
            "IDARTICLE" => $_POST["search"],
            "TITREARTICLE" => "%".$_POST["search"]."%"
    ]);
    $articles = $requete->fetch(PDO::FETCH_ASSOC);
}else{
    $requete = $bdd->query("SELECT * FROM articles");
    $articles = $requete->fetchAll(PDO::FETCH_ASSOC);
}
var_dump($articles);
?>
<?php require ("./inc/header.php"); ?>

<h1>Bienvenue sur notre Blog</h1>
<?php
$variableduchampinvisible = "123";
?>
<form name="recherche" method="post">
    <input placeholder="ID Sql" name="search" type="text">
    <input type="hidden" name="champInvisible" value="<?php echo $variableduchampinvisible; ?>">
</form>



<?php require ("./inc/footer.php"); ?>