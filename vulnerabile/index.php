<?php
    require 'common/elements/header.php';
?>

<div class="row">
    <!-- colonna sinistra: form di login -->
    <div class="col-sm-5">
        <?php if (!$autenticato): ?>
        <h2>Accedi al tuo conto</h2>
        <form id="loginForm" method="post" action="common/dialog_manager.php?action=login">
            <p>
                <label for="username">Nome utente</label><br />
                <input id="username" name="username" type="text"
                       class="form-control" size="30" value="" />
            </p>
            <p>
                <label for="pwd">Password</label><br />
                <input id="pwd" name="pwd" type="password"
                       class="form-control" size="30" value="" />
            </p>
            <p>
                <input class="btn btn-danger" type="submit" value="accedi" />
            </p>
        </form>

        <?php else: ?>
        <h2>Sei già; autenticato.</h2>
        <p><a href="dashboard.php" class="btn btn-primary">vai al tuo conto &rarr;</a></p>
        <?php endif; ?>
    </div>

    <!-- colonna destra: info sulla vulnerabilita' e debug -->
    <div class="col-sm-7">

        <!-- box: spiegazione dell'attacco -->
        <div class="panel panel-warning">
            <div class="panel-heading">
                <strong> Sql injection</strong>
            </div>
            <div class="panel-body">
                <p>
                    Questa versione è vulnerabile a sql injection nel form di login.
                    la query sql viene costruita concatenando direttamente l'input dell'utente.
                </p>
                <p><strong>Payload da provare nel campo nome utente</strong> (password: qualsiasi):</p>
                <table class="table table-bordered table-sm">
                    <thead><tr><th>payload</th><th>effetto</th></tr></thead>
                    <tbody>
                        <tr>
                            <td><code>admin' -- </code></td>
                            <td>Accede come <em>admin</em> senza password</td>
                        </tr>
                        <tr>
                            <td><code>' OR '1'='1' -- </code></td>
                            <td>Accede come primo utente in tabella</td>
                        </tr>
                        <tr>
                            <td><code>alice' -- </code></td>
                            <td>Accede come <em>alice</em> senza password</td>
                        </tr>
                    </tbody>
                </table>
                <p class="small">
                    Il carattere <code>'</code> chiude la stringa sql; <code>--</code>
                    commenta tutto ci&ograve; che segue, eliminando il controllo sulla password.
                </p>
            </div>
        </div>

        <!-- box: query generata (mostrata dopo ogni tentativo di login) -->
        <?php if (isset($_SESSION['debug_query'])): ?>
        <div class="panel panel-danger">
            <div class="panel-heading">
                <strong> Query sql eseguita sul database</strong>
            </div>
            <div class="panel-body">
                <p class="small text-muted">
                    
                </p>
                <pre class="query-debug"><?php echo htmlspecialchars($_SESSION['debug_query']); ?></pre>
            </div>
        </div>
        <?php endif; ?>

    </div>
</div>

<?php
    require 'common/elements/footer.php';
?>
