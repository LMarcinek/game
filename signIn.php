<?php

    session_start();

    if ((!isset($_POST['login']))||(!isset($_POST['password'])))
    {
         header('Location: index.php');
         exit();
    }


    require_once "connect.php";
   $sql = @new mysqli($host, $db_user, $db_password, $db_name);


    if ($sql->connect_errno!=0)
    {
       echo "Error".$sql->connect_errno;
    }
    else {
        $login = $_POST['login'];
        $password = $_POST['password'];
        $login = htmlentities($login, ENT_QUOTES, "UTF-8");



        if ($result = $sql->query(sprintf("SELECT * FROM uzytkownicy WHERE user='%s'",
            mysqli_real_escape_string($sql, $login))))
        {

            $many_user = $result->num_rows;

            if($many_user>0){
                $row = $result->fetch_assoc();
                if(password_verify($password,$row['pass'])) {


                    $_SESSION['zalogowany'] = true;
                    $_SESSION['id'] = $row['id'];
                    $_SESSION['user'] = $row['user'];
                    $_SESSION['drewno'] = $row['drewno'];
                    $_SESSION['kamien'] = $row['kamien'];
                    $_SESSION['zboze'] = $row['zboze'];
                    $_SESSION['email'] = $row['email'];
                    $_SESSION['dnipremium'] = $row['dnipremium'];

                    unset($_SESSION['blad']);
                    $result->free_result();
                    header('Location: game.php');


                }else{

                    $_SESSION['blad']= '<span style="color:red"> nieprawdidłowy login lub hasło!</span>';
                    header('Location: index.php');



                    }

                }else{

                $_SESSION['blad']= '<span style="color:red"> nieprawdidłowy login lub hasło!</span>';
                header('Location: index.php');
                
                
                
                    }
            
        }
    
         $sql->close();
}




//echo $login. "<br>";
//echo $password;
 
?>