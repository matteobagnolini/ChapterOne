<div class="container my-4">
    <section>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0">Carrello</h1>
            <a href="account.php" class="btn btn-outline-secondary">
                <i class="bi bi-person me-1"></i> Torna ad account
            </a>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($_SESSION['success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <?php unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
        
        <section class="mb-5">
            <h2 class="mb-3 visually-hidden">Articoli nel carrello</h2>
            
            <?php if (!empty($templateParams["libricarrello"])): ?>
                <ul class="list-unstyled">
                    <?php foreach($templateParams["libricarrello"] as $item): ?>
                        <li class="card mb-3 shadow-sm">
                            <div class="row g-0">
                                <div class="col-md-2 col-lg-1 text-center text-md-start">
                                    <a href="book.php?id=<?php echo htmlspecialchars($item["Id"]); ?>">
                                        <figure class="m-2 m-md-3" style="max-width: 80px; margin: auto;">
                                            <img src="<?php echo UPLOAD_DIR . htmlspecialchars($item["Cover"]); ?>" alt="Copertina: <?php echo htmlspecialchars($item["Title"]); ?>" class="img-fluid rounded">
                                        </figure>
                                    </a>
                                </div>
                                <div class="col-md-10 col-lg-11">
                                    <div class="card-body">
                                        <div class="d-flex flex-column flex-md-row justify-content-between">
                                            <div>
                                                <a href="book.php?id=<?php echo htmlspecialchars($item["Id"]); ?>" class="text-decoration-none text-dark">
                                                    <h3 class="card-title h5 mb-1"><?php echo htmlspecialchars($item["Title"]); ?></h3>
                                                </a>
                                                <p class="card-text small text-muted mb-1">Autore: <?php echo htmlspecialchars($item["Author_First_name"] . " " . $item["Author_Last_name"]); ?></p>
                                                <p class="card-text small mb-2">Prezzo unitario: <?php echo number_format($item["Price"], 2, ',', '.'); ?> €</p>
                                            </div>
                                            <div class="mt-2 mt-md-0">
                                                <form action="utils/cart_actions.php" method="POST" class="d-inline-block">
                                                    <input type="hidden" name="action" value="remove">
                                                    <input type="hidden" name="book_id" value="<?php echo htmlspecialchars($item["Id"]); ?>">
                                                    <button type="submit" class="btn btn-outline-danger btn-sm" aria-label="Rimuovi <?php echo htmlspecialchars($item["Title"]); ?> dal carrello">
                                                        <i class="bi bi-trash"></i> <span class="d-none d-md-inline">Rimuovi</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                        
                                        <form action="utils/cart_actions.php" method="POST" class="d-flex align-items-center mt-2 mb-2" style="max-width: 250px;">
                                            <input type="hidden" name="action" value="update">
                                            <input type="hidden" name="book_id" value="<?php echo htmlspecialchars($item["Id"]); ?>">
                                            <label for="quantity_<?php echo htmlspecialchars($item["Id"]); ?>" class="form-label me-2 visually-hidden">Quantità:</label>
                                            <input type="number" name="quantity" id="quantity_<?php echo htmlspecialchars($item["Id"]); ?>" class="form-control form-control-sm me-2" value="<?php echo htmlspecialchars($item["Quantity"]); ?>" min="0" <?php echo (isset($item["Product_count_actual"]) && $item["Product_count_actual"] !== null) ? 'max="' . htmlspecialchars($item["Product_count_actual"]) . '"' : 'max="99"'; ?> style="width: 75px;" aria-label="Quantità per <?php echo htmlspecialchars($item["Title"]); ?>">
                                            <button type="submit" class="btn btn-primary btn-sm">Aggiorna</button>
                                        </form>
                                        <p class="card-text fw-bold">Subtotale articolo: <?php echo number_format($item["Price"] * $item["Quantity"], 2, ',', '.'); ?> €</p>
                                    </div>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <div class="alert alert-info text-center" role="alert">
                    <i class="bi bi-cart-x fs-3 d-block mb-2"></i>
                    Il tuo carrello è vuoto.
                </div>
                <div class="text-center mb-4">
                    <a href="index.php" class="btn btn-primary"><i class="bi bi-book"></i> Sfoglia il catalogo</a>
                </div>
            <?php endif; ?>
        </section>
        
        <?php if (!empty($templateParams["libricarrello"])): ?>
            <section class="card p-3 p-md-4 bg-light shadow-sm">
                <h2 class="mb-3 h4">Riepilogo Ordine</h2>
                <div class="d-flex justify-content-between">
                    <span>Totale articoli:</span>
                    <span><?php echo htmlspecialchars($templateParams["carrello"]["Item_count"]); ?></span>
                </div>
                <hr class="my-2">
                <div class="d-flex justify-content-between fw-bold fs-5 mb-3">
                    <span>Prezzo totale:</span>
                    <span><?php echo number_format($templateParams["carrello"]["Subtotal"], 2, ',', '.'); ?> €</span>
                </div>
                
                <form action="checkout.php" method="POST"> <?php // Modificato create_order.php a checkout.php se più appropriato ?>
                    <?php /* <input type="hidden" name="cart_id" value="<?php echo htmlspecialchars($templateParams["carrello"]["Id"]); ?>"> */ ?>
                    
                    <div class="mb-3">
                        <label for="discount_code" class="form-label">Codice Sconto (opzionale)</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="discount_code" name="discount_code" placeholder="Es: SCONTO10">
                            <?php /* Potresti aggiungere un pulsante per applicare lo sconto via AJAX qui se vuoi
                            <button class="btn btn-outline-secondary" type="button" id="apply_discount_btn">Applica</button>
                            */ ?>
                        </div>
                         <?php if (isset($_SESSION['discount_applied_msg'])): ?>
                            <div class="alert alert-<?php echo $_SESSION['discount_applied_type'] ?? 'info'; ?> mt-2 py-1 px-2" role="alert" style="font-size: 0.9em;">
                                <?php echo htmlspecialchars($_SESSION['discount_applied_msg']); ?>
                            </div>
                            <?php unset($_SESSION['discount_applied_msg']); unset($_SESSION['discount_applied_type']); ?>
                        <?php endif; ?>
                        <?php if (isset($_SESSION['discount_error_msg'])): ?>
                            <div class="alert alert-danger mt-2 py-1 px-2" role="alert" style="font-size: 0.9em;">
                                <?php echo htmlspecialchars($_SESSION['discount_error_msg']); ?>
                            </div>
                            <?php unset($_SESSION['discount_error_msg']); ?>
                        <?php endif; ?>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100 btn-lg">
                        <i class="bi bi-credit-card"></i> Procedi all'acquisto
                    </button>
                </form>
            </section>
        <?php endif; ?>
    </section>
</div>