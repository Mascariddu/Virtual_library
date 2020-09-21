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
$_SESSION["visit"] = 0;
$_SESSION["err"] = false;
$_SESSION["tornato"] = 0;
$_SESSION["ritorno"] = 0;
$_SESSION["esiste"] = false;
$_SESSION["giusto"] = false;
?>
<!doctype html>
<html lang = "IT" class = "animated fadeInLeft">
    
    <head>
        <title>HOME</title>
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

        <?php if($_SESSION["login"] == true){
            echo "<p class='bo'>Bentornato ".$_SESSION["utente"]."!</p>";
            echo "<p>Sei libero/a di navigare sulla nostra piattaforma per dilettarti nella lettura </p>";
            echo "<p>E ricorda: </p>";
            }else {
                echo "<p class='bo'>Benvenuto/a in Crazy Books, la prima ed unica biblioteca virtuale!</p>";
                echo "<p>Se già fai parte della nostra famiglia <a  class = 'a' href='Login.php'>loggati</a> ora!</p>";
                echo "<p>Se invece sei un nuovo utente <a class = 'a' href='NEW.php'>registrati</a>!</p>";
            }
        ?>

        <blockquote>
        <p class = "citazione">Chi non legge, a 70 anni avrà vissuto una sola vita: la propria! Chi legge avrà vissuto 5000 anni: c’era quando Caino uccise Abele, quando Renzo sposò Lucia, quando Leopardi ammirava l’infinito… perché la lettura è una immortalità all’indietro
            (Umberto Eco)</p>
        </blockquote>

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