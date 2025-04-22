    <div class="container my-4">
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="mb-4">Notifiche</h1>
                
                <section class="notification-section">
                    <ul class="list-unstyled">
                        <?php foreach($templateParams["notifiche"] as $notification): ?>
                        <li class="card mb-3 shadow-sm">
                            <article class="card-body">
                                <h2 class="h5 card-title"><?php echo $notification["Preview"]; ?></h2>
                                <p class="card-text"><?php echo $notification["Message"]; ?></p>
                                <time class="text-muted small" datetime="2023-05-15T10:30:00"><?php echo $notification["Date"]; ?></time>
                            </article>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </section>
