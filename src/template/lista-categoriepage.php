<section class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="mb-0">Gestione Categorie</h1>
        <a href="accountadmin.php" class="btn btn-outline-secondary">
            <span class="bi bi-arrow-left-circle me-1"></span> Torna al Pannello Admin
        </a>
    </div>
    <p class="mb-4">Seleziona una categoria per modificarla o eliminarla, oppure crea una nuova categoria.</p>
    
    <?php if (empty($templateParams["categorie"])): ?>
        <div class="alert alert-info" role="alert">
            Nessuna categoria trovata.
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <caption class="visually-hidden">
                    Elenco delle categorie con nome e azioni disponibili.
                </caption>
                <thead class="table-dark">
                    <tr>
                        <th id="intestazione-id" scope="col">ID</th>
                        <th id="intestazione-nome" scope="col">Nome</th>
                        <th id="intestazione-azioni" scope="col">Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($templateParams["categorie"] as $category): ?>
                    <tr>
                        <th id="riga-<?php echo $category['Id']; ?>" scope="row"><?php echo htmlspecialchars($category["Id"]); ?></th>
                        <td headers="riga-<?php echo $category['Id']; ?> intestazione-nome"><?php echo htmlspecialchars($category["Name"]); ?></td>
                        <td headers="riga-<?php echo $category['Id']; ?> intestazione-azioni">
                            <a href="utils/delete-categoria.php?id=<?php echo $category["Id"]; ?>" class="btn btn-danger btn-sm mb-1" onclick="return confirm('Sei sicuro di voler eliminare questa categoria?');" title="Elimina Categoria">
                                <span class="bi bi-trash"></span> <span class="d-none d-md-inline">Elimina</span>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
    
    <a href="crea-categorie.php" class="btn btn-success mt-3">
        <span class="bi bi-plus-circle me-1"></span> Crea una nuova categoria
    </a>
</section>