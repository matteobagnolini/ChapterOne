<section class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="mb-0">Gestione Codici Sconto</h1>
        <a href="accountadmin.php" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left-circle me-1"></i> Torna al Pannello Admin
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
                        <th scope="col">ID</th>
                        <th scope="col">Codice</th>
                        <th scope="col">Tipo</th>
                        <th scope="col">Valore</th>
                        <th scope="col">Data Inizio</th>
                        <th scope="col">Data Fine</th>
                        <th scope="col">Uso Singolo</th>
                        <th scope="col">Attivo</th>
                        <th scope="col">Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($templateParams["codicisconto"] as $discountCode): ?>
                    <tr>
                        <th scope="row"><?php echo htmlspecialchars($discountCode["Id"]); ?></th>
                        <td><?php echo htmlspecialchars($discountCode["Code"]); ?></td>
                        <td><?php echo ucfirst(htmlspecialchars($discountCode["Type"])); ?></td>
                        <td>
                            <?php 
                                if ($discountCode["Type"] == 'percentage') {
                                    echo htmlspecialchars($discountCode["Value"]) . "%";
                                } else {
                                    echo number_format($discountCode["Value"], 2, ',', '.') . " €";
                                }
                            ?>
                        </td>
                        <td>
                            <?php 
                                $startDate = new DateTime($discountCode["Start_date"]);
                                echo $startDate->format('d/m/Y');
                            ?>
                        </td>
                        <td>
                            <?php 
                                $endDate = new DateTime($discountCode["End_date"]);
                                echo $endDate->format('d/m/Y');
                            ?>
                        </td>
                        <td>
                            <span class="badge bg-<?php echo $discountCode["Single_use"] ? 'success' : 'secondary'; ?>">
                                <?php echo $discountCode["Single_use"] ? 'Sì' : 'No'; ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-<?php echo $discountCode["Active"] ? 'success' : 'danger'; ?>">
                                <?php echo $discountCode["Active"] ? 'Sì' : 'No'; ?>
                            </span>
                        </td>
                        <td>
                            <a href="gestisci-codice-sconto.php?id=<?php echo $discountCode["Id"]; ?>" class="btn btn-primary btn-sm me-2 mb-1" title="Modifica Codice Sconto">
                                <i class="bi bi-pencil-square"></i> <span class="d-none d-md-inline">Modifica</span>
                            </a>
                            <a href="elimina-codice-sconto.php?id=<?php echo $discountCode["Id"]; ?>" class="btn btn-danger btn-sm mb-1" onclick="return confirm('Sei sicuro di voler eliminare questo codice sconto?');" title="Elimina Codice Sconto">
                                <i class="bi bi-trash"></i> <span class="d-none d-md-inline">Elimina</span>
                            </a>
                            <!-- Potresti aggiungere un pulsante per attivare/disattivare rapidamente -->
                            <a href="attiva-disattiva-codice.php?id=<?php echo $discountCode["Id"]; ?>&current_status=<?php echo $discountCode["Active"]; ?>" class="btn btn-<?php echo $discountCode["Active"] ? 'warning' : 'success'; ?> btn-sm mb-1" title="<?php echo $discountCode["Active"] ? 'Disattiva' : 'Attiva'; ?>">
                                <i class="bi bi-<?php echo $discountCode["Active"] ? 'toggle-off' : 'toggle-on'; ?>"></i> <span class="d-none d-md-inline"><?php echo $discountCode["Active"] ? 'Disattiva' : 'Attiva'; ?></span>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
    
    <a href="crea-codice-sconto.php" class="btn btn-success mt-3">
        <i class="bi bi-plus-circle me-1"></i> Crea un nuovo codice sconto
    </a>
</section>