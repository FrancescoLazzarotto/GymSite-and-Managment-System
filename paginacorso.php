<?php
include("funzioni.php");
session_start();
$testo_errore = '';
$bottone_login = '<a class="" href="login.php">Login</a>';
$bottone_utente = '<a class="" href="registrazione.php">Registrati </a>';
$utenti = '';
$messaggio = '';
$corso_id = '';



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

//verifica se è presente l'id nella richiesta get e nel caso lo ottiene (dal bottone nella pagina dei corsi)
if (isset($_GET['id'])) {
  $corso_id = $_GET['id'];

  $connessione = connessione_database();
  $query = "SELECT * FROM Corso WHERE id_Corso = $corso_id";
  $risultato = mysqli_query($connessione, $query);
//se ci sono risultato estrae i dati della riga del corso e la mette $corso 
  if (mysqli_num_rows($risultato) > 0) {
    $corso = mysqli_fetch_assoc($risultato);
    mysqli_close($connessione);
  }
} else {
  echo "ID del corso non specificato.";

}



?>

<!DOCTYPE html>
<html lang=it dir="ltr">

<head>
  <meta charset="utf-8">
  <meta name="keywords" content="Web design, grafica, html, css" />
  <meta name="description" content="sito web di Francesco Lazzarotto" />
  <meta name="author" content="Francesco Lazzarotto" />
  <link rel="shortcut icon" href="immagini/logo.jpg" type="image/jpg">
  <title>Dettagli Corso</title>
  <link rel="stylesheet" href="style.css" type="text/css">
</head>

<body>
  <a name="inizio"></a>
  <nav id="navigation" class="menu1">
    <table>
      <tr>
         <td id="tdsx">  <img src="immagini/LOGO_1.png" alt="logo palestra" class="logonuovo">  </td>
        <br>
        <td>
          <ul>
            <li><a href="homee.php">Home</a></li>
            <li><a href="corsi.php">Corsi</a></li>
            <li> <a href="benefici.php"> Benefici </a> </li>   

            <li><a href="contatti.php">Contatti</a></li>
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
  <br> <br> <br> <br> <br>
<div id="content2" class="course-details">
  <?php
  if (isset($corso)) {
    echo "<div class='course-info'>";
    echo "<h1 class='course-title'>" . $corso['nome'] . "</h1>";
    echo "<p class='course-type'><strong>Tipologia:</strong> " . $corso['tipologia'] . "</p>";
    echo "<p class='course-participants'><strong>Partecipanti:</strong> " . $corso['partecipanti'] . "</p>";
    echo "</div>";
    echo "<p class='course-description'> <strong> Descrizione: </strong>" . $corso['descrizione'] . "</p>";
    
  }
  ?>
  <a href="corsi.php" class="return-button">
    <button class="back">Torna ai corsi</button>
  </a>
</div>



  <footer>
    <a href="#inizio">

      <div id="tornasu">

        <img src="immagini/su.png" class="tornasu" alt="freccia per tornare all'inizio della pagina" title="freccia">

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