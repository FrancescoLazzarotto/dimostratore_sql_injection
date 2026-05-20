<?php
    require('config/config.php');
    require('libs/functions.php');

    session_start();
    $autenticato = isset($_SESSION['loggedin']) ? $_SESSION['loggedin'] : false;
    $id_utente   = isset($_SESSION['user_id'])  ? $_SESSION['user_id']  : 0;
    $nome_utente = isset($_SESSION['username']) ? $_SESSION['username'] : '';

    // reindirizza al login se non autenticato (eccetto per index.php)
    if (!$autenticato && strpos($_SERVER['REQUEST_URI'], 'index.php') === false) {
        header('Location: index.php');
        exit();
    }
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/style.css" />
    <title>BancaWeb</title>
</head>
<body>

<!-- header -->
<header>
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-8">
                <h1><a href="index.php" title="BancaWeb">BancaWeb</a></h1>
                <span class="badge-vulnerabile"> versione vulnerabile sql injection</span>
            </div>
            <?php if ($autenticato): ?>
            <div class="col-sm-4 text-right">
                <p>
                    Benvenuto, <strong><?php echo htmlspecialchars($nome_utente); ?></strong>
                    &nbsp;|&nbsp;
                    <a href="common/dialog_manager.php?action=logout">logout</a>
                </p>
                <p class="small">
                    <a href="dashboard.php">dashboard</a>
                    &nbsp;|&nbsp;
                    <a href="ricerca.php">ricerca avanzata</a>
                </p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</header>

<main>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
<?php
    if (isset($_GET['msg']) && array_key_exists($_GET['msg'], $messaggi_errore)) {
        echo $messaggi_errore[$_GET['msg']];
    }
?>
