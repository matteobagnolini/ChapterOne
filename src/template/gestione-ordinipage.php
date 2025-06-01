<section class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="mb-0">Gestione Ordini</h1>
        <a href="accountadmin.php" class="btn btn-outline-secondary">
            <span class="bi bi-arrow-left-circle me-1"></span> Torna al Pannello Admin
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
                <caption class="visually-hidden">
                    Elenco degli ordini ricevuti, con informazioni sul cliente, data, stato e azioni disponibili.
                </caption>
                <thead class="table-dark">
                    <tr>
                        <th id="intestazione-id" scope="col">ID Ordine</th>
                        <th id="intestazione-cliente" scope="col">Cliente</th>
                        <th id="intestazione-email" scope="col">Email Cliente</th>
                        <th id="intestazione-data" scope="col">Data</th>
                        <th id="intestazione-totale" scope="col">Totale</th>
                        <th id="intestazione-stato" scope="col">Stato</th>
                        <th id="intestazione-azioni" scope="col">Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($templateParams["ordini"] as $ordine): ?>
                    <tr>
                        <th id="riga-<?php echo $ordine['Id']; ?>" scope="row"><?php echo $ordine["Id"]; ?></th>
                        <td headers="riga-<?php echo $ordine['Id']; ?> intestazione-cliente"><?php echo $ordine["Customer_Name"]; ?></td>
                        <td headers="riga-<?php echo $ordine['Id']; ?> intestazione-email"><?php echo $ordine["Customer_Email"]; ?></td>
                        <td headers="riga-<?php echo $ordine['Id']; ?> intestazione-data">
                            <?php 
                                $date = new DateTime($ordine["Date"]);
                                echo $date->format('d/m/Y H:i');
                            ?>
                        </td>
                        <td headers="riga-<?php echo $ordine['Id']; ?> intestazione-totale"><?php echo number_format($ordine["Total"], 2, ',', '.'); ?> €</td>
                        <td headers="riga-<?php echo $ordine['Id']; ?> intestazione-stato">
                            <span class="badge bg-<?php
                                switch ($ordine['Status']) {
                                    case 'pending': echo 'warning text-dark'; break;
                                    case 'sent': echo 'info text-dark'; break;
                                    case 'arrived': echo 'success'; break;
                                    default: echo 'secondary'; break;
                                }
                            ?>"><?php echo ucfirst($ordine['Status']); ?></span>
                        </td>
                        <td headers="riga-<?php echo $ordine['Id']; ?> intestazione-azioni">
                            <a href="orderdetails.php?id_order=<?php echo $ordine["Id"]; ?>" class="btn btn-primary btn-sm me-2" title="Vedi Dettagli">
                                <span class="bi bi-eye"></span> <span class="d-none d-md-inline">Dettagli</span>
                            </a>
                            <?php
                                $nextStatus = '';
                                $btnText = '';
                                $btnClass = '';
                                if ($ordine['Status'] == 'pending') {
                                    $nextStatus = 'sent';
                                    $btnText = 'Spedisci';
                                    $btnClass = 'info';
                                } elseif ($ordine['Status'] == 'sent') {
                                    $nextStatus = 'arrived';
                                    $btnText = 'Consegnato';
                                    $btnClass = 'success';
                                }
                            ?>
                            <?php if ($nextStatus): ?>
                                <a href="utils/update-stato-ordine.php?id_order=<?php echo $ordine["Id"]; ?>&next_status=<?php echo $nextStatus; ?>" class="btn btn-<?php echo $btnClass; ?> btn-sm" title="Imposta come <?php echo ucfirst($nextStatus); ?>">
                                    <span class="bi bi-arrow-repeat"></span> <span class="d-none d-md-inline"><?php echo $btnText; ?></span>
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        </div>
    <?php endif; ?>
</section>