<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form action="gestisci-autore.php<?php echo isset($author['Id']) && $author['Id'] ? '?id=' . $author['Id'] : ''; ?>" method="POST">
                        <h2 class="card-title mb-4">Gestisci Autore</h2>
                        <?php if (isset($_SESSION['form_error_message'])): ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo $_SESSION['form_error_message']; unset($_SESSION['form_error_message']); ?>
                            </div>
                        <?php endif; ?>
                        <?php if (isset($author['Id']) && $author['Id']): ?>
                            <input type="hidden" name="id" value="<?php echo $author['Id']; ?>" />
                        <?php endif; ?>
                        <ul class="list-unstyled">
                            <li class="mb-3">
                                <label for="first_name" class="form-label">Nome:</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" required value="<?php echo isset($author['First_name']) ? $author['First_name'] : ''; ?>" />
                            </li>
                            <li class="mb-3">
                                <label for="last_name" class="form-label">Cognome:</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" required value="<?php echo isset($author['Last_name']) ? $author['Last_name'] : ''; ?>" />
                            </li>
                            <li class="mt-4">
                                <input type="submit" name="submit" value="Salva Autore" class="btn btn-primary me-2" />
                                <a href="lista-autori.php" class="btn btn-secondary">Annulla</a>
                            </li>
                        </ul>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>