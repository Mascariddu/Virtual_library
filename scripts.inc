<?php function head() {
        echo "<meta charset='utf-8'><meta name='author' content='Andrea Mascarello'><link rel='stylesheet' type='text/css'href=\"StilePagine.css\">";
     }
     
     function footer() {
        echo  "<p>Autore : Andrea Mascarello</p><p>Email : mascarelloandrea@icloud.com</p><p><img class = 'footer' src = './img/html.png'><img class = 'footer' src = './img/css.png'><img class = 'footer' src = './img/js.png'></p>";
        echo  basename($_SERVER['PHP_SELF']);
     }

     function header2(){
         echo "<h1>BOOKS PLATFORM</h1>";
     }

     function menu(){
         echo '<div class="vertical-menu"><ul><a class = "trans" href="Home.php" ><img class = "menu" src ="./img/home.png">Home</a><a class = "trans" href="Login.php"><img class = "menu" src ="./img/login.png">Login</a><a class = "trans" href="Libri.php"><img class = "menu" src ="./img/libri.png">Libri</a><a class = " trans" href="NEW.php"><img class = "menu" src ="./img/new.png">New user</a><a class = "trans" href="Logout.php"><img class = "menu" src ="./img/logout.png">Logout</a></ul></div>';
     }

     function nome(){
         echo $_SESSION["utente"]."-".$_SESSION["libri"];
     }

?>