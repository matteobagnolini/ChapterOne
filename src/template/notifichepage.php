<div class="container my-4">
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="mb-4">Notifiche</h1>
                
                <section class="notification-section">
                    <ul class="list-unstyled">
                       
                        <?php foreach($templateParams["notifiche"] as $notification): ?>
                        <li class="card mb-3 shadow-sm">
                            <article class="card-body">
                                <h2 class="h5 card-title"><?php echo htmlspecialchars($notification["Preview"]); ?></h2>
                                <p class="card-text"><?php echo htmlspecialchars($notification["Message"]); ?></p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <time class="text-muted small" datetime="<?php echo htmlspecialchars(date('c', strtotime($notification["Date"]))); ?>"><?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($notification["Date"]))); ?></time>
                                    <a href="orderdetails.php?id_order=<?php echo htmlspecialchars($notification["Order_id"]); ?>" class="btn btn-primary btn-sm">
                                        Vedi Dettagli Ordine
                                    </a>
                                </div>
                            </article>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </section>
</div>