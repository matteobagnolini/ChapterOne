<section class="container my-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="mb-0">Gestione Autori</h1>
                <a href="accountadmin.php" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left-circle me-1"></i> Torna al Pannello Admin
                </a>
            </div>
            <p class="mb-4">Seleziona un autore per modificarlo o eliminarlo, oppure crea un nuovo autore.</p>
            
            <!-- Elenco degli autori -->
            <ul class="list-unstyled">
                <?php foreach($templateParams["autori"] as $author): ?>
                <li class="mb-3">
                    <article class="border p-3 rounded">
                        <header class="mb-2">
                            <h2 class="h5"><?php echo $author["First_name"] . " " . $author["Last_name"] ?></h2>
                        </header>
                        <footer>
                            <a href="gestisci-autore.php?id=<?php echo $author["Id"]; ?>" class="btn btn-primary btn-sm me-2">Modifica</a>
                            <a href="elimina-autore.php?id=<?php echo $author["Id"]; ?>" class="btn btn-danger btn-sm">Elimina</a>
                        </footer>
                    </article>
                </li>
                <?php endforeach; ?>
            </ul>
        
            <a href="crea-autore.html" class="btn btn-success">Crea un nuovo autore</a>
        </section>
