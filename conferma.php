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
    if(!isset($_SESSION["refresh"])){
        $_SESSION["refresh"] = 0;
    }
    if(!isset($_SESSION["esiste"])){
        $_SESSION["esiste"] = false;
    }
    if(!isset($_SESSION["last"])){
        $_SESSION["last"] = "";
    }

    $_SESSION["tutti"] = array();
    $_SESSION["stato"] = array();
    $_SESSION["giorni"] = array();
}
include("scripts.inc");
$_SESSION["refresh"]++;;
$_SESSION["tornato"] = 0;
$_SESSION["err"] = false;
$_SESSION["err2"] = false;
$_SESSION["ritorno"] = 0;
$_SESSION["giusto"] = false;
?>
<!doctype html>
<html lang = "IT" class = "animated fadeInLeft">
    
    <head>
        <title>CONFERMA</title>
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
    
    <div class= "theMenu">
        <?php
            menu();
        ?>
    </div>

    <div class = "theBody">

    <?php
        if(isset($_REQUEST["username"]) && isset($_REQUEST["psw"]) && isset($_REQUEST["psw2"])){
            if($_SESSION["refresh"] <= 1){

        $userRegex = "/^([a-zA-Z%]{1})[\w%]{2,5}$/";
        $pwRegex = "/^[A-Za-z]{4,8}$/";

        if(preg_match($userRegex,$_REQUEST["username"]) && preg_match("/\d/",($_REQUEST["username"])) && preg_match("/[a-zA-Z%]/",($_REQUEST["username"])) ){
            if(preg_match($pwRegex,$_REQUEST["psw"]) && preg_match("/[a-z]/",($_REQUEST["psw"])) && preg_match("/[A-Z]/",($_REQUEST["psw"]))){
                if($_REQUEST["psw"] === $_REQUEST["psw2"]){
        
        $user = $_REQUEST["username"];
        $psw = $_REQUEST["psw"];
        $_SESSION["last"] = $user;

        error_reporting(0);
        $con = mysqli_connect("localhost","uReadWrite","SuperPippo!!!","biblioteca");
        if(mysqli_connect_errno()){
            printf("<p>errore, impossibile connesione al DB %s</p>",mysqli_connect_error());
        } else{

            $query1 = "SELECT COUNT(*) as tot FROM users WHERE username = ?";
            $stmt1 = mysqli_prepare($con, $query1);
            mysqli_stmt_bind_param($stmt1,"s",$user);
            mysqli_stmt_execute($stmt1);
            mysqli_stmt_bind_result($stmt1,$res);
            mysqli_stmt_fetch($stmt1);
            //mysqli_fetch($stmt1);

            if($res > 0){
                $_SESSION["esiste"] = true;
                echo "<p class = 'error'>Errore: username già esistente, torna alla <a class = 'a'  href = 'NEW.php'>registrazione!</a></p>";
            } else {
              
            mysqli_stmt_free_result($stmt1);
            mysqli_stmt_close($stmt1);

            $query = "INSERT INTO `users`(`username`, `pwd`) VALUES (?,?)";
            $stmt2 = mysqli_prepare($con, $query);
            mysqli_stmt_bind_param($stmt2, "ss", $user, $psw);
            $res2 = mysqli_stmt_execute($stmt2);
            if($res2 == null){
                printf("<p>Errore apertura DB %s</p>",mysqli_connect_error($con));
            }else{
                echo "<p class = 'totti'>Registrazione andata a buon fine!</p>";
                if($_SESSION["login"] == false)
                    echo "<p>Effettua subito il <a class = 'a' href='Login.php'>login</a>!</p>";
                else echo "<p>Torna a <a class = 'a' href='Libri.php'>libri</a>!</p>";
                mysqli_stmt_free_result($stmt2);
                mysqli_stmt_close($stmt2);
                mysqli_close($con);
            }
        }
        
    }
        }else {
            echo "<p class = 'error'>Le password non combaciano!</p> <p>Ritorna alla <a class = 'a'  href = 'NEW.php'>registrazione</a></p>";
            $_SESSION["refresh"] = 0;
        }
        }else {
            echo "<p class = 'error'>La password dell’utente puo contenere solo caratteri alfabetici, deve essere lunga minimo quattro e massimo` otto caratteri ed avere almeno un carattere minuscolo ed uno maiuscolo.</p> <p>Ritorna alla <a class = 'a'  href = 'NEW.php'>registrazione</a></p>";
            $_SESSION["refresh"] = 0;
        } 
    }else {
             echo "<p class = 'error'>Lo username dell’utente puo contenere solo caratteri alfabetici o numerici o il simbolo %, deve iniziare con` un carattere alfabetico o con %, deve essere lungo da un minimo di tre ad un massimo di sei caratteri e deve contenere almeno un carattere non numerico ed uno numerico.</p> <p>Ritorna alla <a class = 'a'  href = 'NEW.php'>registrazione</a></p>";
             $_SESSION["refresh"] = 0;
        }
    }else {
        $userRegex = "/^([a-zA-Z%]{1})[\w%]{2,5}$/";
        $pwRegex = "/^[A-Za-z]{4,8}$/";

        if(preg_match($userRegex,$_REQUEST["username"]) && preg_match("/\d/",($_REQUEST["username"])) && preg_match("/[a-zA-Z%]/",($_REQUEST["username"])) ){
            if(preg_match($pwRegex,$_REQUEST["psw"]) && preg_match("/[a-z]/",($_REQUEST["psw"])) && preg_match("/[A-Z]/",($_REQUEST["psw"]))){
                if($_REQUEST["psw"] === $_REQUEST["psw2"]){

                    $user = $_REQUEST["username"];
                    $psw = $_REQUEST["psw"];

                    if(isset($_GET["username"]) && isset($_GET["psw"])){

                        if($_SESSION["last"] !== $user){
            
                    error_reporting(0);
                    $con = mysqli_connect("localhost","uReadWrite","SuperPippo!!!","biblioteca");
                    if(mysqli_connect_errno()){
                        printf("<p>errore, impossibile connesione al DB %s</p>",mysqli_connect_error());
                    } else{
            
                        $query1 = "SELECT COUNT(*) as tot FROM users WHERE username = ?";
                        $stmt1 = mysqli_prepare($con, $query1);
                        mysqli_stmt_bind_param($stmt1,"s",$user);
                        mysqli_stmt_execute($stmt1);
                        mysqli_stmt_bind_result($stmt1,$res);
                        mysqli_stmt_fetch($stmt1);
                        //mysqli_fetch($stmt1);
            
                        if($res > 0){
                            $_SESSION["esiste"] = true;
                        } else {
                          
                            mysqli_stmt_free_result($stmt1);
                            mysqli_stmt_close($stmt1);
                
                            $query = "INSERT INTO `users`(`username`, `pwd`) VALUES (?,?)";
                            $stmt2 = mysqli_prepare($con, $query);
                            mysqli_stmt_bind_param($stmt2, "ss", $user, $psw);
                            $res2 = mysqli_stmt_execute($stmt2);
                            if($res2 == null){
                                printf("<p>Errore apertura DB %s</p>",mysqli_connect_error($con));
                            }else{
                                $_SESSION["esiste"] = false;
                                mysqli_stmt_free_result($stmt2);
                                mysqli_stmt_close($stmt2);
                                mysqli_close($con);
                            }
                        }
                    }
                    $_SESSION["last"] = $user;
                }
            }               
        if($_SESSION["esiste"] == true)
            echo "<p class = 'error'>Errore: username già esistente, torna alla <a class = 'a'  href = 'NEW.php'>registrazione!</a></p>";
        else{
            echo "<p class = 'totti'>Registrazione andata a buon fine!</p>";
            if($_SESSION["login"] == false)
            echo "<p>Effettua subito il <a class = 'a' href='Login.php'>login</a>!</p>";
            else echo "<p>Vai a <a class = 'a' href='Libri.php'>libri</a>!</p>";
        }
    } else echo "<p class = 'error'>Le password non combaciano!</p> <p>Ritorna alla <a class = 'a'  href = 'NEW.php'>registrazione</a></p>";
    } else echo "<p class = 'error'>La password dell’utente puo contenere solo caratteri alfabetici, deve essere lunga minimo quattro e massimo` otto caratteri ed avere almeno un carattere minuscolo ed uno maiuscolo.</p> <p>Ritorna alla <a class = 'a'  href = 'NEW.php'>registrazione</a></p>";
} else echo "<p class = 'error'>Lo username dell’utente puo contenere solo caratteri alfabetici o numerici o il simbolo %, deve iniziare con` un carattere alfabetico o con %, deve essere lungo da un minimo di tre ad un massimo di sei caratteri e deve contenere almeno un carattere non numerico ed uno numerico.</p> <p>Ritorna alla <a class = 'a'  href = 'NEW.php'>registrazione</a></p>";
    }
    }else{
        $_SESSION["refresh"] = 0;
        echo "<p class = 'errore3'>Ops,si e' verificato un errore!</p> <p>Ritorna alla <a class = 'a'  href = 'Home.php'>home page</a></p>";
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

    </div>
    </body>
</html>