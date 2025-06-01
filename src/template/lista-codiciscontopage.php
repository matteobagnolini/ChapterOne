<section class="container my-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="mb-0">Gestione Codici Sconto</h1>
        <a href="accountadmin.php" class="btn btn-outline-secondary">
            <span class="bi bi-arrow-left-circle me-1"></span> Torna al Pannello Admin
        </a>
    </div>
    <p class="mb-4">Seleziona un codice sconto per modificarlo, attivarlo/disattivarlo o eliminarlo, oppure crea un nuovo codice sconto.</p>
    
    <?php if (empty($templateParams["codicisconto"])): ?>
        <div class="alert alert-info" role="alert">
            Nessun codice sconto trovato.
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th scope="col" id="id">ID</th>
                        <th scope="col" id="codice">Codice</th>
                        <th scope="col" id="tipo">Tipo</th>
                        <th scope="col" id="valore">Valore</th>
                        <th scope="col" id="data-inizio">Data Inizio</th>
                        <th scope="col" id="data-fine">Data Fine</th>
                        <th scope="col" id="uso-singolo">Uso Singolo</th>
                        <th scope="col" id="attivo">Attivo</th>
                        <th scope="col" id="azioni">Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($templateParams["codicisconto"] as $discountCode): ?>
                    <tr>
                        <th scope="row" id="row-<?php echo $discountCode["Id"]; ?>"><?php echo $discountCode["Id"]; ?></th>
                        <td headers="row-<?php echo $discountCode["Id"]; ?> codice"><?php echo $discountCode["Code"]; ?></td>
                        <td headers="row-<?php echo $discountCode["Id"]; ?> tipo"><?php echo ucfirst($discountCode["Type"]); ?></td>
                        <td headers="row-<?php echo $discountCode["Id"]; ?> valore">
                            <?php 
                                if ($discountCode["Type"] == 'percentage') {
                                    echo $discountCode["Value"] . "%";
                                } else {
                                    echo number_format($discountCode["Value"], 2, ',', '.') . " €";
                                }
                            ?>
                        </td>
                        <td headers="row-<?php echo $discountCode["Id"]; ?> data-inizio">
                            <?php 
                                $startDate = new DateTime($discountCode["Start_date"]);
                                echo $startDate->format('d/m/Y');
                            ?>
                        </td>
                        <td headers="row-<?php echo $discountCode["Id"]; ?> data-fine">
                            <?php 
                                $endDate = new DateTime($discountCode["End_date"]);
                                echo $endDate->format('d/m/Y');
                            ?>
                        </td>
                        <td headers="row-<?php echo $discountCode["Id"]; ?> uso-singolo">
                            <span class="badge bg-<?php echo $discountCode["Single_use"] ? 'success' : 'secondary'; ?>">
                                <?php echo $discountCode["Single_use"] ? 'Sì' : 'No'; ?>
                            </span>
                        </td>
                        <td headers="row-<?php echo $discountCode["Id"]; ?> attivo">
                            <span class="badge bg-<?php echo $discountCode["Active"] ? 'success' : 'danger'; ?>">
                                <?php echo $discountCode["Active"] ? 'Sì' : 'No'; ?>
                            </span>
                        </td>
                        <td headers="row-<?php echo $discountCode["Id"]; ?> azioni">
                            <a href="gestisci-codice-sconto.php?id=<?php echo $discountCode["Id"]; ?>" class="btn btn-primary btn-sm me-2 mb-1" title="Modifica Codice Sconto">
                                <span class="bi bi-pencil-square"></span> <span class="d-none d-md-inline">Modifica</span>
                            </a>
                            <a href="utils/delete-discountcode.php?id=<?php echo $discountCode["Id"]; ?>" class="btn btn-danger btn-sm mb-1" onclick="return confirm('Sei sicuro di voler eliminare questo codice sconto?');" title="Elimina Codice Sconto">
                                <span class="bi bi-trash"></span> <span class="d-none d-md-inline">Elimina</span>
                            </a>
                            <a href="utils/toggle-discountcode.php?id=<?php echo $discountCode["Id"]; ?>&current_status=<?php echo $discountCode["Active"] ? '1' : '0'; ?>" class="btn btn-<?php echo $discountCode["Active"] ? 'warning' : 'success'; ?> btn-sm mb-1" title="<?php echo $discountCode["Active"] ? 'Disattiva' : 'Attiva'; ?>">
                                <span class="bi bi-<?php echo $discountCode["Active"] ? 'toggle-off' : 'toggle-on'; ?>"></span> <span class="d-none d-md-inline"><?php echo $discountCode["Active"] ? 'Disattiva' : 'Attiva'; ?></span>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
    
    <a href="crea-codice-sconto.php" class="btn btn-success mt-3">
        <span class="bi bi-plus-circle me-1"></span> Crea un nuovo codice sconto
    </a>
</section>