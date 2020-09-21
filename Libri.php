
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
        if(!isset($_SESSION["val"]))
            $_SESSION["val"] = 0;
        if(!isset($_SESSION["count"]))
            $_SESSION["count"] = 0;
        if(!isset($_SESSION["idlibro"])){
            $_SESSION["idlibro"] = 0;
        }
        if(!isset($_SESSION["prestito"])){
            $_SESSION["prestito"] = 0;
        }
        if(!isset($_SESSION["totali"])){
            $_SESSION["totali"] = 0;
        }

        $_SESSION["checked"] = array();
        $_SESSION["tutti"] = array();
        $_SESSION["stato"] = array();
        $_SESSION["giorni"] = array();
    }
    include("scripts.inc");
    $_SESSION["visit"] = 0;
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
    <title>LIBRI</title>
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
        <form name = "f2" action = "restituzione.php" method = "POST">
        <?php 
        
            $check = 0;
            function sectostr($secs) {
            $r = '';
            if ($secs >= 86400) {
                $days = floor($secs/86400);
                $secs = $secs%86400;
                $r .= $days . ' giorni';
                if ($secs > 0) $r .= ', ';
            }
            if ($secs >= 3600) {
                $hours = floor($secs/3600);
                $secs = $secs%3600;
                $r .= $hours . ' ore';
            if ($secs > 0) $r .= ', ';
            }
            if ($secs>=60) {
                $minutes = floor($secs/60);
                $secs = $secs%60;
                $r .= $minutes . ' minuti';
            if ($secs > 0) $r .= ', ';
            }
            return $r . $secs . ' secondi';
            }

            if($_SESSION["login"] == true){
                        
                error_reporting(0);
                $con = mysqli_connect("localhost","uReadOnly","posso_solo_leggere","biblioteca");
                if(mysqli_connect_errno()){
                    $err = true;
                    printf("<p>errore, impossibile connesione al DB %s</p>",mysqli_connect_error());
                } else{
                $query = "SELECT COUNT(*) FROM `books` WHERE prestito = ?";
                $stmt1 = mysqli_prepare($con, $query);
                mysqli_stmt_bind_param($stmt1,"s",$_SESSION["utente"]);
                mysqli_stmt_execute($stmt1);
                mysqli_stmt_bind_result($stmt1,$res);
                while(mysqli_stmt_fetch($stmt1)){
                        $count = $res;
                    }
                mysqli_stmt_free_result($stmt1);
                mysqli_stmt_close($stmt1);
                }
                
                $_SESSION["val"] = 3-$count;
                $_SESSION["libri"] = $count;

            if(isset($_REQUEST["prestito"]))
                echo "<p class='errore' id='errore'>warning: $errore</p>";
            echo "<h2>I tuoi libri</h2>";
            if($_SESSION["libri"] === 0){
            echo "Nessun libro in prestito!";
                } else echo "<table class= 'table' ><tr><th>ID</th><th>Autore</th><th>Nome</th><th>Restituzione</th></tr>";
            
                $query = "SELECT * FROM `books` WHERE prestito = ?";
                $stmt1 = mysqli_prepare($con, $query);
                mysqli_stmt_bind_param($stmt1,"s",$_SESSION["utente"]);
                mysqli_stmt_execute($stmt1);
                mysqli_stmt_bind_result($stmt1,$id,$autori,$titolo,$prestito,$data,$giorni);
                while(mysqli_stmt_fetch($stmt1)){
                     echo "<tr><td id ='id'>".$id."</td><td>".$autori."</td><td>".$titolo."</td><td><input class = 'button' type='submit' name = $id value = 'RESTITUISCI'></td></tr>";
                     $_SESSION["giorni"][$id] = sectostr(time() - strtotime($data));
                     $_SESSION["tutti"][$id] = $id;
                    }
                if($_SESSION["libri"] > 0)
                    echo "</table>";
                mysqli_stmt_free_result($stmt1);
                mysqli_stmt_close($stmt1);

                ?>
                </form>
                <form name = 'f' action = "errore.php" method = 'POST' onsubmit = 'return controllo()'>
                <?php
                echo "<h2>Libri della biblioteca</h2><table><tr><th>ID</th><th>Autore</th><th>Nome</th><th>Stato</th></tr>";

                $query = "SELECT DISTINCT * FROM books";
                $res = mysqli_query($con, $query);

                if(!$res){
                    printf("<p>Errore apertura DB %s</p>",mysqli_connect_error($con));
                }else{
            
                while($row = mysqli_fetch_assoc($res)){
                    $id = $row["id"];
                    if($row["prestito"] == null){
                        $_SESSION["stato"][$id] = "";
                        $_SESSION["check"][$id] = $id;
                        echo "<tr><td>".$id."</td><td>".$row["autori"]."</td><td>".$row["titolo"]."</td><td><input type = 'checkbox' id = $id name = $id></tr>";
                        
                    } else{
                        if(strtotime($row["data"]) + $row["giorni"]*24*3600 < time()){
                            $_SESSION["stato"][$id] = "Attenzione il prestito era scaduto";
                            echo "<tr><td>".$id."</td><td>".$row["autori"]."</td><td>".$row["titolo"]."</td><td class = 'scaduto' >PRESTITO SCADUTO</td></tr>";
                        }else{
                            $_SESSION["stato"][$id] = "Prestito avvenuto entro le tempistiche prestabilite";
                            echo "<tr><td>".$id."</td><td>".$row["autori"]."</td><td>".$row["titolo"]."</td><td class = 'inP' >IN PRESTITO</td></tr>";
                        }
                    }
                }
                echo "</table>";
                echo "<p class='sposta'>Durata prestito: <input type = 'text' value='' id = 'gg' name = 'gg' > <input type = 'submit' class = 'button' name = 'prestito' value = 'PRESTITO'></p>";

                mysqli_free_result($res);
                mysqli_close($con);
            }
        } else{

            error_reporting(0);
            $con = mysqli_connect("localhost","uReadOnly","posso_solo_leggere","biblioteca");
                if(mysqli_connect_errno()){
                    $err = true;
                printf("<p>errore, impossibile connesione al DB %s</p>",mysqli_connect_error());
                } else{
                $query = "SELECT COUNT(*) as tot from books";
                $res = mysqli_query($con, $query);

                if(!$res){
                    printf("<p>Errore apertura DB %s</p>",mysqli_connect_error($con));
                }else{
                    while($row = mysqli_fetch_assoc($res)){
                            echo "<p class='books'>Libri totali della biblioteca: ".$row["tot"]." </p>";
                    }
                }

                mysqli_free_result($res);
            }

                $query = "SELECT COUNT(*) as tot FROM `books` WHERE `prestito` = ''";
                $res = mysqli_query($con, $query);

                if(!$res){
                    printf("<p>Errore apertura DB %s</p>",mysqli_connect_error());
                }else{
                    while($row = mysqli_fetch_assoc($res))
                        echo "<p>Libri disponibili al prestito: ".$row["tot"]." </p>";
                }

                mysqli_free_result($res);
                mysqli_close($con);
                echo "<p>Inizia subito a prendere in prestito dei libri: <a class = 'a'  href = 'Login.php'>Login</a></p>";
                }

        ?>
        </form>
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