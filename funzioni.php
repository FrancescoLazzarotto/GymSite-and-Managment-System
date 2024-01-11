<?php
/*
istruzioni di debugging 
 ini_set('display_errors', 1);
 ini_set('display_startup_errors', 1);
 error_reporting(E_ALL);
 mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);  
*/


//funzione per connettersi al database 
function connessione_database()
{
    $server = "localhost";
    $user = "root";
    $password = "";
    $database = "palestralazzarotto";

    $conn = mysqli_connect($server, $user, $password, $database) or die("Problema di connessione" . mysqli_connect_error());
    return $conn;
}




//funzione per creare nuovo utente 
function nuovoUtente($nome, $cognome, $username, $password, $mail, $data)
{  //inserisci nuovo utente nel database (no admin)
    $connessione = connessione_database();

    $query = "SELECT * FROM utenti WHERE username = '$username' OR mail = '$mail'"; //query di controllo per far si che non si crei un utente già esistente
    $risultato = mysqli_query($connessione, $query);

    if (mysqli_num_rows($risultato) == 1) { //se da risultati ritorno false perchè esiste già un utente 
        return false;
        //se i dati non sono vuoti 
    } else if (!empty($nome) && !empty($cognome) && !empty($username) && !empty($password) && !empty($mail) && !empty($data)) {

        //query 
        $query = "INSERT INTO utenti (nome,cognome,username,password,mail,data_nascita,amministratore)
                VALUES ('$nome', '$cognome', '$username' , '$password', '$mail', '$data', 0)";
        $risultato = mysqli_query($connessione, $query);
    } else {
        $risultato = false; //dati non validi 
    }

    mysqli_close($connessione);
    return ($risultato);
}



//funzione per ritornare tutti i corsi ordinati per l'id 
function listaCorsi()
{
    $connessione = connessione_database();
    $query = "select * from Corso order by id_Corso";
    $risultato = mysqli_query($connessione, $query);
    $return = []; //array per i risultato 

    if (mysqli_num_rows($risultato) > 0) { // se ci sono risultato 

        while ($row = mysqli_fetch_assoc($risultato)) {  // restituisce un array associativo 
            array_push($return, $row);  // mette la nuova riga in fondo all'array 
        }
        mysqli_close($connessione);
    }
    return $return;
}


//funzione per ritornare tutti i corsi presenti sul db 
function listaAdmin()
{
    $connessione = connessione_database();
    $query = "select * from Corso";
    $risultato = mysqli_query($connessione, $query);
    $return = []; //array per i risultati 

    if (mysqli_num_rows($risultato) > 0) { //se ci sono risultati 

        while ($row = mysqli_fetch_assoc($risultato)) {
            array_push($return, $row);
        }
        mysqli_close($connessione);
    }
    return $return;
}



//funzione per cancellare un corso dal database in base all'id 
function cancellaCorso($id_corso)
{
    $conn = connessione_database();

    $query = "DELETE FROM Corso WHERE id_corso = ?";
    $stmt = mysqli_prepare($conn, $query);
    //preparazione della query e blinding del parametro 
    mysqli_stmt_bind_param($stmt, "i", $id_corso);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) { //se ci sono risultati chiusura dello statement e della connessione 
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return true;
    } else {
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return false;
    }

}

//funzione per inserire un nuovo corso all'interno del database 
function nuovoCorso($nome, $tipologia, $descrizione, $partecipanti, $orario)
{
    $connessione = connessione_database();
    $query = "INSERT INTO corso (nome, tipologia, descrizione, partecipanti, orario) VALUES ('$nome', '$tipologia', '$descrizione', '$partecipanti', '$orario')";
    $risultato = mysqli_query($connessione, $query);
    if ($risultato == true) {
        return true;
    } else { // restituisce stringa con le informazioni di debug e l'errore specifico 
        return mysqli_debug($risultato) . mysqli_error($connessione);
    }
}







//funzione per recuperare e poi stampare le assistenze presenti nel db
function recuperaAssistenza()
{
    $assistenzaData = array(); //preparo un array per le assistenze 


    $connessione = connessione_database();

    // query per recuperare i dati dalla tabella "assistenza"
    $query = "SELECT id_assistenza, username_ass, mail_ass, assistenza_richiesta FROM assistenza";
    $result = mysqli_query($connessione, $query);

    if ($result) { //se c'è risultato popolo l'array 
        while ($row = mysqli_fetch_assoc($result)) {
            $assistenzaData[] = $row;
        }
    }

   
    mysqli_close($connessione);

    return $assistenzaData;
}



