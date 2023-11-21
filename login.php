<?php
require ("./inc/config.php");
require ("./inc/header.php");
?>
<h1>LOGIN<h1>
<form method="post" action="login_check.php">
    <input type="email" name="Email">
    <input type="password" name="Password">
    <input type="submit">
</form>

<?php require ("./inc/footer.php");