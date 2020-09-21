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
    if(!isset($_SESSION["prestito"])){
        $_SESSION["prestito"] = false;
    }
    if(!isset($_SESSION["ritorno"])){
        $_SESSION["ritorno"] = 0;
    }

    $_SESSION["tutti"] = array();
    $_SESSION["stato"] = array();
    $_SESSION["giorni"] = array();
    $_SESSION["checked"] = array();
}
include("scripts.inc");
$_SESSION["tornato"] = 0;
$_SESSION["err"] = false;
$_SESSION["ritorno"]++;
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
    
    <div class= "theMenu">
        <?php
            menu();
        ?>
    </div>

    <div class = "theBody">
        <?php
        $flag = false;
        $check = 0;

        if($_SESSION["login"] == false){
            $_SESSION["check"] = array();
        }

        foreach($_SESSION["check"] as $key => $value){
            if(isset($_REQUEST[$key])){
                $_SESSION["checked"][$key] = $value;
            }
        } 

        $err = '';
        $check = sizeof($_SESSION["checked"]);
    
        if(isset($_REQUEST["prestito"])){
            if($_REQUEST["gg"] != ""){
                    if(preg_match("/^[0-9]{1,}$/",$_REQUEST["gg"])){
                        if($_REQUEST["gg"] > 0){
                            if($check > 0){
                            if($check <= 3){
                                if($_SESSION["ritorno"] <= 1){
                                if($_SESSION["libri"] < 3){
                                if(($check+$_SESSION["libri"]) <= 3){
                                    foreach($_SESSION["checked"] as $key => $value){

                                        error_reporting(0);
                                        $con = mysqli_connect("localhost","uReadWrite","SuperPippo!!!","biblioteca");
                                        if(mysqli_connect_errno()){
                                            if($err == false)
                                                printf("<p>errore, impossibile connesione al DB %s</p>",mysqli_connect_error());
                                        } else{

                                            $query = "SELECT COUNT(*) FROM `books` WHERE `id` = ? AND `prestito` != ''";
                                            $stmt1 = mysqli_prepare($con, $query);
                                            mysqli_stmt_bind_param($stmt1,"i",$value);
                                            mysqli_stmt_execute($stmt1);
                                            mysqli_stmt_bind_result($stmt1,$res);
                                            while(mysqli_stmt_fetch($stmt1)){
                                                if($res > 0)
                                                    $flag = true;
                                            }
                                            mysqli_stmt_free_result($stmt1);
                                            mysqli_stmt_close($stmt1);

                                            if($flag == false){
                                            $result = date('Y-m-d H:i:s',time());
                                            $query = "UPDATE `books` SET `prestito`= ? ,`data`= ? ,`giorni`= ? WHERE `id` = ?";
                                            $stmt1 = mysqli_prepare($con, $query);
                                            mysqli_stmt_bind_param($stmt1,"ssii", $_SESSION["utente"],$result,$_REQUEST["gg"],$value);
                                            $res = mysqli_stmt_execute($stmt1);
                                        if($res){
                                             $err =  '<p>Prestito andato a buon fine!</p>';
                                        } else{
                                            printf("<p>errore, impossibile connesione al DB %s</p>",mysqli_connect_error());
                                        }
                                        mysqli_stmt_free_result($stmt1);
                                        mysqli_stmt_close($stmt1);

                                        $query = "SELECT COUNT(*) FROM `books` WHERE prestito = ?";
                                        $stmt1 = mysqli_prepare($con, $query);
                                        mysqli_stmt_bind_param($stmt1,"s",$_SESSION["utente"]);
                                        mysqli_stmt_execute($stmt1);
                                        mysqli_stmt_bind_result($stmt1,$res);
                                            while(mysqli_stmt_fetch($stmt1)){
                                                $conta = $res;
                                            }
                                        mysqli_stmt_free_result($stmt1);
                                        mysqli_stmt_close($stmt1);
                                        mysqli_close($con);

                                        $_SESSION["libri"] = $conta;
                                    }else {$err = 'Libro gia presente, non viene aggiunto ai prestiti';
                                        mysqli_close($con);
                                    }
                                    }
                                    }
                                    echo "<p>$err</p><p>Torna a <a href = 'Libri.php'>Libri</a>";
                                } else {
                                    echo "<p class = 'errore'>Possiedi ".$_SESSION["libri"]." libri, puoi selezionarne solo ".(3-$_SESSION["libri"]."!")."</p><p>Torna a <a href = 'Libri.php'>Libri</a>";
                                    $_SESSION["ritorno"] = 0;
                                }
                            } 
                                else {echo '<p class = "errore">Hai già tre libri in prestito, non potrai prenderne altri!</p><p>Torna a <a href = "Libri.php">Libri</a></p>';
                                $_SESSION["ritorno"] = 0;
                            }
                        }else{
                            if(isset($_GET["prestito"]) && isset($_REQUEST["gg"])){
                            if($_REQUEST["gg"] != ""){
                                if(preg_match("/^[0-9]{1,}$/",$_REQUEST["gg"])){
                                    if($_REQUEST["gg"] > 0){
                                        if($check > 0){
                                        if($check <= 3){
                                            if($_SESSION["libri"] < 3){
                                            if(($check+$_SESSION["libri"]) <= 3){
                                                foreach($_SESSION["checked"] as $key => $value){
            
                                                    error_reporting(0);
                                                    $con = mysqli_connect("localhost","uReadWrite","SuperPippo!!!","biblioteca");
                                                    if(mysqli_connect_errno()){
                                                        if($err == false)
                                                            printf("<p>errore, impossibile connesione al DB %s</p>",mysqli_connect_error());
                                                    } else{
            
                                                        $query = "SELECT COUNT(*) FROM `books` WHERE `id` = ? AND `prestito` != ''";
                                                        $stmt1 = mysqli_prepare($con, $query);
                                                        mysqli_stmt_bind_param($stmt1,"i",$value);
                                                        mysqli_stmt_execute($stmt1);
                                                        mysqli_stmt_bind_result($stmt1,$res);
                                                        while(mysqli_stmt_fetch($stmt1)){
                                                            if($res > 0)
                                                                $flag = true;
                                                        }
                                                        mysqli_stmt_free_result($stmt1);
                                                        mysqli_stmt_close($stmt1);
            
                                                        if($flag == false){
                                                        $result = date('Y-m-d H:i:s',time());
                                                        $query = "UPDATE `books` SET `prestito`= ? ,`data`= ? ,`giorni`= ? WHERE `id` = ?";
                                                        $stmt1 = mysqli_prepare($con, $query);
                                                        mysqli_stmt_bind_param($stmt1,"ssii", $_SESSION["utente"],$result,$_REQUEST["gg"],$value);
                                                        $res = mysqli_stmt_execute($stmt1);
                                                    if($res){
                                                         $err =  '<p>Prestito andato a buon fine!</p>';
                                                    } else{
                                                        printf("<p>errore, impossibile connesione al DB %s</p>",mysqli_connect_error());
                                                    }
                                                    mysqli_stmt_free_result($stmt1);
                                                    mysqli_stmt_close($stmt1);
            
                                                    $query = "SELECT COUNT(*) FROM `books` WHERE prestito = ?";
                                                    $stmt1 = mysqli_prepare($con, $query);
                                                    mysqli_stmt_bind_param($stmt1,"s",$_SESSION["utente"]);
                                                    mysqli_stmt_execute($stmt1);
                                                    mysqli_stmt_bind_result($stmt1,$res);
                                                        while(mysqli_stmt_fetch($stmt1)){
                                                            $conta = $res;
                                                        }
                                                    mysqli_stmt_free_result($stmt1);
                                                    mysqli_stmt_close($stmt1);
                                                    mysqli_close($con);
            
                                                    $_SESSION["libri"] = $conta;
                                                }else {$err = 'Libro gia presente, non viene aggiunto ai prestiti';
                                                    mysqli_close($con);
                                                }
                                                }
                                                }
                                                echo "<p>$err</p><p>Torna a <a href = 'Libri.php'>Libri</a>";
                                            } else {
                                                echo "<p class = 'errore'>Possiedi ".$_SESSION["libri"]." libri, puoi selezionarne solo ".(3-$_SESSION["libri"]."!")."</p><p>Torna a <a href = 'Libri.php'>Libri</a>";
                                                $_SESSION["ritorno"] = 0;
                                            }
                                        } 
                                            else {echo '<p class = "errore">Hai già tre libri in prestito, non potrai prenderne altri!</p><p>Torna a <a href = "Libri.php">Libri</a></p>';
                                            $_SESSION["ritorno"] = 0;
                                        }
                                        }else echo "<p class = 'errore'>Non puoi selezionare piu' di tre libri</p><p>Torna a <a href = 'Libri.php'>Libri</a></p>";
                                        }else echo "<p class = 'errore'>Nessun libro selezionato o ID inesistente</p><p>Torna a <a href = 'Libri.php'>Libri</a></p>";
                                    } else echo "<p class = 'errore'> Non e' possibile mantenere un libro per meno di un giorno</p><p>Torna a <a href = 'Libri.php'>Libri</a></p>";
                                } else echo "<p class = 'errore'> Inserisci dati numerici corretti per il prestito!</p><p>Torna a <a href = 'Libri.php'>Libri</a></p>";
                            } else echo "<p class = 'errore'> Scrivi per quanti giorni deve durare il prestito</p><p>Torna a <a href = 'Libri.php'>Libri</a></p>";
                        } else echo "<p>Torna a <a href = 'Libri.php'>Libri</a></p>";
                    }
                            }else echo "<p class = 'errore'>Non puoi selezionare piu' di tre libri</p><p>Torna a <a href = 'Libri.php'>Libri</a></p>";
                            }else echo "<p class = 'errore'>Nessun libro selezionato o ID inesistente</p><p>Torna a <a href = 'Libri.php'>Libri</a></p>";
                        } else echo "<p class = 'errore'> Non e' possibile mantenere un libro per meno di un giorno</p><p>Torna a <a href = 'Libri.php'>Libri</a></p>";
                    } else echo "<p class = 'errore'> Inserisci dati numerici corretti per il prestito!</p><p>Torna a <a href = 'Libri.php'>Libri</a></p>";
                } else echo "<p class = 'errore'> Scrivi per quanti giorni deve durare il prestito</p><p>Torna a <a href = 'Libri.php'>Libri</a></p>";
            } else {
                echo "<p class = 'errore'>Assenza dati! Per proseguire torna alla <a href = 'Home.php'>home page</a></p>";
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