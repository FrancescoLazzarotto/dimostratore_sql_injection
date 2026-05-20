<?php
    require 'common/elements/header.php';

    $query_ricerca = isset($_GET['q']) ? $_GET['q'] : '';
    $risultati_ricerca = array();
    $query_debug = '';
    $messaggio_errore = '';
    

    if ($query_ricerca !== '') {
        $risultati_ricerca = ricerca_utenti($query_ricerca, $query_debug, $messaggio_errore);
    }
?>

<div class="row">
    <!-- colonna sinistra: form di ricerca + risultati -->
    <div class="col-sm-5">
        <h2>Ricerca utenti</h2>
        <p class="small">
            La query sql concatena direttamente l'input e permette sql injection.
        </p>

        <form id="searchForm" method="get" action="ricerca.php">
            <p>
                <label for="q">Cerca per nome utente o nome</label><br />
                <input id="q" name="q" type="text"
                       class="form-control" size="30"
                       value="<?php echo htmlspecialchars($query_ricerca); ?>" />
            </p>
            <p>
                <input class="btn btn-danger" type="submit" value="cerca" />
            </p>
        </form>

        <?php if ($query_ricerca !== ''): ?>
            <h3>risultati</h3>
            <?php if ($messaggio_errore !== ''): ?>
                <div class="alert alert-danger">
                    errore db: <?php echo htmlspecialchars($messaggio_errore); ?>
                </div>
            <?php endif; ?>

            <?php if (count($risultati_ricerca) > 0): ?>
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>id</th>
                            <th>nome utente</th>
                            <th>nome completo</th>
                            <th>ruolo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($risultati_ricerca as $riga): ?>
                            <tr>
                                <td><?php echo (int)$riga['id']; ?></td>
                                <td><?php echo htmlspecialchars($riga['username']); ?></td>
                                <td><?php echo htmlspecialchars($riga['nome_completo']); ?></td>
                                <td><?php echo htmlspecialchars($riga['ruolo']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="alert alert-info">Nessun risultato trovato.</div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <!-- colonna destra: spiegazione + debug query -->
    <div class="col-sm-7">
        <div class="panel panel-warning">
            <div class="panel-heading">
                <strong> Sql injection </strong>
            </div>
            <div class="panel-body">
                <p class="small">
                    Esempi di payload nel campo ricerca:
                </p>
                <table class="table table-bordered table-sm">
                    <thead><tr><th>Payload</th><th>Effetto</th></tr></thead>
                    <tbody>
                        <tr>
                            <td><code>%' OR '1'='1' -- </code></td>
                            <td>Bypass filtro e mostra tutti gli utenti</td>
                        </tr>
                        <tr>
                            <td><code>%' UNION SELECT id, numero_conto, saldo, ultima_operazione FROM conti -- </code></td>
                            <td>Exfiltra dati dai conti</td>
                        </tr>
                        <tr>
                            <td><code>%' OR IF(1=1, SLEEP(2), 0) -- </code></td>
                            <td>time-based: ritardo nella risposta</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <?php if ($query_ricerca !== ''): ?>
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <strong>Query sql eseguita</strong>
                </div>
                <div class="panel-body">
                    <p class="small text-muted"></p>
                    <pre class="query-debug"><?php echo htmlspecialchars($query_debug); ?></pre>
                    
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
    require 'common/elements/footer.php';
?>
