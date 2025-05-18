<div class="container my-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="mb-4">Notifiche</h1>

            <section class="notification-section mb-5">
                <h2>Non Lette</h2>
                <?php if (empty($templateParams["notifiche_non_lette"])): ?>
                    <p>Nessuna nuova notifica.</p>
                <?php else: ?>
                    <ul class="list-unstyled">
                       
                        <?php foreach($templateParams["notifiche_non_lette"] as $notification): ?>
                        <li class="card mb-3 shadow-sm position-relative">
                            <article class="card-body">
                                <div class="position-absolute top-0 end-0 p-2 d-flex" style="z-index: 10;">
                                    <?php if(isset($notification["Id"])): // L'ID della notifica è necessario per segnarla come letta ?>
                                    <form action="utils/mark_notification_seen.php" method="POST" class="me-1">
                                        <input type="hidden" name="notification_id" value="<?php echo htmlspecialchars($notification["Id"]); ?>">
                                        <button type="submit" class="btn btn-link text-success btn-sm p-0" title="Segna come letta">
                                           <i class="bi bi-check-circle-fill fs-5"></i>
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                    <?php if(isset($notification["Id"])): // L'ID della notifica è necessario per eliminarla ?>
                                    <form action="utils/delete_notification.php" method="POST" onsubmit="return confirm('Sei sicuro di voler eliminare questa notifica?');">
                                        <input type="hidden" name="notification_id" value="<?php echo htmlspecialchars($notification["Id"]); ?>">
                                        <button type="submit" class="btn btn-link text-danger btn-sm p-0" title="Elimina notifica">
                                           <i class="bi bi-trash-fill fs-5"></i>
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                </div>

                                <h3 class="h5 card-title"><?php echo isset($notification["Preview"]) ? htmlspecialchars($notification["Preview"]) : 'Notifica Ordine'; ?></h3>
                                <p class="card-text"><?php echo isset($notification["Message"]) ? htmlspecialchars($notification["Message"]) : 'Dettagli non disponibili.'; ?></p>
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <time class="text-muted small" datetime="<?php echo htmlspecialchars(date('c', strtotime($notification["Date"]))); ?>"><?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($notification["Date"]))); ?></time>
                                    <div>
                                        <?php if(isset($notification["Order_id"])): ?>
                                        <a href="orderdetails.php?id_order=<?php echo htmlspecialchars($notification["Order_id"]); ?>" class="btn btn-primary btn-sm me-2">
                                            Vedi Dettagli Ordine
                                        </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </article>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </section>

            <hr class="my-5">

            <section class="notification-section">
                <h2>Storico Notifiche Lette</h2>
                <?php if (empty($templateParams["notifiche_lette"])): ?>
                    <p>Nessuna notifica letta.</p>
                <?php else: ?>
                    <ul class="list-unstyled">
                        <?php foreach($templateParams["notifiche_lette"] as $notification): ?>
                        <li class="card mb-3 shadow-sm bg-light position-relative">
                            <article class="card-body">
                                <?php if(isset($notification["Id"])): // L'ID della notifica è necessario per eliminarla ?>
                                <form action="utils/delete_notification.php" method="POST" class="position-absolute top-0 end-0 p-2" style="z-index: 10;" onsubmit="return confirm('Sei sicuro di voler eliminare questa notifica?');">
                                    <input type="hidden" name="notification_id" value="<?php echo htmlspecialchars($notification["Id"]); ?>">
                                    <button type="submit" class="btn btn-link text-danger btn-sm p-0" title="Elimina notifica">
                                       <i class="bi bi-trash-fill fs-5"></i>
                                    </button>
                                </form>
                                <?php endif; ?>

                                <h3 class="h5 card-title"><?php echo isset($notification["Preview"]) ? htmlspecialchars($notification["Preview"]) : 'Notifica Ordine'; ?></h3>
                                <p class="card-text"><?php echo isset($notification["Message"]) ? htmlspecialchars($notification["Message"]) : 'Dettagli non disponibili.'; ?></p>
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <time class="text-muted small" datetime="<?php echo htmlspecialchars(date('c', strtotime($notification["Date"]))); ?>"><?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($notification["Date"]))); ?></time>
                                    <?php if(isset($notification["Order_id"])): ?>
                                    <a href="orderdetails.php?id_order=<?php echo htmlspecialchars($notification["Order_id"]); ?>" class="btn btn-secondary btn-sm">
                                        Vedi Dettagli Ordine
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </article>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </section>
        </div>
    </div>
</div>