<section class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="mb-0">Gestione Ordini</h1>
        <a href="accountadmin.php" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left-circle me-1"></i> Torna al Pannello Admin
        </a>
    </div>
    <p class="mb-4">Visualizza i dettagli degli ordini e gestisci il loro stato.</p>
    
    <?php if (empty($templateParams["ordini"])): ?>
        <div class="alert alert-info" role="alert">
            Nessun ordine trovato.
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">ID Ordine</th>
                        <th scope="col">Cliente</th>
                        <th scope="col">Email Cliente</th>
                        <th scope="col">Data</th>
                        <th scope="col">Totale</th>
                        <th scope="col">Stato</th>
                        <th scope="col">Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($templateParams["ordini"] as $ordine): ?>
                    <tr>
                        <th scope="row"><?php echo htmlspecialchars($ordine["Id"]); ?></th>
                        <td><?php echo htmlspecialchars($ordine["Customer_Name"]); ?></td>
                        <td><?php echo htmlspecialchars($ordine["Customer_Email"]); ?></td>
                        <td>
                            <?php 
                                $date = new DateTime($ordine["Date"]);
                                echo $date->format('d/m/Y H:i');
                            ?>
                        </td>
                        <td><?php echo number_format($ordine["Total"], 2, ',', '.'); ?> €</td>
                        <td>
                            <span class="badge bg-<?php
                                switch ($ordine['Status']) {
                                    case 'pending': echo 'warning text-dark'; break;
                                    case 'sent': echo 'info text-dark'; break;
                                    case 'arrived': echo 'success'; break;
                                    default: echo 'secondary'; break;
                                }
                            ?>"><?php echo ucfirst(htmlspecialchars($ordine['Status'])); ?></span>
                        </td>
                        <td>
                            <a href="orderdetails.php?id_order=<?php echo $ordine["Id"]; ?>" class="btn btn-primary btn-sm me-2" title="Vedi Dettagli">
                                <i class="bi bi-eye"></i> <span class="d-none d-md-inline">Dettagli</span>
                            </a>
                            <a href="modifica-stato-ordine.php?id_order=<?php echo $ordine["Id"]; ?>" class="btn btn-info btn-sm" title="Modifica Stato">
                                <i class="bi bi-pencil-square"></i> <span class="d-none d-md-inline">Stato</span>
                            </a>
                            <!-- Potresti aggiungere un pulsante elimina se necessario, con conferma -->
                            <!-- 
                            <a href="elimina-ordine.php?id=<?php echo $ordine["Id"]; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Sei sicuro di voler eliminare questo ordine?');" title="Elimina Ordine">
                                <i class="bi bi-trash"></i> <span class="d-none d-md-inline">Elimina</span>
                            </a>
                            -->
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
    
   
</section>