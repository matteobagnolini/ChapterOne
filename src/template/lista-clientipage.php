<section class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="mb-0">Gestione Clienti</h1>
        <a href="accountadmin.php" class="btn btn-outline-secondary">
            <span class="bi bi-arrow-left-circle me-1"></span> Torna al Pannello Admin
        </a>
    </div>
    <p class="mb-4">Seleziona un cliente per visualizzare i dettagli, modificarlo o eliminarlo, oppure crea un nuovo cliente.</p>
    
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['success_message']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['error_message']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <?php if (empty($templateParams["utenti"])): ?>
        <div class="alert alert-info" role="alert">
            Nessun cliente trovato.
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th scope="col" id="id">ID</th>
                        <th scope="col" id="nome">Nome</th>
                        <th scope="col" id="cognome">Cognome</th>
                        <th scope="col" id="email">Email</th>
                        <th scope="col" id="indirizzo">Indirizzo</th>
                        <th scope="col" id="telefono">Telefono</th>
                        <th scope="col" id="azioni">Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($templateParams["utenti"] as $customer): ?>
                    <tr>
                        <th scope="row" id="row-<?php echo $customer["Id"]; ?>" headers="id"><?php echo $customer["Id"]; ?></th>
                        <td headers="nome row-<?php echo $customer["Id"]; ?>"><?php echo $customer["First_name"]; ?></td>
                        <td headers="cognome row-<?php echo $customer["Id"]; ?>"><?php echo $customer["Last_name"]; ?></td>
                        <td headers="email row-<?php echo $customer["Id"]; ?>"><?php echo $customer["Email"]; ?></td>
                        <td headers="indirizzo row-<?php echo $customer["Id"]; ?>"><?php echo $customer["Address"]; ?></td>
                        <td headers="telefono row-<?php echo $customer["Id"]; ?>"><?php echo $customer["Phone"]; ?></td>
                        <td headers="azioni row-<?php echo $customer["Id"]; ?>">
                            <a href="utils/delete-customer.php?id=<?php echo $customer["Id"]; ?>" class="btn btn-danger btn-sm mb-1" onclick="return confirm('Sei sicuro di voler eliminare questo cliente? L\'eliminazione potrebbe influenzare gli ordini e i dati associati.');" title="Elimina Cliente">
                                <span class="bi bi-trash"></span> <span class="d-none d-md-inline">Elimina</span>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>