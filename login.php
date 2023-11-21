<?php
session_start();
require ("./inc/config.php");
require ("./inc/header.php");
?>
<h1>LOGIN<h1>
<?php
 if(isset($_SESSION["MSG"])){
     if($_SESSION["MSG"]["Type"] == "Error"){
         echo "<p style='color: red'> ERROR : {$_SESSION["MSG"]["Message"]}</p>";
     }
     unset($_SESSION["MSG"]);
 }
?>
<form method="post" action="login_check.php">
    <input type="email" name="Email">
    <input type="password" name="Password">
    <input type="submit">
</form>

<?php require ("./inc/footer.php");