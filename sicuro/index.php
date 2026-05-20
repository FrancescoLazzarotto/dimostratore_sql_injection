<?php
    require 'common/elements/header.php';
?>

<div class="row">
    <!-- Colonna sinistra: form di login -->
    <div class="col-sm-5">
        <?php if (!$autenticato): ?>
        <h2>accedi al tuo conto</h2>
        <form id="loginForm" method="post" action="common/dialog_manager.php?action=login">
            <p>
                <label for="username">nome utente</label><br />
                <input id="username" name="username" type="text"
                       class="form-control" size="30" value="" />
            </p>
            <p>
                <label for="pwd">password</label><br />
                <input id="pwd" name="pwd" type="password"
                       class="form-control" size="30" value="" />
            </p>
            <p>
                <input class="btn btn-success" type="submit" value="accedi" />
            </p>
        </form>

        <?php else: ?>
        <h2>sei gi&agrave; autenticato.</h2>
        <p><a href="dashboard.php" class="btn btn-primary">vai al tuo conto &rarr;</a></p>
        <?php endif; ?>
    </div>

    <!-- colonna destra: spiegazione della protezione -->
    <div class="col-sm-7">

        <!-- box: spiegazione della correzione -->
        <div class="panel panel-success">
            <div class="panel-heading">
                <strong>prepared statements</strong>
            </div>
            <div class="panel-body">
                <p>
                    in questa versione, la funzione <code>verifica_login()</code> usa
                    <strong>prepared statements</strong>. la struttura sql &egrave; fissa
                    e inviata al database separatamente dai valori dell'input.
                </p>
                <p><strong>provando i payload della versione vulnerabile vedremo come il sistema protegga l'accesso</strong></p>
                <table class="table table-bordered table-sm">
                    <thead><tr><th>payload</th><th>risultato atteso</th></tr></thead>
                    <tbody>
                        <tr>
                            <td><code>admin' -- </code></td>
                            <td class="text-danger">login fallito</td>
                        </tr>
                        <tr>
                            <td><code>' OR '1'='1' -- </code></td>
                            <td class="text-danger">login fallito</td>
                        </tr>
                        <tr>
                            <td><code>alice' -- </code></td>
                            <td class="text-danger">login fallito</td>
                        </tr>
                        <tr>
                            <td><code>admin</code> + <code>admin123</code></td>
                            <td class="text-success">login riuscito</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

<?php
    require 'common/elements/footer.php';
?>
