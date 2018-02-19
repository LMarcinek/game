<?php

session_start();
if ((!isset($_SESSION['successregister'])))
{
    header('Location: index.php');
    exit();
}else{

    unset($_SESSION['successregister']);

}


?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    dziękujęmy za rejestracje w serwisie !
    <a href="index.php">zaloguj sie na stronie głownej !</a>
</body>
</html>