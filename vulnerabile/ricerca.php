<?php
    require 'common/elements/header.php';

    $query_ricerca = isset($_GET['q']) ? $_GET['q'] : '';
    $risultati_ricerca = array();
    $query_debug = '';
    $messaggio_errore = '';
    $tempo_ms = 0.0;

    if ($query_ricerca !== '') {
        $risultati_ricerca = ricerca_utenti($query_ricerca, $query_debug, $tempo_ms, $messaggio_errore);
    }
?>

<div class="row">
    <!-- colonna sinistra: form di ricerca + risultati -->
    <div class="col-sm-5">
        <h2>ricerca utenti</h2>
        <p class="small">
            la query sql concatena direttamente l'input e permette sql injection.
        </p>

        <form id="searchForm" method="get" action="ricerca.php">
            <p>
                <label for="q">cerca per nome utente o nome</label><br />
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
                <div class="alert alert-info">nessun risultato trovato.</div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <!-- colonna destra: spiegazione + debug query -->
    <div class="col-sm-7">
        <div class="panel panel-warning">
            <div class="panel-heading">
                <strong> sql injection </strong>
            </div>
            <div class="panel-body">
                <p class="small">
                    esempi di payload nel campo ricerca:
                </p>
                <table class="table table-bordered table-sm">
                    <thead><tr><th>payload</th><th>effetto</th></tr></thead>
                    <tbody>
                        <tr>
                            <td><code>%' OR '1'='1' -- </code></td>
                            <td>bypass filtro e mostra tutti gli utenti</td>
                        </tr>
                        <tr>
                            <td><code>%' UNION SELECT id, numero_conto, saldo, ultima_operazione FROM conti -- </code></td>
                            <td>exfiltra dati dai conti</td>
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
                    <strong>&#128269; query sql eseguita</strong>
                </div>
                <div class="panel-body">
                    <p class="small text-muted">(mostrata a scopo dimostrativo.)</p>
                    <pre class="query-debug"><?php echo htmlspecialchars($query_debug); ?></pre>
                    <p class="small">
                        tempo esecuzione: <?php echo number_format($tempo_ms, 1, '.', ''); ?> ms
                    </p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
    require 'common/elements/footer.php';
?>