//funzione per cancellare le assistenze presenti nel db in base all'id 
function cancellaAssistenza($id_assistenza)
{
    $conn = connessione_database();

    //query per eliminare le assistenze dalla tabella assistenza in base all'id 
    $query = "DELETE FROM assistenza WHERE id_assistenza = ?";

    //preparo lo statement con la query e lo eseguo
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id_assistenza);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {  // se ci sono risultati chiudo lo statement, la connessione e ritorno true 
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return true;
    } else {
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return false;
    }

}
//funzione per controllare l'esistenza di un utente nel DB
function controllaUtente($username, $password, )
{
    $conn = connessione_database();

    $query = "SELECT * FROM utenti WHERE username = '$username'";
    $res = mysqli_query($conn, $query);
    if (mysqli_num_rows($res) != 0) { // controllo che abbia trovato un utente 

        $row = mysqli_fetch_assoc($res); //recupero i dati e metto in un array associativo 
        mysqli_close($conn);

        if ($row['password'] != $password) { //controllo psw 
            return "Password errata";
        }
        return $row; // se le psw coincidono ritorno i dati all'utente
    } else {
        mysqli_close($conn);
        return false; // nessun utente 
    }

}
//funzione per recuperare i dati di un utente 
function recuperaUtente($username)
{
    $conn = connessione_database();
    //query per recuperare i dati 
    $id_utenti = "SELECT * FROM utenti WHERE username = '$username'";
    $res = mysqli_query($conn, $id_utenti);
    //array da popolare con i dati trovati 
    $return = [];
    if (mysqli_num_rows($res) > 0) { // se ci sono risultati 

        while ($row = mysqli_fetch_assoc($res)) { //popolo l'array return con i dati 
            array_push($return, $row);
        }
        mysqli_close($conn);
    }
    return $return;
}

//funzione per cambiare username 
function CambiaUsername($username, $mail)
{
    $conn = connessione_database();

    //query per selezionare tutti i campi in base a un username specifico 
    $query = "SELECT * FROM utenti WHERE username = '$username'";
    $res = mysqli_query($conn, $query);

    if (mysqli_num_rows($res) == 1) { //se trova un risultato vuol dire che quell'username esiste già 
        $res = "duplicato";
    } elseif (!empty($username)) { //se i dati non sono vuoti eseguo la query di aggiornamento dell'username 
        $query = "UPDATE utenti SET username = '$username' WHERE mail = '$mail'";
        $res = mysqli_query($conn, $query);
    } else {
        $res = false;
    }

    mysqli_close($conn);
    return ($res);
}

//funzione per cambiare la password del proprio account 
function cambiaPsw($username, $psw)
{
    $conn = connessione_database(); //connessione al database

    //query per eseguire l'update della password 
    $query = "UPDATE utenti SET password = ? WHERE username = ?";

    //preparo lo statement e lo eseguo blindando i dati 
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ss", $psw, $username);
    mysqli_stmt_execute($stmt);

    //se da risultato chiudo lo statement la connessione e ritorno true 
    if (mysqli_stmt_affected_rows($stmt) > 0) {
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return true;
    } else {
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return false;
    }
}

//funzione per cancellare un utente dal db 
function cancellaUtente($username)
{
    $connessione = connessione_database();

    //query per eliminare un utente in base all'username 
    $query = "DELETE FROM utenti WHERE username= ?";
    //preparo lo statement (più sicuro) e lo eseguo 
    $stmt = mysqli_prepare($connessione, $query);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) { //se la query da risultati chiudo lo statement, la connessione e ritorno true 
        mysqli_stmt_close($stmt);
        mysqli_close($connessione);
        return true;
    } else { 
        mysqli_stmt_close($stmt);
        mysqli_close($connessione);
        return false;
    }
}



//funzione per controllare il tipo di utente 
function tipoUtente($username)
{
    //richiamo la funzione recuperaUtente per prelevare i dati 
    $utente = recuperaUtente($username);
    $tipo = ''; // inizializzo la variabile tipo 

    for ($i = 0; $i < sizeof($utente); $i++) {

        if (isset($username)) { // controllo l'utente 
            $tipo = 'reg';
            if ($username == 'Amministratore') {
                $tipo = 'admin';
            }
        } else {
            $tipo = 'utente';
        }

    }
    return $tipo;
}



?>