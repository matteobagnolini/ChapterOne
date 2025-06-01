<section class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="mb-0">Gestione Libri</h1>
        <a href="accountadmin.php" class="btn btn-outline-secondary">
            <span class="bi bi-arrow-left-circle me-1"></span> Torna al Pannello Admin
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
                <caption class="visually-hidden">
                    Elenco dei libri con informazioni dettagliate e azioni disponibili per ciascuno (modifica o eliminazione).
                </caption>
                <thead class="table-dark">
                    <tr>
                        <th id="intestazione-id" scope="col">ID</th>
                        <th id="intestazione-copertina" scope="col">Copertina</th>
                        <th id="intestazione-titolo" scope="col">Titolo</th>
                        <th id="intestazione-autore" scope="col">Autore</th>
                        <th id="intestazione-categoria" scope="col">Categoria</th>
                        <th id="intestazione-prezzo" scope="col">Prezzo</th>
                        <th id="intestazione-quantita" scope="col">Quantità</th> 
                        <th id="intestazione-azioni" scope="col">Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($templateParams["libri"] as $book): ?>
                    <tr>
                        <th id="riga-<?php echo $book['Id']; ?>" scope="row"><?php echo $book["Id"]; ?></th>
                        <td headers="riga-<?php echo $book['Id']; ?> intestazione-copertina">
                            <?php if (!empty($book["Cover"])): ?>
                                <img src="<?php echo UPLOAD_DIR . $book["Cover"]; ?>" alt="Copertina <?php echo $book["Title"]; ?>">
                            <?php else: ?>
                                N/D
                            <?php endif; ?>
                        </td>
                        <td headers="riga-<?php echo $book['Id']; ?> intestazione-titolo"><?php echo $book["Title"]; ?></td>
                        <td headers="riga-<?php echo $book['Id']; ?> intestazione-autore"><?php echo $book["Author_name"]; ?></td>
                        <td headers="riga-<?php echo $book['Id']; ?> intestazione-categoria"><?php echo $book["Category_name"]; ?></td>
                        <td headers="riga-<?php echo $book['Id']; ?> intestazione-prezzo"><?php echo number_format($book["Price"], 2, ',', '.'); ?> €</td>
                        <td headers="riga-<?php echo $book['Id']; ?> intestazione-quantita"><?php echo isset($book["Product_count"]) ? $book["Product_count"] : 'N/D'; ?></td> 
                        <td headers="riga-<?php echo $book['Id']; ?> intestazione-azioni">
                            <a href="gestisci-libro.php?id=<?php echo $book["Id"]; ?>" class="btn btn-primary btn-sm me-2 mb-1" title="Modifica Libro">
                                <span class="bi bi-pencil-square"></span> <span class="d-none d-md-inline">Modifica</span>
                            </a>
                            <a href="utils/delete-libro.php?id=<?php echo $book["Id"]; ?>" class="btn btn-danger btn-sm mb-1" onclick="return confirm('Sei sicuro di voler eliminare questo libro? L\'operazione è irreversibile.');" title="Elimina Libro">
                                <span class="bi bi-trash"></span> <span class="d-none d-md-inline">Elimina</span>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        </div>
    <?php endif; ?>
    
    <a href="crea-libro.php" class="btn btn-success mt-3">
        <span class="bi bi-plus-circle me-1"></span> Crea un nuovo libro
    </a>
</section>