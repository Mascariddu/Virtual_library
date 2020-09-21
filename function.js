
function check2() {
    var userRegex = /^([a-zA-Z%]{1})[\w%]{2,5}$/;
var pwRegex = /^[A-Za-z]{4,8}$/;

var user = document.getElementById("user").value;
var pw1 = document.getElementById("psw").value;
var pw2 = document.getElementById("psw2").value;

if ( userRegex.test(user) ){
if(/\d/.test(user)){
    if(/[a-zA-Z%]/.test(user)){
    if ( pwRegex.test(pw1)){
        if(/[a-z]/.test(pw1)){
            if(/[A-Z]/.test(pw1)){
                if(pw1 ===pw2)
                return true;
                else{
                    window.alert("Le password non combaciano!");
                    return false;
                }
            }else{
                window.alert("Manca una lettera maiuscola nella password!");
                return false;
            }
        }else {
            window.alert("Manca una lettera minuscola nella password!");
            return false;
        }
    } else {

        window.alert("La password può contenere solo caratteri alfabetici. Deve essere lunga fra i 4 e gli 8 caratteri !");
        document.getElementById("psw").innerHTML = "";
        return false;
    }
    }else{
        window.alert("Manca un carattere non numerico nello username!");
        return false;
    }
}else{
   window.alert("Manca un carattere numerico nello username!");
   return false;
}
} else {

window.alert("Lo username può contenere solo caratteri alfanumerici e \"%\". Deve essere lungo fra i 3 e i 6 caratteri di cui almeno uno numerico e uno non numerico e iniziare con lettere o \"%\"!");
return false;
}
}

function check() {
    var userRegex = /^([a-zA-Z%]{1})[\w%]{2,5}$/;
    var pwRegex = /^[A-Za-z]{4,8}$/;
    
    var user = document.getElementById("user").value;
    var pw1 = document.getElementById("psw").value;
    
    if ( userRegex.test(user) ){
        if(/\d/.test(user)){
            if(/[a-zA-Z%]/.test(user)){
            if ( pwRegex.test(pw1)){
                if(/[a-z]/.test(pw1)){
                    if(/[A-Z]/.test(pw1))
                    return true;
                    else{
                        window.alert("Manca una lettera maiuscola nella password!");
                        return false;
                    }
                }else {
                    window.alert("Manca una lettera minuscola nella password!");
                    return false;
                }
            } else {

                window.alert("La password può contenere solo caratteri alfabetici. Deve essere lunga fra i 4 e gli 8 caratteri !");
                document.getElementById("psw").innerHTML = "";
                return false;
            }
            }else{
                window.alert("Manca un carattere non numerico nello username!");
                return false;
            }
        }else{
           window.alert("Manca un carattere numerico nello username!");
           return false;
        }
    } else {

        window.alert("Lo username può contenere solo caratteri alfanumerici e \"%\". Deve essere lungo fra i 3 e i 6 caratteri di cui almeno uno numerico e uno non numerico e iniziare con lettere o \"%\"!");
        return false;
    }
}

function controllo(){

    var num = document.getElementById("gg").value;
    var expr = /[1-9]/;

    if(!expr.test(num)){
        window.alert("Inserisci valori numerici per la durata del prestito, lo zero non e' consentito!");
        return false;
    }

    return true;
}