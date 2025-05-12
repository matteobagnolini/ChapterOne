<section class="container my-4">
    <h1 class="mb-4">Risultati della Ricerca</h1>

    <?php if ($templateParams["tipoRisultato"] == "query_mancante"): ?>
        <div class="alert alert-warning" role="alert">
            Per favore, inserisci un termine da cercare.
        </div>
        <a href="index.php" class="btn btn-primary">Torna alla Home</a>
    <?php elseif (!empty($templateParams["termineRicerca"])): ?>
        <p class="lead mb-4">Risultati per: <strong><?php echo htmlspecialchars($templateParams["termineRicerca"]); ?></strong></p>

        <?php if ($templateParams["tipoRisultato"] == "libri" && !empty($templateParams["risultatiLibri"])): ?>
            <h2 class="mb-3">Libri Trovati</h2>
            <ul class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-6 g-4 m-0 list-unstyled">
                <?php foreach ($templateParams["risultatiLibri"] as $book): ?>
                <li class="col">
                    <a href="book.php?id=<?php echo $book["Id"] ?>" class="text-decoration-none">
                        <div class="card h-100 shadow-sm"> 
                            <div class="card-img-container"> 
                                <img src="<?php echo UPLOAD_DIR . htmlspecialchars($book["Cover"]); ?>" class="card-img-top" alt="Copertina di <?php echo htmlspecialchars($book["Title"]); ?>" style="object-fit: cover; height: 250px;"> 
                            </div>
                            <div class="card-body d-flex flex-column"> 
                                <h5 class="card-title" style="font-size: 1rem;"><?php echo htmlspecialchars($book["Title"]); ?></h5>
                                <?php 
                                    $authorName = trim(htmlspecialchars($book['Author_First_name'] . ' ' . $book['Author_Last_name']));
                                    if(!empty($authorName)): 
                                ?>
                                    <p class="card-text text-muted small">Di <?php echo $authorName; ?></p>
                                <?php endif; ?>
                                <p class="card-text fw-bold mt-auto"><?php echo number_format($book["Price"], 2, ',', '.'); ?> €</p>
                            </div>
                           
                        </div>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
        <?php elseif ($templateParams["tipoRisultato"] == "autori" && !empty($templateParams["risultatiAutori"])): ?>
            <h2 class="mb-3">Autori Trovati</h2>
            <div class="list-group">
                <?php foreach ($templateParams["risultatiAutori"] as $author): ?>
                    <a href="catalogo.php?author_id=<?php echo $author['Id']; ?>" class="list-group-item list-group-item-action">
                        <?php echo htmlspecialchars($author['First_name'] . ' ' . $author['Last_name']); ?>
                        <small class="d-block text-muted">Visualizza i libri di questo autore</small>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php elseif ($templateParams["tipoRisultato"] == "nessuno"): ?>
            <div class="alert alert-info mt-4" role="alert">
                Nessun risultato trovato per "<?php echo htmlspecialchars($templateParams["termineRicerca"]); ?>". Prova con termini diversi.
            </div>
        <?php endif; ?>
    <?php endif; ?>
</section>