<?php

  session_start();
 if ((isset($_SESSION['zalogowany']))&&($_SESSION['zalogowany']==true))
{
    header('Location: game.php');
    exit();
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>game</title>
</head>
<body>
    Tylko martwi ujrzeli koniec wojny - platon <br><br>
    
    <form action="signIn.php" method="post">
        login: 
        <br>
        <input type="text" name="login"><br>
        
        Password:
        <br>
        <input type="password" name="password"><br>
        <br>
        <input type="submit" name="send" value="send">
        
        
    </form>
    <?php
    if(isset($_SESSION['blad']))
        echo $_SESSION['blad'];

    ?>
</body>
</html>