<div class="container my-4">
    <section>
        <?php if ($templateParams["ordineInfo"]): ?>
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="mb-0">Dettagli Ordine #<?php echo htmlspecialchars($templateParams["ordineInfo"]['Id']); ?></h1>
                <?php if ($templateParams["isAdminView"]): ?>
                    <a href="lista-ordini.php" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Torna alla lista ordini
                    </a>
                <?php else: ?>
                    <a href="lista-ordini.php" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Torna agli ordini
                    </a>
                <?php endif; ?>
            </div>

            <section class="mb-5">
                <h2 class="mb-3">Articoli Acquistati</h2>
                <?php if (!empty($templateParams["dettagliordine"]) && !empty($templateParams["libriOrdine"])): ?>
                    <ul class="list-unstyled">
                        <?php foreach($templateParams["dettagliordine"] as $detail): ?>
                            <?php
                                $bookId = $detail['Book_id'];
                                $bookInfo = $templateParams["libriOrdine"][$bookId] ?? null;
                            ?>
                            <?php if ($bookInfo): ?>
                               
                                <a href="book.php?id=<?php echo htmlspecialchars($bookId); ?>" class="text-decoration-none text-dark d-block">
                                    <li class="card mb-3">
                                        <div class="row g-0">
                                            <div class="col-md-2 d-flex align-items-center justify-content-center">
                                                <figure class="m-3" style="max-width: 100px;">
                                                    <img src="<?php echo UPLOAD_DIR . htmlspecialchars($bookInfo["Cover"]); ?>" alt="Copertina <?php echo htmlspecialchars($bookInfo["Title"]); ?>" class="img-fluid rounded">
                                                </figure>
                                            </div>
                                            <div class="col-md-10">
                                                <div class="card-body">
                                                    <h3 class="card-title h5"><?php echo htmlspecialchars($bookInfo["Title"]); ?></h3>
                                                    <p class="card-text mb-1">
                                                        Autore: <?php echo htmlspecialchars(($bookInfo["Author_First_name"] ?? '') . ' ' . ($bookInfo["Author_Last_name"] ?? 'N/D')); ?>
                                                    </p>
                                                    <p class="card-text mb-1">
                                                        Quantità: <?php echo htmlspecialchars($detail["Quantity"]); ?>
                                                    </p>
                                                    <p class="card-text fw-bold">
                                                        Subtotale: <?php echo number_format($detail["Subtotal"], 2, ',', '.'); ?> €
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </a> 
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>Nessun articolo trovato per questo ordine.</p>
                <?php endif; ?>
            </section>

           
            <section class="card p-4 bg-light mb-4">
                <h2 class="mb-3">Riepilogo Ordine</h2>
                <p class="fw-bold">Totale articoli: <?php echo htmlspecialchars($templateParams["totaleArticoli"]); ?></p>
                <p class="fw-bold">Data di acquisto:
                    <?php
                        $date = new DateTime($templateParams["ordineInfo"]["Date"]);
                        echo $date->format('d/m/Y H:i');
                    ?>
                </p>
                <p class="fw-bold fs-5">Prezzo totale: <?php echo number_format($templateParams["ordineInfo"]["Total"], 2, ',', '.'); ?> €</p>
                <p class="fw-bold">Stato: <span class="badge bg-<?php
                    switch ($templateParams["ordineInfo"]['Status']) {
                        case 'pending': echo 'warning text-dark'; break;
                        case 'sent': echo 'info text-dark'; break;
                        case 'arrived': echo 'success'; break;
                        default: echo 'secondary'; break;
                    }
                ?>"><?php echo ucfirst(htmlspecialchars($templateParams["ordineInfo"]['Status'])); ?></span></p>
            </section>

            <div class="text-center">
                <?php if ($templateParams["isAdminView"]): ?>
                    <a href="lista-ordini.php" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Torna alla lista ordini
                    </a>
                <?php else: ?>
                    <a href="lista-ordini.php" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Torna agli ordini
                    </a>
                <?php endif; ?>
            </div>

        <?php else: ?>
            <h1 class="mb-4">Ordine non trovato</h1>
            <p>L'ordine richiesto non è stato trovato o non hai i permessi per visualizzarlo.</p>
            <?php if ($templateParams["isAdminView"]): ?>
                <a href="lista-ordini.php" class="btn btn-primary">Torna alla lista ordini</a>
            <?php else: ?>
                <a href="lista-ordini.php" class="btn btn-primary">Torna ai tuoi ordini</a>
            <?php endif; ?>
        <?php endif; ?>
    </section>
</div>