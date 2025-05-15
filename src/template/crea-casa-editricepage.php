<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form action="crea-casa-editrice.php" method="POST">
                        <h2 class="card-title mb-4">Aggiungi Casa Editrice</h2>
                        <?php if (isset($_SESSION['form_error_message'])): ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo $_SESSION['form_error_message']; unset($_SESSION['form_error_message']); ?>
                            </div>
                        <?php endif; ?>
                        <ul class="list-unstyled">
                            <li class="mb-3">
                                <label for="nome" class="form-label">Nome:</label>
                                <input type="text" class="form-control" id="nome" name="nome" required value="<?php echo isset($publisher['Name']) ? htmlspecialchars($publisher['Name']) : ''; ?>" />
                            </li>
                            <li class="mb-3">
                                <label for="indirizzo" class="form-label">Indirizzo:</label>
                                <input type="text" class="form-control" id="indirizzo" name="indirizzo" required value="<?php echo isset($publisher['Address']) ? htmlspecialchars($publisher['Address']) : ''; ?>" />
                            </li>
                            <li class="mt-4">
                                <input type="submit" name="submit" value="Aggiungi Casa Editrice" class="btn btn-success me-2" />
                                <a href="lista-case-editrici.php" class="btn btn-secondary">Annulla</a>
                            </li>
                        </ul>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>