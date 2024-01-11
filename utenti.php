<?php
session_start();
include 'funzioni.php';
$tabella = '';
$mail = '';
$message_us = '';
$message_psw = '';
$bottone_login = '<a id="login" href="login.php">Login</a>';
$utenti = '';
$bottone_utente = '<a class="loginn" href="registrazione.php">Registrati </a>';


//Condizione per verificare se l'utente è un amministratore e se la variabile di sessione "amministratore" è impostata a 1 se si, metto il tasto Logout e bottone area admin
if (isset($_SESSION["amministratore"]) && $_SESSION["amministratore"] == 1) {
    $bottone_login = '<form action="login.php" method="post"><input type="submit" id="loginn" name="logout" value="Logout"></form>';
    $utenti = '<li class=""><a href="areaadmin.php">Area Admin</a></li>';
    $bottone_utente = '';

    //se è un utente loggato sostituisco il bottone di login con quello di logout e inserisco il bottone per la gestione area personale
} elseif (isset($_SESSION["login"])) {
    $username = $_SESSION['login'];
    $tipo = tipoUtente($username); //richiamo la funzione tipo utente e inserisco il valore in una variabile
    $bottone_login = '<form action="login.php" method="post"><input type="submit" id="logout" name="logout" value="Logout"> </form>';
    if ($tipo == 'reg') { //se il valore che la funzione mi ha restituito e di utente standard genera il bottone area privata
        $utenti = '<li class=""><a href="utenti.php">Area Privata</a></li>';
        $bottone_utente = ''; //tolgo bottone registrati


    } else {
        $utenti = '<li class=""><a href="areaadmin.php">Area Admin</a></li>';
        $bottone_utente = '';

    }
    //nel caso venga cliccato logout distruggo la sessione e genero nuovamente il tasto login invece che log out 
    if (isset($_POST['logout']) == true) {
        $bottone_login = '<a id="loginn" href="login.php">Login</a>';
        $utenti = '';
        session_destroy();
    }
}

//richiamo la funzione recupera utente con parametro $username 
    $utente = recuperaUtente($username);
//creazione tabella con i dati personali dell'utente 
    for ($i = 0; $i < sizeof($utente); $i++) {
        $tabella .= '<tr>
                    <td>' . $utente[$i]["nome"] . '</td>
                    <td>' . $utente[$i]["cognome"] . '</td>
                    <td>' . $utente[$i]["mail"] . '</td>
                    <td>' . $utente[$i]["username"] . '</td>
                    <td >' . $utente[$i]["password"] . '</td>
                  </tr>';
    }
//salvo in username il valore della variabile di sessione e richiamo la funziona recuperaUtente con l'attuale username e assegno a variabile 
    $username = $_SESSION['login'];
    $use = recuperaUtente($username);
    //se inviata richiesta post del bottone per cambiare username recupero il nuovo username dal form 
    if (isset($_POST['cambiaUser'])) {
        $username = $_POST['username'];

        if (empty($username)) { //se vuoto do errore
            $message_us = 'Errore, campo mancante';
        } else {
            //itera fra tutti gli utenti e verifica se il nuovo username è già presente nei dati derivanti dalla funzione 
            for ($i = 0; $i < sizeof($use); $i++) {
                if ($username == $use[$i]['username']) {
                    $message_us = '<p>Questo username è già utilizzato</p>';
                } 
                
                else {

                    //recupera l'indirizzo mail 
                    $mail = $use[$i]['mail'];
                    //chiamo la funzione cambiausername e metto passando i dati nella variabile 
                    $res = CambiaUsername($username, $mail);

                    //se res è true il cambio è avvenuto con successo 
                    if ($res) {
                        $message_us = '<p>Cambiamento avvenuto con successo!</p>';
                        //aggiorno le informazioni dell'utente e la tabella 
                        $utente = recuperaUtente($username);
                        for ($i = 0; $i < sizeof($utente); $i++) {
                            $tabella = '<tr>
                    <td>' . $utente[$i]["nome"] . '</td>
                    <td>' . $utente[$i]["cognome"] . '</td>
                    <td>' . $utente[$i]["mail"] . '</td>
                    <td>' . $utente[$i]["username"] . '</td>
                    <td>' . $utente[$i]["password"] . '</td>
                  </tr>';
                            $_SESSION["login"] = $username; //salvo il nuovo username nella sessione 

                        }
                    } else { //se no errore 
                        $message_us = '<p>Non hai inserito tutti i campi necessari</p>';
                    }
                }
            }
        }
    }

