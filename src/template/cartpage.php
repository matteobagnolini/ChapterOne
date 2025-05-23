<div class="container my-4">
    <section>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0">Carrello</h1>
            <a href="account.php" class="btn btn-outline-secondary">
                <i class="bi bi-person me-1"></i> Torna ad account
            </a>
        </div>
    </section>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['error']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['success']; ?>
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
                                    <a href="libro.php?id=<?php echo $item["Id"]; ?>">
                                        <figure class="m-2 m-md-3" style="max-width: 80px; margin: auto;">
                                            <img src="<?php echo UPLOAD_DIR . $item["Cover"]; ?>" alt="Copertina: <?php echo $item["Title"]; ?>" class="img-fluid rounded">
                                        </figure>
                                    </a>
                                </div>
                                <div class="col-md-10 col-lg-11">
                                    <div class="card-body">
                                        <div class="d-flex flex-column flex-md-row justify-content-between">
                                            <div>
                                                <a href="libro.php?id=<?php echo $item["Id"]; ?>" class="text-decoration-none text-dark">
                                                    <h3 class="card-title h5 mb-1"><?php echo $item["Title"]; ?></h3>
                                                </a>
                                                <p class="card-text small text-muted mb-1">Autore: <?php echo $item["Author_First_name"] . " " . $item["Author_Last_name"]; ?></p>
                                                <p class="card-text small mb-2">Prezzo unitario: <?php echo number_format($item["Price"], 2, ',', '.'); ?> €</p>
                                            </div>
                                            <div class="mt-2 mt-md-0">
                                                <form action="utils/cart_actions.php" method="POST" class="d-inline-block">
                                                    <input type="hidden" name="action" value="remove">
                                                    <input type="hidden" name="book_id" value="<?php echo $item["Id"]; ?>">


                                                    <button type="submit" class="btn btn-link text-danger btn-sm p-0" title="Elimina notifica">
                                                        <i class="bi bi-trash-fill fs-5"></i>
                                                    </button>

                                                </form>
                                            </div>
                                        </div>
                                        
                                        <form action="utils/cart_actions.php" method="POST" class="d-flex align-items-center mt-2 mb-2" style="max-width: 250px;">
                                            <input type="hidden" name="action" value="update">
                                            <input type="hidden" name="book_id" value="<?php echo $item["Id"]; ?>">
                                            <label for="quantity_<?php echo $item["Id"]; ?>" class="form-label me-2 visually-hidden">Quantità:</label>
                                            <input type="number" name="quantity" id="quantity_<?php echo $item["Id"]; ?>" class="form-control form-control-sm me-2" value="<?php echo $item["Quantity"]; ?>" min="0" <?php echo (isset($item["Product_count_actual"]) && $item["Product_count_actual"] !== null) ? 'max="' . $item["Product_count_actual"] . '"' : 'max="99"'; ?> style="width: 75px;" aria-label="Quantità per <?php echo $item["Title"]; ?>">
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
            <section class="card p-4 bg-light">
                <h2 class="mb-3">Riepilogo</h2>
                <p class="fw-bold">Totale articoli: <?php echo $templateParams["carrello"]["Item_count"]; ?></p>
                <p class="fw-bold fs-5">Prezzo totale: <?php echo number_format($templateParams["carrello"]["Subtotal"], 2, ',', '.'); ?> €</p>
                
                <form action="create_order.php" method="POST">
                    <input type="hidden" name="cart_id" value="<?php echo $templateParams["carrello"]["Id"]; ?>">
                    
                    <div class="mb-3">
                        <label for="discount_code" class="form-label">Codice Sconto (opzionale)</label>
                        <input type="text" class="form-control" id="discount_code" name="discount_code" placeholder="Inserisci il tuo codice sconto">
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">Procedi all'acquisto</button>
                </form>
            </section>
        <?php endif; ?>
</div>