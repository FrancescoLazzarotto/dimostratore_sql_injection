<?php
    require 'common/elements/header.php';

    if (!$autenticato) {
        header('Location: index.php');
        exit();
    }

    $nome_completo = isset($_SESSION['nome'])  ? $_SESSION['nome']  : '';
    $ruolo_utente  = isset($_SESSION['ruolo']) ? $_SESSION['ruolo'] : '';
    $conto_utente  = ottieni_conto_utente($id_utente);
?>

<div class="row">
    <!-- dati del conto -->
    <div class="col-sm-7">
        <h2>benvenuto, <?php echo htmlspecialchars($nome_completo); ?></h2>
        <p>
            <span class="label label-default">ruolo: <?php echo htmlspecialchars($ruolo_utente); ?></span>
            &nbsp;
            <span class="label label-default">id utente: <?php echo (int)$id_utente; ?></span>
        </p>

        <h3>il tuo conto corrente</h3>
        <?php if ($conto_utente): ?>
        <table class="table table-bordered">
            <tr>
                <th>numero conto</th>
                <td><code><?php echo htmlspecialchars($conto_utente['numero_conto']); ?></code></td>
            </tr>
            <tr>
                <th>saldo disponibile</th>
                <td class="saldo-positivo">
                    &euro; <?php echo number_format((float)$conto_utente['saldo'], 2, ',', '.'); ?>
                </td>
            </tr>
            <tr>
                <th>ultima operazione</th>
                <td><?php echo htmlspecialchars($conto_utente['ultima_operazione']); ?></td>
            </tr>
        </table>
        <?php else: ?>
        <div class="alert alert-info">nessun conto bancario associato a questo utente.</div>
        <?php endif; ?>
    </div>

    <!-- box: conferma accesso legittimo -->
    <div class="col-sm-5">
        <div class="panel panel-success">
            <div class="panel-heading">
                <strong>accesso legittimo confermato</strong>
            </div>
            <div class="panel-body">
                <?php if (isset($_SESSION['input_usato'])): ?>
                <p><strong>nome utente inserito:</strong></p>
                <pre class="query-secure"><?php echo htmlspecialchars($_SESSION['input_usato']); ?></pre>
                <hr />
                <?php endif; ?>
                <p>
                    hai effettuato l'accesso fornendo credenziali corrette.
                    l'uso dei prepared statements ha garantito che nessun payload sql
                    potesse manipolare la query di autenticazione.
                </p>
                <p>
                    qualunque tentativo di sql injection nel form di login
                    viene neutralizzato: l'input viene cercato letteralmente
                    come stringa nel database.
                </p>
            </div>
        </div>
    </div>
</div>

<?php
    require 'common/elements/footer.php';
?>
