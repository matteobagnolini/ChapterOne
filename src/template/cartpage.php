        <div class="container my-4">
            <section>
                <h1 class="mb-4">Carrello</h1>
                
                <section class="mb-5">
                    <h2 class="mb-3">Articoli</h2>
                    
                    <ul class="list-unstyled">
                        <?php foreach($templateParams["libricarrello"] as $item):   ;?>
                     
                        <a href="book.php?id=<?php echo htmlspecialchars($item["Id"]); ?>" class="text-decoration-none text-dark d-block">
                            <li class="card mb-3">
                                <div class="row g-0">
                                    <div class="col-md-2">
                                        <figure class="m-3">
                                            <img src="<?php echo UPLOAD_DIR . $item["cover"] ?>" alt="Copertina libro" class="img-fluid rounded">
                                        </figure>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="card-body">
                                            <h3 class="card-title"><?php echo $item["Title"]; ?></h3>
                                            <p class="card-text"><?php echo $item["Author_First_name"] . " " . $item["Author_Last_name"]; ?></p>
                                            <p class="card-text"><?php echo $item["Price"]; ?></p>
                                            <button class="btn btn-danger btn-sm">Rimuovi</button>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </a>
                        <?php endforeach; ?>
                </section>
                
                <section class="card p-4 bg-light">
                    <h2 class="mb-3">Riepilogo</h2>
                    <p class="fw-bold">Totale articoli: <?php echo $templateParams["carrello"]["Item_count"]; ?></p>
                    <p class="fw-bold">Prezzo totale: <?php echo $templateParams["carrello"]["Subtotal"];  ?>€</p>
                    <button class="btn btn-primary mt-2">Procedi all'acquisto</button>
                </section>
            </section>
        </div>
