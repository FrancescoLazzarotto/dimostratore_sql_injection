<?php
    require 'common/elements/header.php';

    $q = isset($_GET['q']) ? $_GET['q'] : '';
    $results = array();
    $elapsed_ms = 0.0;

    if ($q !== '') {
        $results = searchUsers($q, $elapsed_ms);
    }
?>

<div class="row">
    <!-- Colonna sinistra: form di ricerca + risultati -->
    <div class="col-sm-5">
        <h2>Ricerca utenti</h2>
        <p class="small">
            La query usa prepared statements con LIKE parametrizzato.
        </p>

        <form id="searchForm" method="get" action="ricerca.php">
            <p>
                <label for="q">Cerca per username o nome</label><br />
                <input id="q" name="q" type="text"
                       class="form-control" size="30"
                       value="<?php echo htmlspecialchars($q); ?>" />
            </p>
            <p>
                <input class="btn btn-success" type="submit" value="Cerca" />
            </p>
        </form>

        <?php if ($q !== ''): ?>
            <h3>Risultati</h3>
            <?php if (count($results) > 0): ?>
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Nome completo</th>
                            <th>Ruolo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($results as $row): ?>
                            <tr>
                                <td><?php echo (int)$row['id']; ?></td>
                                <td><?php echo htmlspecialchars($row['username']); ?></td>
                                <td><?php echo htmlspecialchars($row['nome_completo']); ?></td>
                                <td><?php echo htmlspecialchars($row['ruolo']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="alert alert-info">Nessun risultato trovato.</div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <!-- Colonna destra: spiegazione + template query -->
    <div class="col-sm-7">
        <div class="panel panel-success">
            <div class="panel-heading">
                <strong> Protezione attiva: query parametrizzata</strong>
            </div>
            <div class="panel-body">
                <p class="small">
                    Qualunque payload SQL viene trattato come stringa letterale.
                </p>
                <p><strong>Prova i payload della versione vulnerabile:</strong></p>
                <table class="table table-bordered table-sm">
                    <thead><tr><th>Payload</th><th>Risultato atteso</th></tr></thead>
                    <tbody>
                        <tr>
                            <td><code>%' OR '1'='1' -- </code></td>
                            <td class="text-danger"> Nessun bypass</td>
                        </tr>
                        <tr>
                            <td><code>%' UNION SELECT id, numero_conto, saldo, ultima_operazione FROM conti -- </code></td>
                            <td class="text-danger"> Nessuna esfiltrazione</td>
                        </tr>
                        <tr>
                            <td><code>%' OR IF(1=1, SLEEP(2), 0) -- </code></td>
                            <td class="text-danger"> Nessun ritardo</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

     

<?php
    require 'common/elements/footer.php';
?>
