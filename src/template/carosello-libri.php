<section>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1>Sezione Categoria: <?php echo $templateParams["categoria"]["Name"]; ?></h2>
                <p>Esplora la nostra selezione di libri per la categoria <b><?php echo $templateParams["categoria"]["Name"]; ?></b>. Trova nuove avventure e lasciati ispirare dalle storie che abbiamo scelto per te.</p>
            </div>
        </div>
        <ul class="row row-cols-2 row-cols-md-3 row-cols-xl-6 g-4 m-0 list-unstyled">
            <?php foreach($templateParams["libri"] as $book): ?>
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