//salvo in username il valore della variabile di sessione e richiamo la funziona recuperaUtente con l'attuale username e assegno a variabile  
    $username = $_SESSION['login'];
    $pass = recuperaUtente($username);
//se inviata richiesta post del bottone per cambiare username recupero la nuova password dal form 
    if (isset($_POST['cambiaPsw'])) {
        $psw = $_POST['psw'];
        $psw1 = $_POST['psw1'];
        //se una delle due è vuote errore
        if (empty($psw) || empty($psw1)) {
            $message_psw = '<p>Per favore inserisci entrambe le password</p>';

            // se non sono uguali errore
        } elseif ($psw != $psw1) {
            $message_psw = '<strong><p>Le password non coincidono</p></strong>';
        } else {

        //e verifica se la nuova pass è uguale a quella precedente
            for ($i = 0; $i < sizeof($pass); $i++) {
                if ($psw == $pass[$i]['password']) {
                    $message_psw = '<p>La password non può essere uguale a quella precedentemente utilizzata</p>';
                } else {
                    //cambio password richiamando la funzione con parametri username e password
                    $cambiaPsw = cambiaPsw($username, $psw);
                    if ($cambiaPsw) { //se true successo e aggiorno dati tabella 
                        $message_psw = '<strong><p>Cambiamento avvenuto con successo!</p></strong>';
                        $utente = recuperaUtente($username);
                        for ($i = 0; $i < sizeof($utente); $i++) { //ricarico la tabella per mostrare all'utente i cambiamenti
                            $tabella = '<tr>
                                            <td>' . $utente[$i]["nome"] . '</td>
                                            <td>' . $utente[$i]["cognome"] . '</td>
                                            <td>' . $utente[$i]["mail"] . '</td>
                                            <td>' . $utente[$i]["username"] . '</td>
                                            <td>' . $utente[$i]["password"] . '</td>
                                            </tr>';
                        }
                    } else {
                        $message_psw = "<strong><p>Si è verificato un errore durante il cambiamento della password, riprova più tardi o contatta l'amministratore</p></strong>";
                    }
                }
            }
        }
    }
//se inviato il modulo con cancella recupero username corrente 
    if (isset($_POST['cancella'])) {
        $username = $_SESSION['login'];

        //richiama la funzione cancellautente con parametro l'username corrente 
        if (cancellaUtente($username) == true) {
            session_destroy(); //elimina sessione
            header("Location: login.php"); 
        } else { //errore
            echo "Impossibile eliminare l'utente";
        }
    }


?>

<!DOCTYPE html>
<html lang=it dir="ltr">

<head>
  <meta charset="utf-8">
  <meta name="keywords" content="Web design, grafica, html, css" />
  <meta name="description" content="sito web di Francesco Lazzarotto" />
  <meta name=“author" content=“Francesco Lazzarotto" />
  <link rel="shortcut icon" href="immagini/logo.jpg" type="image/jpg">
  <title> Esame Lazzarotto Francesco (Sito Palestra) </title>

  <link rel="stylesheet" href="style.css" type="text/css">
  <style> #table {
  width: 48%;
  border-collapse: collapse;
  margin: 20px 0;
  border-radius: 25px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
}

#table th, #table td {
  padding: 10px;
  text-align: center;
  border: 1px solid #000;
  color: #000;
  background-color: #f5f5f5;
}

#table th {
  font-weight: bold;
  background-color: #d3d3d3;
  color: #000;
}

#table tr:nth-child(even) {
  background-color: #e0e0e0;
}

#table tr:hover {
  background-color: #f0f0f0;
}

