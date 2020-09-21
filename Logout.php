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
    if(!isset($_SESSION["tornato"])){
        $_SESSION["tornato"] = 0;
    }
    if(!isset($_SESSION["err2"])){
        $_SESSION["err2"] = false;
    }

    $_SESSION["tutti"] = array();
    $_SESSION["stato"] = array();
    $_SESSION["giorni"] = array();

}

include("scripts.inc");
$_SESSION["tornato"]++;
$_SESSION["err"] = false;
$_SESSION["ritorno"] = 0;
$_SESSION["esiste"] = false;
$_SESSION["giusto"] = false;
?>
<!doctype html>
<html lang = "IT" class = "animated fadeInLeft">
    
    <head>
        <title>LOGOUT</title>
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

    <div class = "theMenu">
        <?php
            menu();
        ?>
    </div>

    <div class = "theBody">
        <?php
        if($_SESSION["err2"] == false){
        if($_SESSION["login"] == true){
            $_SESSION["utente"] = "Anonimo";
            $_SESSION["login"] = false;
            $_SESSION["libri"] = 0;
            echo "<p>Logout effettuato, ora puoi continuare a navigare in anonimo</p>";
            echo "<p><img class = 'logout' src='./img/download.png'></p>";
            echo "<p> Torna alla <a class = 'a' href='Home.php'>home</a></p>";
            $_SESSION["err2"] = false;
        } else {
            if($_SESSION["tornato"] <= 1){
                $page = $_SERVER['HTTP_REFERER'];
                if($page !== null){
                    $_SESSION["err2"] = false;
                    header("Location:$page");
                    exit();
                } else {
                    $_SESSION["err2"] = true;
                    header("Location:Logout.php");
                    exit();
                }
            } else {
                $_SESSION["err2"] = false;
                header("Location:Home.php");
                exit();
            }
        }
    }else {
        header("Location:Home.php");
        exit();
    }
        ?>
    </div>

    <div class = "theName">
        <?php nome() ?>
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