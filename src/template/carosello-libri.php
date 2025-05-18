<section>
    <div class="container pt-4 pb-3">
        <div class="row text-center mb-4">
            <div class="col-lg-8 col-md-10 mx-auto">
                <h1 class="fw-light display-5"><?php echo htmlspecialchars($templateParams["intestazione"]); ?></h1> 
                
                <?php if(isset($templateParams["testo"]) && !empty($templateParams["testo"])): ?>
                    <p class="lead text-muted">
                        <?php echo $templateParams["testo"]; ?>
                    </p>
                <?php endif; ?>
            </div>
        </div>

        <?php if($templateParams["libri"] === null || count($templateParams["libri"]) != 0): ?>
        <ul class="row row-cols-2 row-cols-md-3 row-cols-xl-6 g-4 m-0 list-unstyled">
            <?php foreach($templateParams["libri"] as $book): ?>
            <li class="col">
                <a href="book.php?id=<?php echo $book["Id"] ?>" class="text-decoration-none">
                    <div class="card mb-4">
                        <div class="card-img-container">
                            <img src="<?php echo UPLOAD_DIR . $book["Cover"] ?>" class="card-img-top" alt="Copertina Libro">
                        </div>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($book["Title"]); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($book["Author_First_name"] . " " . $book["Author_Last_name"]); ?></p>
                            <p class="card-text"><?php echo htmlspecialchars($book["Price"]); ?> €</p>
                        </div>
                    </div>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>
        <?php endif; ?>
    </div>
</section>
