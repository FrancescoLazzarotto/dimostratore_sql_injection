<?php
// gestisce le azioni post/get - versione sicura

require('../config/config.php');
require('../libs/functions.php');

session_start();
$autenticato = isset($_SESSION['loggedin']) ? $_SESSION['loggedin'] : false;
$id_utente   = isset($_SESSION['user_id'])  ? $_SESSION['user_id']  : 0;
$azione      = isset($_GET['action'])       ? $_GET['action']       : '';

// controllo di accesso di base
if ($azione === '' ||
    (!$autenticato && $azione !== 'login') ||
    ($autenticato  && $azione === 'login')) {
    header('Location: ../index.php');
    exit();
}

// login
if ($azione === 'login') {
    $nome_utente = isset($_POST['username']) ? $_POST['username'] : '';
    $password    = isset($_POST['pwd'])      ? $_POST['pwd']      : '';

    $dati_utente = verifica_login($nome_utente, $password);

    if ($dati_utente) {
        $_SESSION['loggedin']     = true;
        $_SESSION['user_id']      = $dati_utente['id'];
        $_SESSION['username']     = $dati_utente['username'];
        $_SESSION['nome']         = $dati_utente['nome_completo'];
        $_SESSION['ruolo']        = $dati_utente['ruolo'];
        $_SESSION['input_usato']  = $nome_utente;
        header('Location: ../dashboard.php');
    } else {
        $_SESSION['input_tentato'] = $nome_utente;
        header('Location: ../index.php?msg=' . MESSAGGIO_ERRORE_LOGIN);
    }
    exit();
}

// logout
if ($azione === 'logout') {
    session_destroy();
    header('Location: ../index.php?msg=' . MESSAGGIO_LOGOUT);
    exit();
}

header('Location: ../index.php');
exit();
?>
