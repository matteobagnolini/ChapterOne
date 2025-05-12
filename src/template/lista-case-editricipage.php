<section class="container my-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="mb-0">Gestione Case Editrici</h1>
                <a href="accountadmin.php" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left-circle me-1"></i> Torna al Pannello Admin
                </a>
            </div>
            <p class="mb-4">Seleziona una casa editrice per modificarla o eliminarla, oppure crea una nuova casa editrice.</p>
            
            <!-- Elenco delle case editrici -->
            <ul class="list-unstyled">
            <?php foreach($templateParams["caseeditrici"] as $publisher): ?>
                <li class="mb-3">
                    <article class="border p-3 rounded">
                        <header class="mb-2">
                            <h2 class="h5">Nome: <?php echo $publisher["Name"]; ?></h2>
                            <p class="mb-0">Indirizzo: <?php echo $publisher["Address"]; ?></p>
                        </header>
                        <footer>
                            <a href="gestisci-casa-editrice.php?id=<?php echo $publisher["Id"]; ?>" class="btn btn-primary btn-sm me-2">Modifica</a>
                            <a href="elimina-casa-editrice.php?id=<?php echo $publisher["Id"]; ?>" class="btn btn-danger btn-sm">Elimina</a>
                        </footer>
                    </article>
                </li>
                <?php endforeach; ?>
            </ul>
        
            <!-- Pulsante per creare una nuova casa editrice -->
            <a href="crea-casa-editrice.php" class="btn btn-success">Crea una nuova casa editrice</a>
        </section>
