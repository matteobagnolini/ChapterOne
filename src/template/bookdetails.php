<article class="container mt-4">
            <section class="row mb-4">
                <div class="col-md-4">
                    <img src="<?php echo UPLOAD_DIR . $book["Cover"] ?>" class="img-fluid rounded shadow" alt="Copertina del libro">
                </div>
                <div class="col-md-8 d-flex flex-column justify-content-between">
                    <div>
                        <h1><?php $book["Title"] ?></h1>
                        <p><strong>Autore:</strong> <?php echo $templateParams["libro"]["Author_full_name"]; ?></p>
                        <p><strong>Casa Editrice:</strong> <?php echo $templateParams["libro"]["Publisher_name"]; ?></p>
                        <p><strong>Prezzo:</strong> € <?php echo $templateParams["libro"]["Price"]; ?></p>
                    </div>
                    <div>
                        <button class="btn btn-primary me-2"><i class="bi bi-cart-plus"></i> Aggiungi al carrello</button>
                        <button class="btn btn-outline-secondary"><i class="bi bi-file-earmark-arrow-down"></i> Scarica estratto</button>
                    </div>
                </div>
            </section>
            
            <section class="mb-4">
                <h2>Descrizione</h2>
                <p><?php echo $templateParams["libro"]["Description"]; ?></p>
            </section>

            <section class="mb-4">
                <h2>Recensioni</h2>
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
