<?php

    session_start();

    require_once "connect.php";
   $sql = @new mysqli($host, $db_user, $db_password, $db_name);


    if ($sql->connect_errno!=0)
    {
       echo "Error".$sql->connect_errno;
    }
    else{
         $login = $_POST['login'];
         $password = $_POST['password'];
        
        $askSql = ("SELECT * FROM uzytkownicy WHERE user='$login' AND pass='$password'");
        if ($result = $sql->query($askSql))
        {
            
            $many_user = $result->num_rows;
            if($many_user>0){

                $_SESSION['zalogowany']=true;

                $row = $result->fetch_assoc();
                $_SESSION['id']=$row['id'];
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
            
        }
    
         $sql->close();
    }




//echo $login. "<br>";
//echo $password;
 
?>