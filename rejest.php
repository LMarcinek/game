<?php

    session_start();
    if(isset($_POST['email'])){
        // success
        $ok =true;
        //nickname
        $nick =$_POST['nick'];

        if((strlen($nick)<3) || (strlen($nick)>20)){
            $ok=false;
            $_SESSION['e_nick']="nick has to have 3 to 20 letters";
        }

        if (ctype_alnum($nick)==false){
            $ok=false;
            $_SESSION['e_nick']="Nick może składać sie tylko z liter i cyfr(bez znaków specialnych)";
        }

        // chech email
        $email = $_POST['email'];
        $emailB = filter_var($email, FILTER_SANITIZE_EMAIL);

        if ((filter_var($emailB, FILTER_VALIDATE_EMAIL)==false)||($emailB!=$email))
        {
            $ok=false;
            $_SESSION['e_email']="Podaj poprawny adres email.";
        }

        // check lenght  password
        $password1=$_POST['password'];
        $password2=$_POST['password2'];

        if((strlen($password1)<8)|| (strlen($password1)>20))
        {
            $ok=false;
            $_SESSION['e_password']="hasło musi zawierac od 8 do 20 znakow";
        }
        if($password1!=$password2){
            $ok=false;
            $_SESSION['e_password']="haslo musi byc takie same";
        }

        $password_hash=password_hash($password1, PASSWORD_DEFAULT);
        // if accpet

        if (!isset($_POST['check'])){
            $ok=false;
            $_SESSION['e_check']="potweirdz akctepacje regulamianu";
        }

        //bot or not
        $secret ="6LcuCkcUAAAAAAFAS56a0kfIQTidtAqFkWxmq5HC";
        $check = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
        $response =json_decode($check);

        if ($response->success==false) {
            $ok=false;
            $_SESSION['e_bot']="potweirdz ze nie jestes botem";
        }

        require_once 'connect.php';

        mysqli_report(MYSQLI_REPORT_STRICT);
        try
        {
            $sql = new mysqli($host, $db_user, $db_password, $db_name);
            if ($sql->connect_errno!=0)
            {
                throw new Exception(mysqli_connect_errno());
            }
            else
            {
                // if email is this in db
                 $result = $sql->query("SELECT id FROM uzytkownicy WHERE email='$email'");

                 if (!$result) throw new Exception($sql->eroor);

                 $many_this_mail = $result->num_rows;
                 if ($many_this_mail>0){
                     $ok=false;
                     $_SESSION['e_email']="istanieje juz taki email ";
                 }
                // if nick is this in db
                $result = $sql->query("SELECT id FROM uzytkownicy WHERE user='$nick'");

                if (!$result) throw new Exception($sql->eroor);

                $many_this_nick = $result->num_rows;
                if ($many_this_nick>0){
                    $ok=false;
                    $_SESSION['e_nick']="istnieje juz gracz o takim nicku! Wybierz inny ";
                }

                if ($ok==true){
                    //everthing is ok. we can add to mysql
                  if ($sql->query("INSERT INTO uzytkownicy VALUES(NULL, '$nick', '$password_hash', '$email', 100, 100,100, 14)"
                  ))
                  {

                      $_SESSION['successregister']= true;
                      header('Location: welcome.php');

                  }else{

                      throw new Exception($sql->eroor);

                  }
                }


                $sql->close();
            }



        }
        catch (Exception $e)
        {
            echo '<span style="color:red;">blad serwera przperaszamy za niedogodnisci</span>';
            echo '<br>informacja developerska: '.$e;
        }







    }

?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>załózł darowy konot </title>
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <style>
        .mistake{
            color:red;
            margin-top: 10px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    <form action="" method="post">
        Nickname:<br> <input type="text" name="nick"><br>
        <?php
        if (isset($_SESSION['e_nick'])){
            echo'<div class="mistake">'.$_SESSION['e_nick'].'</div>';
            unset($_SESSION['e_nick']);
        }
        ?>
        email :<br> <input type="email" name="email"><br>
        <?php
        if (isset($_SESSION['e_email'])){
            echo'<div class="mistake">'.$_SESSION['e_email'].'</div>';
            unset($_SESSION['e_email']);
        }
        ?>

        Password:<br> <input type="password" name="password"><br>
        <?php
        if (isset($_SESSION['e_password'])){
            echo'<div class="mistake">'.$_SESSION['e_password'].'</div>';
            unset($_SESSION['e_password']);
        }
        ?>


        Reapet password:<br> <input type="password" name="password2"><br>
        <br> <label><input type="checkbox" name="check"> Akcept <br></label>
        <?php
        if (isset($_SESSION['e_check'])){
            echo'<div class="mistake">'.$_SESSION['e_check'].'</div>';
            unset($_SESSION['e_check']);
        }
        ?>

        <div class="g-recaptcha" data-sitekey="6LcuCkcUAAAAAK1o3MJYHSMRDilqWnOdJr0GWl7Z"></div>
        <?php
        if (isset($_SESSION['e_bot'])){
            echo'<div class="mistake">'.$_SESSION['e_bot'].'</div>';
            unset($_SESSION['e_bot']);
        }
        ?>
        <input type="submit" value="register!">
    </form>



</body>
</html>