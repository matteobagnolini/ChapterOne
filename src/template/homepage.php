<section>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1>Benvenuti su ChapterOne</h1>
                <p>ChapterOne è il tuo negozio online di fiducia per libri di ogni genere. Scopri i nostri bestseller, le ultime novità e le categorie più popolari.</p>
            </div>
        </div>
    </div>
</section>

<section>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2>Bestseller</h2>
            </div>
        </div>
        <ul class="row row-cols-2 row-cols-md-3 row-cols-xl-6 g-4 m-0 list-unstyled">
            <?php foreach($templateParams["bestsellers"] as $book): ?>
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
    </div>
</section>

<section>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2>Novità</h2>
            </div>
        </div>
        <ul class="row row-cols-2 row-cols-md-3 row-cols-xl-6 g-4 m-0 list-unstyled">
            <?php foreach($templateParams["novità"] as $book): ?>
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
    </div>
</section>
