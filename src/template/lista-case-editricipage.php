<section class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="mb-0">Gestione Case Editrici</h1>
        <a href="accountadmin.php" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left-circle me-1"></i> Torna al Pannello Admin
        </a>
    </div>
    <p class="mb-4">Seleziona una casa editrice per modificarla o eliminarla, oppure crea una nuova casa editrice.</p>
    
    <?php if (empty($templateParams["caseeditrici"])): ?>
        <div class="alert alert-info" role="alert">
            Nessuna casa editrice trovata.
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <caption class="visually-hidden">
                    Elenco delle case editrici con nome, indirizzo e azioni disponibili.
                </caption>
                <thead class="table-dark">
                    <tr>
                        <th id="intestazione-id" scope="col">ID</th>
                        <th id="intestazione-nome" scope="col">Nome</th>
                        <th id="intestazione-indirizzo" scope="col">Indirizzo</th>
                        <th id="intestazione-azioni" scope="col">Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($templateParams["caseeditrici"] as $publisher): ?>
                    <tr>
                        <th id="riga-<?php echo $publisher['Id']; ?>" scope="row"><?php echo htmlspecialchars($publisher["Id"]); ?></th>
                        <td headers="riga-<?php echo $publisher['Id']; ?> intestazione-nome"><?php echo htmlspecialchars($publisher["Name"]); ?></td>
                        <td headers="riga-<?php echo $publisher['Id']; ?> intestazione-indirizzo"><?php echo htmlspecialchars($publisher["Address"]); ?></td>
                        <td headers="riga-<?php echo $publisher['Id']; ?> intestazione-azioni">
                            <a href="../gestisci-casa-editrice.php?id=<?php echo $publisher["Id"]; ?>" class="btn btn-primary btn-sm me-2 mb-1" title="Modifica Casa Editrice">
                                <i class="bi bi-pencil-square"></i> <span class="d-none d-md-inline">Modifica</span>
                            </a>
                            <a href="utils/delete-casa-editrice.php?id=<?php echo $publisher["Id"]; ?>" class="btn btn-danger btn-sm mb-1" onclick="return confirm('Sei sicuro di voler eliminare questa casa editrice? L\'eliminazione potrebbe influenzare i libri associati.');" title="Elimina Casa Editrice">
                                <i class="bi bi-trash"></i> <span class="d-none d-md-inline">Elimina</span>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        </div>
    <?php endif; ?>
    
    <a href="crea-casa-editrice.php" class="btn btn-success mt-3">
        <i class="bi bi-plus-circle me-1"></i> Crea una nuova casa editrice
    </a>
</section>