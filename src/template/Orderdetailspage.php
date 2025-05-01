<?php
// filepath: c:\Users\Giuseppe\Documents\Progetti\ChapterOne\src\template\Orderdetailspage.php

// Rimosso il blocco di estrazione variabili
?>
<div class="container my-4">
    <section>
        <?php if ($templateParams["ordineInfo"]): // Usa direttamente $templateParams ?>
            <h1 class="mb-4">Dettagli Ordine #<?php echo htmlspecialchars($templateParams["ordineInfo"]['Id']); ?></h1>

            <section class="mb-5">
                <h2 class="mb-3">Articoli Acquistati</h2>
                <?php if (!empty($templateParams["dettagliordine"]) && !empty($templateParams["libriOrdine"])): // Usa direttamente $templateParams ?>
                    <ul class="list-unstyled">
                        <?php foreach($templateParams["dettagliordine"] as $detail): // Usa direttamente $templateParams ?>
                            <?php
                                // Trova le informazioni del libro corrispondente usando l'ID
                                $bookId = $detail['Book_id']; // Assicurati che la chiave sia corretta
                                // Usa direttamente $templateParams["libriOrdine"]
                                $bookInfo = $templateParams["libriOrdine"][$bookId] ?? null;
                            ?>
                            <?php if ($bookInfo): // Mostra solo se abbiamo trovato le info del libro ?>
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
                                                    <!-- Mostra il subtotale della riga d'ordine -->
                                                    Subtotale: <?php echo number_format($bookInfo["Price"], 2, ',', '.'); ?> €
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>Nessun articolo trovato per questo ordine.</p>
                <?php endif; ?>
            </section>

            <section class="card p-4 bg-light">
                <h2 class="mb-3">Riepilogo Ordine</h2>
                <p class="fw-bold">Data di acquisto:
                    <?php
                        // Usa direttamente $templateParams["ordineInfo"]
                        $date = new DateTime($templateParams["ordineInfo"]["Date"]);
                        echo $date->format('d/m/Y H:i');
                    ?>
                </p>
                <p class="fw-bold fs-5">Prezzo totale: <?php echo $templateParams["dettagliordine"][0]['Subtotal']  ?> €</p>
                <p class="fw-bold">Stato: <span class="badge bg-<?php
                    // Usa direttamente $templateParams["ordineInfo"]
                    switch ($templateParams["ordineInfo"]['Status']) {
                        case 'pending': echo 'warning text-dark'; break;
                        case 'sent': echo 'info text-dark'; break;
                        case 'arrived': echo 'success'; break;
                        default: echo 'secondary'; break;
                    }
                ?>"><?php echo ucfirst(htmlspecialchars($templateParams["ordineInfo"]['Status'])); // Usa direttamente $templateParams ?></span></p>
                 <!-- Aggiungi qui altre info se necessario, es. indirizzo spedizione, codice sconto usato -->
            </section>

        <?php else: ?>
            <h1 class="mb-4">Ordine non trovato</h1>
            <p>L'ordine richiesto non è stato trovato o non hai i permessi per visualizzarlo.</p>
            <a href="orders.php" class="btn btn-primary">Torna ai tuoi ordini</a>
        <?php endif; ?>
    </section>
</div>