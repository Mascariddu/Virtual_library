<?php

$session = true;

if(session_status() === PHP_SESSION_DISABLED)
        $session = false;
else if(session_status() !== PHP_SESSION_ACTIVE){

    session_start();
    if(!isset($_SESSION["errore"]))
        $_SESSION["errore"] = "";
    if(!isset($_SESSION["utente"]))
        $_SESSION["utente"] = "Anonimo";
    if(!isset($_SESSION["login"]))
        $_SESSION["login"] = false;
    if(!isset($_SESSION["libri"]))
        $_SESSION["libri"] = 0;
    if(!isset($_SESSION["idlibro"])){
        $_SESSION["idlibro"] = 0;
    }
    if(!isset($_SESSION["reset"])){
        $_SESSION["reset"] = false;
    }
    if(!isset($_SESSION["err"])){
        $_SESSION["err"] = false;
    }

    $_SESSION["tutti"] = array();
    $_SESSION["stato"] = array();
    $_SESSION["giorni"] = array();
    
}

include("scripts.inc");
$_SESSION["refresh"] = 0;
$_SESSION["tornato"] = 0;
$_SESSION["err2"] = false;
$_SESSION["ritorno"] = 0;
$_SESSION["esiste"] = false;
$_SESSION["giusto"] = false;
?>

<!doctype html>
<html lang = "IT" class = "animated fadeInLeft">

    <head>
        <title>LOGIN</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
        <script type = "text/javascript" src = "function.js"></script>
        <?php
            head();
        ?>
    </head>
        
    <body>
    <div class= "grid-container">

    <div class = "theHeader">
    <header>
    <?php
            header2();
        ?>
    </header>
    </div>
    
    <div class = "theName">
        <?php nome() ?>
    </div>


    <div class = "theMenu">
        <?php
            menu();
        ?>
    </div>

    <?php
    if($_SESSION["err"] == false){
        if($_SESSION["login"] == false){
    ?>
    <div class = "theBody">
    <p><img class = "log" src= "./img/utente.png"></p>
    <form name = "f" action = "Redirect.php" method = "POST">
        <p>Username: <input class = "text" type="text" id = "user" name="username" value=<?php 
                                                            if(isset($_COOKIE["username"])){
                                                                if($_SESSION["reset"] == false)
                                                                    echo $_COOKIE["username"];
                                                                else echo "";
	                                                        }else
                                                                echo "";
		                                                      ?> ></p>
        <p>Password:  <input class = "text" type = "password" name = "psw" id = "psw"></p>
        <p class="errore3"><?php echo $_SESSION["errore"];
        $_SESSION["errore"] = "";?></p>
        <p><input class = "button" type = "submit" id = "invio" value = "OK" onclick = 'return check()'>
        <input class = "button" type = "submit" name = "reset" value = "PULISCI"></p>
        <p>Non sei ancora registrato? : <a class = "a" href = "New.php">Registrati</a></p>
                                                        </form>
    </div>
    <?php
        $_SESSION["err"] = false;
        } else {
                $page = $_SERVER['HTTP_REFERER'];
                if($page !== null){
                header("Location:$page");
                exit();
                $_SESSION["err"] = false;
                } else {
                    $_SESSION["err"] = true;
                    header("Location:Login.php");
                    exit();
                }
            }
        } else {
            header("Location:Home.php");
            exit();
        }
    ?>

    <div class = "theFooter">
    <footer>
        <?php
            $_SESSION["reset"] = false;
            footer();
        ?>
    </footer>
    </div>

    </div>
    </body>
</html>