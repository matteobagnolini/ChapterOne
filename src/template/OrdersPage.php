
<section class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0">I tuoi ordini</h1>
            <a href="account.php" class="btn btn-outline-secondary">
                <i class="bi bi-person me-1"></i> Torna ad account
            </a>
        </div>
        <p class="text-muted mb-4">Visualizza lo storico dei tuoi acquisti</p>
        <ul class="list-group mb-4">
            <?php foreach($templateParams["ordini"] as $ordine): ?>
            <li class="list-group-item list-group-item-action">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">Ordine #<?php echo $ordine["Id"]; ?></h5>
                    <small class="text-success"><?php echo $ordine["Status"]; ?></small>
                </div>
                <p class="mb-1">Data ordine: <?php echo $ordine["Date"]; ?></p>
                <p class="mb-1">Totale: € <?php echo $ordine["Total"] ?></p>
                <small>
                    <a href="orderdetails.php?id_order=<?php echo $ordine["Id"]; ?>" class="btn btn-sm btn-outline-primary mt-2">Dettagli ordine</a>
                </small>
            </li>
            <?php endforeach; ?>
</section>
