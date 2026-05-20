<?php
// versione vulnerabile a sql injection per via della concatenazione diretta

function connetti_database() {
    $connessione = mysqli_connect(
        $GLOBALS['dbhost'],
        $GLOBALS['dbuser'],
        $GLOBALS['dbpwd'],
        $GLOBALS['dbname']
    );
    if (!$connessione) {
        die("connessione al database fallita: " . mysqli_connect_error());
    }
    return $connessione;
}

// vulnerabile: concatenazione diretta dell'input nella query.
// un attaccante può inserire caratteri sql speciali (es. ',--).
// ad es. username="admin' --" trasforma la query in:
// SELECT ... WHERE username='admin' -- ' AND password='x'
function verifica_login($nome_utente, $password) {
    $connessione = connetti_database();

    $query = "SELECT id, username, nome_completo, ruolo "
           . "FROM utenti "
           . "WHERE username='" . $nome_utente . "' "
           . "AND password='" . $password . "'";

    $_SESSION['debug_query'] = $query;

    $risultato = mysqli_query($connessione, $query);

    if ($risultato && mysqli_num_rows($risultato) > 0) {
        $riga = mysqli_fetch_assoc($risultato);
        mysqli_close($connessione);
        return $riga;
    }

    mysqli_close($connessione);
    return false;
}

// recupera i dati del conto bancario dell'utente loggato
function ottieni_conto_utente($id_utente) {
    $connessione = connetti_database();
    $istruzione = mysqli_prepare($connessione,
        "SELECT numero_conto, saldo, ultima_operazione
         FROM conti WHERE id_utente = ?");
    mysqli_stmt_bind_param($istruzione, "i", $id_utente);
    mysqli_stmt_execute($istruzione);
    $risultato = mysqli_stmt_get_result($istruzione);
    $riga = mysqli_fetch_assoc($risultato);
    mysqli_stmt_close($istruzione);
    mysqli_close($connessione);
    return $riga;
}

// vulnerabile: concatenazione diretta dell'input nella query
function ricerca_utenti($termine, &$query_debug, &$tempo_ms, &$messaggio_errore) {
    $connessione = connetti_database();

    $query = "SELECT id, username, nome_completo, ruolo "
           . "FROM utenti "
           . "WHERE username LIKE '%" . $termine . "%' "
           . "OR nome_completo LIKE '%" . $termine . "%'";

    $query_debug = $query;

    $inizio = microtime(true);
    $risultato = mysqli_query($connessione, $query);
    $tempo_ms = (microtime(true) - $inizio) * 1000;

    $righe = array();
    $messaggio_errore = '';
    if ($risultato) {
        while ($riga = mysqli_fetch_assoc($risultato)) {
            $righe[] = $riga;
        }
    } else {
        $messaggio_errore = mysqli_error($connessione);
    }

    mysqli_close($connessione);
    return $righe;
}
?>
