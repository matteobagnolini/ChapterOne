<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form action="gestisci-autore.php?id=<?php echo htmlspecialchars($autore[0]["Id"]); ?>" method="POST">
                        <h2 class="card-title mb-4">Modifica Autore</h2>
                        <?php if (isset($_SESSION['form_error_message'])): ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo $_SESSION['form_error_message']; unset($_SESSION['form_error_message']); ?>
                            </div>
                        <?php endif; ?>
                        <ul class="list-unstyled">
                            <li class="mb-3">
                                <label for="nome" class="form-label">Nome:</label>
                                <input type="text" class="form-control" id="nome" name="nome" required value="<?php echo isset($autore["First_name"]) ? htmlspecialchars($autore["First_name"]) : ''; ?>" />
                            </li>
                            <li class="mb-3">
                                <label for="cognome" class="form-label">Cognome:</label>
                                <input type="text" class="form-control" id="cognome" name="cognome" required value="<?php echo isset($autore["Last_name"]) ? htmlspecialchars($autore["Last_name"]) : ''; ?>" />
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