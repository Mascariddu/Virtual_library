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
    $_SESSION["tutti"] = array();
    $_SESSION["stato"] = array();
    $_SESSION["giorni"] = array();
}
include("scripts.inc");
$_SESSION["refresh"] = 0;
$_SESSION["err"] = false;
$_SESSION["tornato"] = 0;
$_SESSION["err2"] = false;
$_SESSION["ritorno"] = 0;
$_SESSION["esiste"] = false;
$_SESSION["giusto"] = false;
?>

<!doctype html>
<html lang = "IT" class = "animated fadeInLeft">
    
    <head>
        <title>NEW</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
        <script type = "text/javascript" src = "function.js"></script>
        <?php
            head();
        ?>
    </head>

    <body>

    <div class = "grid-container">
    
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

    <div class = "theBody">
    <p><img  class = "log" src="./img/newuser.png"></p>
        <form name = "f" action = "conferma.php" method = "POST" onsubmit = "return check2()">
        <p>Inserisci username: <input class = "text" type="text" id = "user" name="username"></p>
        <p>Inserisci password:  <input class = "text" type = "password" name = "psw" id = "psw"></p>
        <p>Ripeti la password: <input class = "text" type = "password" name = "psw2" id = "psw2"></p>
        <p><input class = "button" type = "submit" id = "invia" value = "REGISTRAMI"></p>
        </form>
    </div>

    <div class = "theFooter">
    <footer>
        <?php
            footer();
        ?>
    </footer>
    </div>
    </div>
    </body>
</html>