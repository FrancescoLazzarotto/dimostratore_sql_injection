<?php
    // credenziali database
    $GLOBALS['dbhost'] = "localhost";
    $GLOBALS['dbuser'] = "root";
    $GLOBALS['dbpwd']  = "";
    $GLOBALS['dbname'] = "progetto_sqli";

    // costanti messaggi
    define("MESSAGGIO_ERRORE_LOGIN", "errore_login");
    define("MESSAGGIO_LOGOUT",       "logout_ok");

    $messaggi_errore = array(
        'errore_login' => '<div class="alert alert-danger"><strong>Errore:</strong> Nome utente o password non validi.</div>',
        'logout_ok'    => '<div class="alert alert-success">Logout effettuato con successo.</div>',
    );
?>
