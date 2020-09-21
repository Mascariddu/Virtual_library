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
    if(!isset($_SESSION["visit"])){
        $_SESSION["visit"] = 0;
    }
    if(!isset($_SESSION["giusto"])){
        $_SESSION["giusto"] = 0;
    }
}

$_SESSION["visit"]++;
include("scripts.inc");
$_SESSION["err"] = false;
$_SESSION["tornato"] = 0;
$_SESSION["err2"] = false;
$_SESSION["ritorno"] = 0;
?>
<!doctype html>
<html lang = "IT" class = "animated fadeInLeft">
    
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
        <title>RESTITUZIONE</title>
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

            $count = 0;
            if($_SESSION["tutti"] != array()){

            $con = mysqli_connect("localhost","uReadOnly","posso_solo_leggere","biblioteca");
            if(mysqli_connect_errno()){
                printf("<p>errore, impossibile connesione al DB %s</p>",mysqli_connect_error());
            } else{
            $query = "SELECT * from books";
                $res = mysqli_query($con, $query);

                if(!$res){
                    printf("<p>Errore apertura DB %s</p>",mysqli_connect_error($con));
                }else{
                    while($row = mysqli_fetch_assoc($res)){
                            if(isset($_REQUEST[$row["id"]]))
                                $count++;
                    }
                }

                mysqli_free_result($res);
                mysqli_close($con);
            }

            if($count <= 1 ){

                foreach($_SESSION["tutti"] as $key => $value){
                    if(isset($_REQUEST[$key])){
                        $_SESSION["idlibro"] = $value;
                    }
                }
            $id = $_SESSION["idlibro"];

            error_reporting(0);
            $con = mysqli_connect("localhost","uReadWrite","SuperPippo!!!","biblioteca");
            if(mysqli_connect_errno()){
                printf("<p>errore, impossibile connesione al DB %s</p>",mysqli_connect_error());
            } else{

                $query = "SELECT COUNT(*) FROM `books` WHERE `id` = ? AND `prestito` = ?";
                $stmt1 = mysqli_prepare($con, $query);
                mysqli_stmt_bind_param($stmt1,"is",$id,$_SESSION["utente"]);
                mysqli_stmt_execute($stmt1);
                mysqli_stmt_bind_result($stmt1,$res);
                while(mysqli_stmt_fetch($stmt1)){
                    if($res < 1)
                        $flag = true;
                }
                mysqli_stmt_free_result($stmt1);
                mysqli_stmt_close($stmt1);

                if($flag == false){
                $query = "UPDATE `books` SET `prestito`=null,`data`='0000-00-00 00:00:00',`giorni`= 0 WHERE `id` = ?";
                $stmt1 = mysqli_prepare($con, $query);
                mysqli_stmt_bind_param($stmt1,"i", $id);
                $res = mysqli_stmt_execute($stmt1);

                if($res){
                     echo "<p>Restituzione avvenuta correttamente per il libro numero $id!</p>";
                     echo "<p>".$_SESSION["stato"][$id]."!</p>";
                     echo "<p>Libro consegnato dopo: ".$_SESSION["giorni"][$id]."!</p>";
                     echo "<p>Ritorna alla pagina <a href= 'Libri.php'>libri</a></p>";
                } else{
                     echo "<p>Errore query fallita: ".mysqli_error($con)."</p>\n";
                }

                mysqli_stmt_free_result($stmt1);
                mysqli_stmt_close($stmt1);
                
                $query1 = "SELECT COUNT(*) FROM books where prestito = ?";
                $stmt1 = mysqli_prepare($con, $query1);
                mysqli_stmt_bind_param($stmt1,"s",$_SESSION["utente"]);
                mysqli_stmt_execute($stmt1);
                mysqli_stmt_bind_result($stmt1,$res);
                mysqli_stmt_fetch($stmt1);

                $libri = $res;
                mysqli_stmt_free_result($stmt1);
                mysqli_stmt_close($stmt1);
                mysqli_close($con);

                $_SESSION["libri"] = $libri ;
            }else echo "<p class = 'errore'>Restituzione non riuscita, libro riconsegnato alla libreria in precedenza o libro non posseduto dall'utente</p><p>Ritorna a <a class = 'a' href='Libri.php'>libri</a></p>";
        }
    }else echo "<p class = 'errore'>Impossibile restituire piu' di un libro per volta!</p><p>Ritorna a <a class = 'a' href='Libri.php'>libri</a></p>";
        } else echo "<p class = 'errore3'>Assenza dati per proseguire! \n Ritorna alla <a class = 'a' href='Home.php'>home page</a></p>";
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