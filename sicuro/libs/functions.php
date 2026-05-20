<?php
// versione sicura con prepared statements

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

// verifica login usando prepared statements
// l'input viene separato dalla struttura sql, impedendo injection
function verifica_login($nome_utente, $password) {
    $connessione = connetti_database();

    $istruzione = mysqli_prepare($connessione,
        "SELECT id, username, nome_completo, ruolo
         FROM utenti
         WHERE username = ?
           AND password = ?");

    mysqli_stmt_bind_param($istruzione, "ss", $nome_utente, $password);
    mysqli_stmt_execute($istruzione);

    $risultato = mysqli_stmt_get_result($istruzione);

    if ($risultato && mysqli_num_rows($risultato) > 0) {
        $riga = mysqli_fetch_assoc($risultato);
        mysqli_stmt_close($istruzione);
        mysqli_close($connessione);
        return $riga;
    }

    mysqli_stmt_close($istruzione);
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

// escapi wildcard per like in modo sicuro
function escapi_like($valore) {
    $valore = str_replace("\\", "\\\\", $valore);
    $valore = str_replace("%", "\\%", $valore);
    $valore = str_replace("_", "\\_", $valore);
    return $valore;
}

// ricerca utenti con prepared statements e like parametrizzato
function ricerca_utenti($termine, &$tempo_ms) {
    $connessione = connetti_database();

    $sql = "SELECT id, username, nome_completo, ruolo "
         . "FROM utenti "
         . "WHERE username LIKE CONCAT('%', ?, '%') ESCAPE '\\' "
         . "OR nome_completo LIKE CONCAT('%', ?, '%') ESCAPE '\\'";

    $termine = escapi_like($termine);
    $istruzione = mysqli_prepare($connessione, $sql);
    mysqli_stmt_bind_param($istruzione, "ss", $termine, $termine);

    $inizio = microtime(true);
    mysqli_stmt_execute($istruzione);
    $risultato = mysqli_stmt_get_result($istruzione);
    $tempo_ms = (microtime(true) - $inizio) * 1000;

    $righe = array();
    if ($risultato) {
        while ($riga = mysqli_fetch_assoc($risultato)) {
            $righe[] = $riga;
        }
    }

    mysqli_stmt_close($istruzione);
    mysqli_close($connessione);
    return $righe;
}
?>
