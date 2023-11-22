<?php require ("./inc/config.php"); ?>
<?php
require ("./objet/Article.php");
use objet\Article;
?>
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

<?php
$article = new Article();
$article->setTitre("Mon titre");
$article->setDescription("dsjiofsd lfshuqldsqluhsqjkhfsqkjlh   hefh qulfqlf hdkqsh jk u fd uksdqkjsdq kljfs lkj lklk  jkhf lkq ljkdq leq kq kjk kfe kulfd");
var_dump($article);
$article2 = new Article();
$article2->setTitre("Mon titre2");
var_dump($article2);
$article3 = $article;
$article3->setTitre("Mon titre 3");
var_dump($article->strlen());

$article->setAuteur("Brice")->setDatePublication(new \DateTime());
$sql = $article->SqlAdd($bdd);
var_dump($sql);

?>









<?php require ("./inc/footer.php"); ?>