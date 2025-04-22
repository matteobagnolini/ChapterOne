<div class="container my-4">
    <section>
        <h1 class="mb-4">Ordine #12345</h1>
        
        <section class="mb-5">
            <h2 class="mb-3">Articoli Acquistati</h2>
            
            <ul class="list-unstyled">
                <?php foreach($templateParams["ordine"] as $book): ?>
                <li class="card mb-3">
                    <div class="row g-0">
                        <div class="col-md-2">
                            <figure class="m-3">
                                <img src="<?php echo UPLOAD_DIR . $book["Cover"]; ?>" alt="Copertina libro 1" class="img-fluid rounded">
                            </figure>
                        </div>
                        <div class="col-md-10">
                            <div class="card-body">
                                <h3 class="card-title">Titolo: <?php echo $book["Title"]; ?></h3>
                                <p class="card-text">Autore: <?php echo $book["First_name"] . $book["Last_name"]; ?></p>
                                <p class="card-text">Prezzo: <?php echo $book["Price"]; ?>€</p>
                            </div>
                        </div>
                    </div>
                </li>
                <?php endforeach; ?>
            </ul>
        </section>
        
        <section class="card p-4 bg-light">
            <h2 class="mb-3">Riepilogo</h2>
            <p class="fw-bold">Totale articoli: <?php echo $templateParams["ordineNumeroLibri"]; ?></p>
            <p class="fw-bold">Data di acquisto: <?php echo $templateParams["ordineData"] ?></p>
            <p class="fw-bold">Prezzo totale: <?php echo $templateParams["ordineNumeroPrezzo"] ?>€</p>
        </section>
    </section>
</div>

