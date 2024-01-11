<?php
include('funzioni.php');
session_start();
$listaCorsi = listaCorsi();
 
$bottone_login = '<a class="loginn" href="login.php">Login</a>';
$form_ricerca = '';
$elenco_corsi = '';
$utenti = '';
$bottone_utente = '<a class="loginn" href="registrazione.php">Registrati </a>';
if (!isset($_SESSION['login'])) {
  header("Location: logincorsi.php"); // Reindirizza alla pagina di login se l'utente non è loggato
  exit;
}
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

  $corsi = listaAdmin();


  $id_corso = '';



?>



<!DOCTYPE html>
<html lang=it dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="keywords" content= "Web design, grafica, html, css" />
    <meta name="description" content="sito web di Francesco Lazzarotto" />
    <meta name=“author" content=“Francesco Lazzarotto" />
    <link rel="shortcut icon" href="immagini/logo.jpg" type="image/jpg">
    <title> Esame Lazzarotto Francesco (Sito Palestra) </title>

    <link rel="stylesheet" href="style.css" type="text/css">

  </head>

  <body>
    
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    var menu = $(".menu");
    var lastScrollTop = 0;
    var scrollPoint = 900; // Altezza dello scroll in pixel a cui cambiare l'opacità

    $(window).scroll(function() {
        var st = $(this).scrollTop();

        if (st > lastScrollTop) {
            // Scorrimento verso il basso
            if (st >= scrollPoint) {
                menu.css('background', '#222');
            }
        } else {
            // Scorrimento verso l'alto
            if (st < scrollPoint) {
                menu.css('background', 'transparent');
            }
        }

        lastScrollTop = st;
    });
});
</script>

<a name="inizio"></a>

<nav id="navigation" class="menu">
  <table> <tr>
 <td id="tdsx">  <img src="immagini/LOGO_1.png" alt="logo palestra" class="logonuovo">  </td>

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

  <div class="parallax-container">
        
     <div id="sfondohome1"> 
      <div class="testo"> Lazzarotto Gym <br> Via Silvio Pellico 10, 10121 Torino 
      <br> <br> <br> <p class="testopiccolo"> Tel. 3392417525   
    <br> <br>
        ORARI <br> LUN-VEN 8-22 SAB 9-19 DOM CHIUSO </p>
       <br>  
       
       <a href="Orari Corsi.pdf" target="_blank"  class="bottone1">Scarica calendario corsi</a>
      </div>  </a>
    </div>
</div>



    <div id="content2">

      
<br> <br> 
  
<div id="ricerca-corsi">
    <h2 class="titolovip">Ricerca Corsi</h2>
    <form method="post"  action="corsi.php"> 
        <input type="text" name="parola_chiave" placeholder="Parola chiave" class="campo-ricerca">
<select name="tipologia" class="menu-a-discesa">
      <option value=""> <h4 class="postdisp"> Seleziona il tipo di corso </h4> </option>
            <option value="Fitness">Fitness</option>
            <option value="Yoga">Yoga</option>
            <option value="Anaerobico">Anaerobico</option>
            <option value="Pilates">Pilates</option>
            <option value="Bodybuilding">Bodybuilding</option>
            <option value="Dancign Fitness">Dancing Fitness</option>
            <option value="CrossFit">CrossFit</option>
            <option value="Arti Marziali">Arti Marziali</option>
            <option value="Funzionale"> Funzionale </option>
</select> 
 <h4 class="postidisp" >Posti Disponibili : </h4>
<input type="checkbox" name="partecipanti" value="1" class="casella-controllo">
    
    <input type="hidden" name="corso_id" value="cerca">
    <button type="submit" class="pulsante-cerca">Cerca </button>
        
    </form>
</div>



<ul class="lista-corsi">
    <?php
    //ricerca specifica dei corsi per parole chiave, tipologia, posti disponibili 
    $connessione = connessione_database();
//verifica se la richiesta è post
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 'pulisco' la stringa da caratteri speciali e diversi così la query non sarà modificata da essi
    $parola_chiave = mysqli_real_escape_string($connessione, $_POST['parola_chiave']);
    $tipo_corso = $_POST['tipologia']; //recupero il valore della tipologia
    $posti_disponibili = isset($_POST['partecipanti']) ? 1 : 0; //verifico se è stata cliccata la checkbox o no 

   //query per selezionare i corsi
    $query = "SELECT * FROM corso WHERE nome LIKE '%$parola_chiave%'";
 // aggiungo parti alla query
    if (!empty($tipo_corso)) {
      $query .= " AND tipologia = '$tipo_corso'";
    }
    // massimo partecipanti 30 
    if ($posti_disponibili) {
      $query .= " AND partecipanti < 30";
    }

    $risultato = mysqli_query($connessione, $query);
//stampa dei corsi risultanti dalla query 
    foreach ($risultato as $corso) {
      echo "<li class='corso'>";
      echo "<strong class='nome-corso'> " . $corso['nome'] . "</strong>";
      echo "<p class='tipo'> TIPOLOGIA CORSO : " . $corso['tipologia'] . "</p>";
      echo "<a class='det' href='paginacorso.php?id=" . $corso['id_corso'] . "'>Dettagli corso</a>";
      echo "</li>";
    }
  }
   
  ?>
</ul>


<center>
  <!-- stampo tutti i corsi presenti nel database !-->
 <h1 class="titolovip">Elenco completo dei Corsi</h1> </center>
   <ul class="lista-corsi">
    <?php
    foreach ($listaCorsi as $corso) {
      echo "<li class='corso'>";
      echo "<strong class='nome-corso'> " . $corso['nome'] . "</strong>";
      echo "<p class='tipo'> TIPOLOGIA CORSO : " . $corso['tipologia'] . "</p>";
      echo "<a class='det' href='paginacorso.php?id=" . $corso['id_corso'] . "'>Dettagli corso</a>";
      echo "</li>";
    }
    ?>
    </ul>






  </div>

          <footer>
            <a href="#inizio">

<div id="tornasu">

<img src= "immagini/su.png" class="tornasu" alt="freccia per tornare all'inizio della pagina" title="freccia">

</div>

</a>
<div id="social"> <table> <tr> <td>

    <a href="https://www.facebook.com" target="_blank"> <img src="immagini/fb1.png" id="socialimg1" alt="logo facebook" title="contatti">  </a> </td> <td>
    <a href="https://www.instagram.com" target="_blank"> <img src="immagini/ig1.png" id="socialimg2" alt="logo instagram" title="contatti">  </a> </td> <td>
    <a href="https://www.linkedin.com/in/francesco-lazzarotto-a09aa51ba/" target="_blank"> <img src="immagini/ln1.png" id="socialimg3" alt="logo linkedin" title="contatti">  </a> </td> </tr> </table>

  </div>
                &copy; <em> 2020 Lazzarotto Gym - Fitness <br>
                Design and Graphics by </em>  <a href="linkedin.com/in/francesco-lazzarotto-a09aa51ba/"target="_blank" class="cop">Francesco Lazzarotto </a> <br>
              <a href="https://mail.google.com/mail/u/0/#inbox" target="_blank" class="cop1" >  francesco.lazzarotto@edu.unito.it <br>

    </footer>
    <script>

        window.addEventListener ("scroll",function(){

            if (window.pageYOffset>300) {
            document.getElementById ("tornasu").style.display= "block";
            }

              else if (window.pageYOffset<300) {
              document.getElementById ("tornasu").style.display= "none";
              }});

             

              




    </script>
  </body>
</html>