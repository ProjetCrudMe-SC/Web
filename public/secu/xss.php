<form method="post">
    <input type="text" name="message"/>
</form>



    <?php
    if(isset($_POST["message"])){
        $value = $_POST["message"];
        echo "<p>Voici le message écrit par xxxx le 24/11/2023 : </p>";
        echo "<p>{$value}</p>";
        // ^ : commence strictement par
        // $ : Se termine strictement par
        if(preg_match("#([on]{1})([on]{1})([on]{1})#", $value)){
            echo "VRAI";
        }
    }
    ?>





