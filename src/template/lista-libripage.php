
<section class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="mb-0">Gestione Libri</h1>
        <a href="accountadmin.php" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left-circle me-1"></i> Torna al Pannello Admin
        </a>
    </div>
    <p class="mb-4">Seleziona un libro per modificarlo o eliminarlo, oppure crea un nuovo libro.</p>
    
    <?php if (empty($templateParams["libri"])): ?>
        <div class="alert alert-info" role="alert">
            Nessun libro trovato.
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Copertina</th>
                        <th scope="col">Titolo</th>
                        <th scope="col">Autore</th>
                        <th scope="col">Categoria</th>
                        <th scope="col">Prezzo</th>
                        <th scope="col">Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($templateParams["libri"] as $book): ?>
                    <tr>
                        <th scope="row"><?php echo htmlspecialchars($book["Id"]); ?></th>
                        <td>
                            <?php if (!empty($book["Cover"])): ?>
                                <img src="<?php echo UPLOAD_DIR . htmlspecialchars($book["Cover"]); ?>" alt="Copertina <?php echo htmlspecialchars($book["Title"]); ?>" style="width: 50px; height: auto; object-fit: cover;">
                            <?php else: ?>
                                N/D
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($book["Title"]); ?></td>
                        <td><?php echo htmlspecialchars($book["Author_name"]); ?></td>
                        <td><?php echo htmlspecialchars($book["Category_name"]); ?></td>
                        <td><?php echo number_format($book["Price"], 2, ',', '.'); ?> €</td>
                        <td>
                            <a href="gestisci-libro.php?id=<?php echo $book["Id"]; ?>" class="btn btn-primary btn-sm me-2 mb-1" title="Modifica Libro">
                                <i class="bi bi-pencil-square"></i> <span class="d-none d-md-inline">Modifica</span>
                            </a>
                            <a href="utils/delete-libro.php?id=<?php echo $book["Id"]; ?>" class="btn btn-danger btn-sm mb-1" onclick="return confirm('Sei sicuro di voler eliminare questo libro?');" title="Elimina Libro">
                                <i class="bi bi-trash"></i> <span class="d-none d-md-inline">Elimina</span>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
    
    <a href="crea-libro.php" class="btn btn-success mt-3"> <!-- Modificato da crea-libro.html a crea-libro.php se è uno script -->
        <i class="bi bi-plus-circle me-1"></i> Crea un nuovo libro
    </a>
</section>