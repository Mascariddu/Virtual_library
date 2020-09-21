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
    $_SESSION["tutti"] = array();
    $_SESSION["stato"] = array();
    $_SESSION["giorni"] = array();
}

include("scripts.inc");
$_SESSION["tornato"] = 0;
$_SESSION["err"] = false;
$_SESSION["err2"] = false;
$_SESSION["ritorno"] = 0;
$_SESSION["esiste"] = false;
$_SESSION["giusto"] = false;
?>

<!doctype html>
<html lang = "IT" class = "animated fadeInLeft">
    
    <head>
        <title>REDIRECT</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
        <?php
            head();
        ?>
    </head>

    <body>
    <div class = 'grid-container'>

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

    <div class = "theName">
        <?php nome() ?>
    </div>

    <div class = "theBody">
    <?php

        if(isset($_REQUEST["reset"])){
            $_SESSION["reset"] = true;
            header("Location:Login.php");
            exit();
        } else{

        if(isset($_REQUEST["username"]) && isset($_REQUEST["psw"])){

            $userRegex = "/^([a-zA-Z%]{1})[\w%]{2,5}$/";
            $pwRegex = "/^[A-Za-z]{4,8}$/";
    
            if(preg_match($userRegex,$_REQUEST["username"]) && preg_match("/\d/",($_REQUEST["username"])) && preg_match("/[a-zA-Z%]/",($_REQUEST["username"])) ){
                if(preg_match($pwRegex,$_REQUEST["psw"]) && preg_match("/[a-z]/",($_REQUEST["psw"])) && preg_match("/[A-Z]/",($_REQUEST["psw"]))){
            
            $user = $_REQUEST["username"];
            $psw = $_REQUEST["psw"];
    
            error_reporting(0);
            $con = mysqli_connect("localhost","uReadOnly","posso_solo_leggere","biblioteca");
            if(mysqli_errno($con)){
                printf("<p>errore, impossibile connesione al DB %s</p>",mysqli_connect_error());
            } else{
                $query1 = "SELECT COUNT(*) FROM users WHERE username = ? AND pwd = ?";
                $stmt1 = mysqli_prepare($con, $query1);
                mysqli_stmt_bind_param($stmt1,"ss",$user,$psw);
                mysqli_stmt_execute($stmt1);
                mysqli_stmt_bind_result($stmt1,$res);
                mysqli_stmt_fetch($stmt1);

                if($res > 0){
                    mysqli_stmt_free_result($stmt1);
                    mysqli_stmt_close($stmt1);

                    $query1 = "SELECT COUNT(*) FROM books where prestito = ?";
                    $stmt1 = mysqli_prepare($con, $query1);
                    mysqli_stmt_bind_param($stmt1,"s",$user);
                    mysqli_stmt_execute($stmt1);
                    mysqli_stmt_bind_result($stmt1,$res);
                    mysqli_stmt_fetch($stmt1);

                    $_SESSION["errore"] = "";
                    $_SESSION["utente"] = $user;
                    $_SESSION["login"] = true;
                    $_SESSION["libri"] = $res;
                    setcookie("username",$_REQUEST["username"],time()+48*3600);
                    mysqli_stmt_free_result($stmt1);
                    mysqli_stmt_close($stmt1);
                    mysqli_close($con);
                    header("Location:Libri.php") ;
                    exit();
                } else {
                    mysqli_stmt_free_result($stmt1);
                    mysqli_stmt_close($stmt2);
                    mysqli_close($con);
                    $_SESSION["errore"] = "Username e/o password errati";
                    $_SESSION["libri"] = 0;
                    header("Location:Login.php");
                    exit();
                }
            }

        } else{
            $_SESSION["errore"] = "La password può contenere solo caratteri alfabetici. Deve essere lunga fra i 4 e gli 8 caratteri ed avere almeno una maiuscola ed una minuscola";
            header("Location:Login.php");
            exit();
        }
    } else{
            $_SESSION["errore"] = "Lo username può contenere solo caratteri alfanumerici e \"%\". Deve essere lungo fra i 3 e i 6 caratteri di cui almeno uno numerico e uno non numerico e iniziare con lettere o \"%\"!";
            header("Location:Login.php");
            exit();
    }
    } else{
        echo "<p class = 'errore'>Ops, si e' verificato un errore, torna alla <a href = 'Home.php'>home page</a></p>";
    }
    } 
    ?>
    </div>

    <div class = "theFooter">
    <footer>
        <?php
            footer();
        ?>
    </footer>
    </div>

    </body>
    <div>
</html>