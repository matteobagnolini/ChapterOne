<section class="container my-4">
            <h1 class="mb-3">Gestione codice sconti</h1>
            <p class="mb-4">Seleziona un codice sconto per modificarlo o eliminarlo, oppure crea un nuovo autore.</p>
            
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
