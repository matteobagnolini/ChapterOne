<article class="container mt-4">
            <section class="row mb-4">
                <div class="col-md-4">
                    <img src="<?php echo UPLOAD_DIR . $book["Cover"] ?>" class="img-fluid rounded shadow" alt="Copertina Libro">
                </div>
                <div class="col-md-8 d-flex flex-column justify-content-between">
                    <div>
                        <h1><?php echo htmlspecialchars($templateParams["libro"]["Title"]); ?></h1>
                        <p><strong>Autore:</strong> <?php echo htmlspecialchars($templateParams["libro"]["Author_full_name"]); ?></p>
                        <p><strong>Casa Editrice:</strong> <?php echo htmlspecialchars($templateParams["libro"]["Publisher_name"]); ?></p>
                        <p><strong>Prezzo:</strong> € <?php echo number_format($templateParams["libro"]["Price"], 2, ',', '.'); ?></p>
                    </div>
                    <div>
                        <?php
                            $bookDetails = $templateParams["libro"];
                            $excerptFilename = $bookDetails["Exceptr"] ?? null; // Assicurati che 'Excerpt_filename' sia la chiave corretta
                            $bookId = $bookDetails["Id"] ?? null;
                            $baseExcerptPath = UPLOAD_DIR;
                        
                        ?>

                                                <form action="utils/cart_actions.php" method="POST" class="d-flex align-items-center mb-3">
                            <input type="hidden" name="action" value="add">
                            <input type="hidden" name="book_id" value="<?php echo $bookId; ?>">
                            
                            <?php 
                            $isBookAvailable = isset($templateParams["libro"]["Product_count"]) && $templateParams["libro"]["Product_count"] > 0;
                            if (!(isset($_SESSION['admin']) && $_SESSION['admin'] === true)):
                                if ($isBookAvailable):
                            ?>
                                <div class="me-2">
                                    <label for="quantity_<?php echo $bookId; ?>" class="form-label visually-hidden">Quantità</label>
                                    <input type="number" name="quantity" id="quantity_<?php echo $bookId; ?>" class="form-control" value="1" min="1" <?php if (isset($templateParams["libro"]["Product_count"])) echo 'max="' . $templateParams["libro"]["Product_count"] . '"'; ?> style="width: 70px;" aria-label="Quantità">
                                </div>
                                <button type="submit" class="btn btn-primary"><i class="bi bi-cart-plus"></i> Aggiungi al carrello</button>
                            <?php else: ?>
                                <button type="button" class="btn btn-secondary" disabled><i class="bi bi-cart-plus"></i> Non disponibile</button>
                            <?php endif; ?>
                            <?php else: // Se è admin ?>
                                <button type="button" class="btn btn-secondary" disabled><i class="bi bi-cart-plus"></i> Aggiungi al carrello</button>
                            <?php endif; ?>
                        </form>

                        <?php if ($excerptFilename && $bookId):?>
                            <a href="<?php echo $baseExcerptPath . $excerptFilename; ?>" class="btn btn-outline-secondary" download="<?php echo $bookDetails["Title"] . "_Preview" . ".txt"; ?>">
                                <i class="bi bi-file-earmark-arrow-down"></i> Scarica estratto
                            </a>
                        <?php else: ?>
                            <button class="btn btn-outline-secondary" disabled><i class="bi bi-file-earmark-arrow-down"></i> Estratto non disponibile</button>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
            
            <section class="mb-4">
                <h2>Descrizione</h2>
                <p><?php echo $templateParams["libro"]["Description"]; ?></p>
            </section>

            <section class="mb-4">
                <h2>Recensioni</h2>

            <?php 
                if ($templateParams["abilitarecensione"]):
            ?>
                <div class="mb-4">
                    <button class="btn btn-outline-primary mb-3" type="button" data-bs-toggle="collapse" data-bs-target="#reviewForm" aria-expanded="false" aria-controls="reviewForm">
                    <i class="bi bi-pen"></i> Scrivi una recensione
                    </button>
            
                    <div class="collapse" id="reviewForm">
                        <div class="card card-body">
                            <form action="utils/processa-recensione.php" method="POST">
                            <input type="hidden" name="book_id" value="<?php echo $bookId; ?>">
                        
                            <div class="mb-3">
                                <label for="rating" class="form-label">Valutazione</label>
                                <select class="form-select" id="rating" name="rating" required>
                                    <option value="" selected disabled>Seleziona una valutazione</option>
                                    <option value="1">1 stella</option>
                                    <option value="2">2 stelle</option>
                                    <option value="3">3 stelle</option>
                                    <option value="4">4 stelle</option>
                                    <option value="5">5 stelle</option>
                                </select>
                            </div>
                        
                            <div class="mb-3">
                                <label for="review_text" class="form-label">La tua recensione</label>
                                <textarea class="form-control" id="review_text" name="review_text" rows="4" required></textarea>
                            </div>
                        
                            <button type="submit" class="btn btn-primary">Pubblica recensione</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endif; ?>


                <ul class="list-group"></ul>
                <?php foreach($templateParams["recensioni"] as $recensione): ?>
                    <li class="list-group-item">
                        <article>
                            <header class="d-flex align-items-center">
                                <h3 class="h5 mb-0 me-2"><?php echo $recensione["Customer_full_name"]; ?></h3>
                                <div class="text-warning">
                                    <?php for($i = 0; $i < $recensione["Rating"]; $i++): ?>
                                    <i class="bi bi-star-fill"></i>
                                    <?php endfor; ?>
                                </div>
                            </header>
                            <p><?php echo $recensione["Text"]; ?></p>
                        </article>
                    </li>
                <?php endforeach; ?>
            </section>
            
            <section>
                <h2>Dello stesso autore:</h2>
                <div>
                    <ul class="row row-cols-2 row-cols-md-3 row-cols-xl-6 g-4 m-0 list-unstyled">
                    <?php foreach($templateParams["librisimili"] as $book): ?>
                    <li class="col">
                        <a href="book.php?id=<?php echo $book["Id"] ?>" class="text-decoration-none">
                            <div class="card mb-4">
                                <div class="card-img-container">
                            <img src="<?php echo UPLOAD_DIR . $book["Cover"] ?>" class="card-img-top" alt="Copertina Libro">
                                </div>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $book["Title"] ?></h5>
                                <p class="card-text"><?php echo $book["Author_name"] ?></p>
                                <p class="card-text"><?php echo $book["Price"] ?> €</p>
                        </div>
                    </div>
                </a>
            </li>
            <?php endforeach; ?>
                    </ul>
                </div>
            </section>
        </article>
