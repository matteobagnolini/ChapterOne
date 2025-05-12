
<section class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="mb-0">Gestione Autori</h1>
        <a href="accountadmin.php" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left-circle me-1"></i> Torna al Pannello Admin
        </a>
    </div>
    <p class="mb-4">Seleziona un autore per modificarlo o eliminarlo, oppure crea un nuovo autore.</p>
    
    <?php if (empty($templateParams["autori"])): ?>
        <div class="alert alert-info" role="alert">
            Nessun autore trovato.
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Nome</th>
                        <th scope="col">Cognome</th>
                        <th scope="col">Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($templateParams["autori"] as $author): ?>
                    <tr>
                        <th scope="row"><?php echo htmlspecialchars($author["Id"]); ?></th>
                        <td><?php echo htmlspecialchars($author["First_name"]); ?></td>
                        <td><?php echo htmlspecialchars($author["Last_name"]); ?></td>
                        <td>
                            <a href="gestisci-autore.php?id=<?php echo $author["Id"]; ?>" class="btn btn-primary btn-sm me-2 mb-1" title="Modifica Autore">
                                <i class="bi bi-pencil-square"></i> <span class="d-none d-md-inline">Modifica</span>
                            </a>
                            <a href="elimina-autore.php?id=<?php echo $author["Id"]; ?>" class="btn btn-danger btn-sm mb-1" onclick="return confirm('Sei sicuro di voler eliminare questo autore? L\'eliminazione potrebbe influenzare i libri associati.');" title="Elimina Autore">
                                <i class="bi bi-trash"></i> <span class="d-none d-md-inline">Elimina</span>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
    
    <a href="crea-autore.php" class="btn btn-success mt-3"> <!-- Assumendo crea-autore.php per la logica di creazione -->
        <i class="bi bi-plus-circle me-1"></i> Crea un nuovo autore
    </a>
</section>