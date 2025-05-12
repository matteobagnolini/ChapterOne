<div class="container my-4">
    <section>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0">Carrello</h1>
            <a href="account.php" class="btn btn-outline-secondary">
                <i class="bi bi-person me-1"></i> Torna ad account
            </a>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($_SESSION['error']); ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success" role="alert">
                <?php echo htmlspecialchars($_SESSION['success']); ?>
                <?php unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
        
        <section class="mb-5">
            <h2 class="mb-3">Articoli</h2>
            
            <?php if (!empty($templateParams["libricarrello"])): ?>
                <ul class="list-unstyled">
                    <?php foreach($templateParams["libricarrello"] as $item): ?>
                        <li class="card mb-3">
                            <div class="row g-0">
                                <div class="col-md-2">
                                    <a href="book.php?id=<?php echo htmlspecialchars($item["Id"]); ?>">
                                        <figure class="m-3">
                                            <img src="<?php echo UPLOAD_DIR . htmlspecialchars($item["Cover"]); ?>" alt="Copertina <?php echo htmlspecialchars($item["Title"]); ?>" class="img-fluid rounded">
                                        </figure>
                                    </a>
                                </div>
                                <div class="col-md-10">
                                    <div class="card-body">
                                        <a href="book.php?id=<?php echo htmlspecialchars($item["Id"]); ?>" class="text-decoration-none text-dark">
                                            <h3 class="card-title"><?php echo htmlspecialchars($item["Title"]); ?></h3>
                                        </a>
                                        <p class="card-text">Autore: <?php echo htmlspecialchars($item["Author_First_name"] . " " . $item["Author_Last_name"]); ?></p>
                                        <p class="card-text">Quantità: <?php echo htmlspecialchars($item["Quantity"]); ?></p>
                                        <p class="card-text">Prezzo: <?php echo number_format($item["Price"], 2, ',', '.'); ?> €</p>
                                        <p class="card-text fw-bold">Subtotale: <?php echo number_format($item["Price"] * $item["Quantity"], 2, ',', '.'); ?> €</p>
                                        
                                        <form action="utils/cart_actions.php" method="POST">
                                            <input type="hidden" name="action" value="remove">
                                            <input type="hidden" name="book_id" value="<?php echo htmlspecialchars($item["Id"]); ?>">
                                            <input type="hidden" name="cart_id" value="<?php echo htmlspecialchars($templateParams["carrello"]["Id"]); ?>">
                                            <button type="submit" class="btn btn-danger btn-sm">Rimuovi</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <div class="alert alert-info" role="alert">
                    Il tuo carrello è vuoto. Sfoglia il nostro catalogo per aggiungere libri!
                </div>
                <div class="text-center mb-4">
                    <a href="index.php" class="btn btn-primary">Torna alla home</a>
                </div>
            <?php endif; ?>
        </section>
        
        <?php if (!empty($templateParams["libricarrello"])): ?>
            <section class="card p-4 bg-light">
                <h2 class="mb-3">Riepilogo</h2>
                <p class="fw-bold">Totale articoli: <?php echo htmlspecialchars($templateParams["carrello"]["Item_count"]); ?></p>
                <p class="fw-bold fs-5">Prezzo totale: <?php echo number_format($templateParams["carrello"]["Subtotal"], 2, ',', '.'); ?> €</p>
                
                <form action="create_order.php" method="POST">
                    <input type="hidden" name="cart_id" value="<?php echo htmlspecialchars($templateParams["carrello"]["Id"]); ?>">
                    
                    <div class="mb-3">
                        <label for="discount_code" class="form-label">Codice Sconto (opzionale)</label>
                        <input type="text" class="form-control" id="discount_code" name="discount_code" placeholder="Inserisci il tuo codice sconto">
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">Procedi all'acquisto</button>
                </form>
            </section>
        <?php endif; ?>
    </section>
</div>