#table td {
  font-weight: bold;
  color: #333; 
}


 </style>

</head>

<body>

  <a name="inizio"></a>

  <nav id="navigation" class="menu1">
    <table>
      <tr>
          <td id="tdsx">   <img src="immagini/LOGO_1.png" alt="logo palestra" class="logonuovo">  </td>


        <br>
        <td>
            <ul>
          <li><a href="homee.php">Home </a></li>
            <li><a href="corsi.php"> Corsi</a></li>
            <li> <a href="benefici.php"> Benefici </a> </li>    
            <li><a href="contatti.php">Contatti </a></li>
     <?php echo $utenti ?>
        <li id="logoutpos">
            <?php echo $bottone_login ?>
        </li>
        <li>
            <?php echo $bottone_utente ?>
        </li>
                </ul>
            </td>
        </tr>
    </table>

</nav>



  <div id="contentutenti">
    <br> <br> <center>
  <h3 class="titolovipad2">Gestione account</h3>
                <table id="table">
                    <tr>
                        <th>Nome</th>
                        <th>Cognome</th>
                        <th>Email</th>
                        <th>Username</th>
                        <th>Password</th>
                    </tr>
                    <?php echo $tabella ?>
                </table>
            </center>
            <br> <center>
            <div id="login-form4">
                <h2 class="titolovip">Cambia i tuoi dati</h2> <br>
                <form action="utenti.php" method="post">
                    <label for="username">Username:
                        <input type="text" placeholder="Inserire nuovo Username" name="username" required>
                    </label><br>
                    <input type="submit" value="Cambia" name="cambiaUser"/>
                </form>
                <?php echo $message_us ?>
                <br><br>
                <form action="utenti.php" method="post">
                    <label for="password">Nuova Password:
                        <input type="password" placeholder="password" name="psw" required>
                    </label><br>
                    <label for="password1">Nuova Password:
                        <input type="password" placeholder="riscriva la password" name="psw1" required>
                    </label><br>
                    <input type="submit" value="Cambia" name="cambiaPsw"/>
                </form><br><br>
                <?php echo $message_psw ?>
                <h3 id="titolovip1">Premere sul bottone sottostante per cancellare l'account</h3>
                <form action="utenti.php" method="post">
                    <input type="submit" name="cancella" value="Cancella"/>
                </form>
            </div> </center>
        </div>
    
    


        





    <footer>
        <a href="#inizio">

            <div id="tornasu">

                <img src="immagini/su.png" class="tornasu" alt="freccia per tornare all'inizio della pagina"
                    title="freccia">

            </div>

        </a>
        <div id="social">
            <table>
                <tr>
                    <td>

                        <a href="https://www.facebook.com" target="_blank"> <img src="immagini/fb1.png" id="socialimg1"
                                alt="logo facebook" title="contatti"> </a>
                    </td>
                    <td>
                        <a href="https://www.instagram.com" target="_blank"> <img src="immagini/ig1.png" id="socialimg2"
                                alt="logo instagram" title="contatti"> </a>
                    </td>
                    <td>
                        <a href="https://www.linkedin.com/in/francesco-lazzarotto-a09aa51ba/" target="_blank"> <img
                                src="immagini/ln1.png" id="socialimg3" alt="logo linkedin" title="contatti"> </a>
                    </td>
                </tr>
            </table>

        </div>
        &copy; <em> 2020 Lazzarotto Gym - Fitness <br>
            Design and Graphics by </em> <a href="linkedin.com/in/francesco-lazzarotto-a09aa51ba/" target="_blank"
            class="cop">Francesco Lazzarotto </a> <br>
        <a href="https://mail.google.com/mail/u/0/#inbox" target="_blank" class="cop1">
            francesco.lazzarotto@edu.unito.it
            <br>

    </footer>
    <script>







        window.addEventListener("scroll", function () {

            if (window.pageYOffset > 300) {
                document.getElementById("tornasu").style.display = "block";
            }

            else if (window.pageYOffset < 300) {
                document.getElementById("tornasu").style.display = "none";
            }

        });

    </script>
</body>

</html>