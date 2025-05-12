<section class="container my-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="mb-0">Gestione Libri</h1>
                <a href="accountadmin.php" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left-circle me-1"></i> Torna al Pannello Admin
                </a>
            </div>
            <p class="mb-4">Seleziona un libro per modificarlo o eliminarlo, oppure crea un nuovo libro.</p>
            
            <ul class="list-unstyled">
                <?php foreach($templateParams["libri"] as $book): ?>
                <li class="mb-3">
                    <article class="border p-3 rounded">
                        <header class="mb-2">
                            <h2 class="h5"><?php echo $book["Title"]; ?></h2>
                            <p class="mb-0">Autore: <?php echo $book["Author_name"]; ?></p>
                        </header>
                        <footer>
                            <a href="gestisci-libro.php?id=<?php echo $book["Id"]?>" class="btn btn-primary btn-sm me-2">Modifica</a>
                            <a href="elimina-libro.php?id=<?php echo $book["Id"]?>" class="btn btn-danger btn-sm">Elimina</a>
                        </footer>
                    </article>
                </li>
                <?php endforeach; ?>
            </ul>

            <a href="crea-libro.html" class="btn btn-success">Crea un nuovo libro</a>
        </section>
