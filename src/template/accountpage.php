<div class="container py-4">
    <div class="row">
        <div class="col-lg-8 offset-lg-2"> 
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="card-title mb-0">Il tuo Account</h2>
                        <a href="logout.php" class="btn btn-danger">
                            <span class="bi bi-box-arrow-right me-1"></span> Logout
                        </a>
                    </div>
                    <div class="d-grid gap-3 mb-4">
                        <a href="cart.php" class="btn btn-outline-primary">
                            <span class="bi bi-cart"></span> Vai al Carrello
                        </a>
                        <a href="../orders.php" class="btn btn-outline-secondary">
                            <span class="bi bi-clock-history"></span> Cronologia acquisti
                        </a>
                    </div>

   
                    <?php if (isset($_SESSION['update_message'])): ?>
                        <div class="alert alert-<?php echo $_SESSION['update_message_type'] ?? 'info'; ?> alert-dismissible fade show" role="alert">
                            <?php echo $_SESSION['update_message']; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php
                
                            unset($_SESSION['update_message']);
                            unset($_SESSION['update_message_type']);
                        ?>
                    <?php endif; ?>


                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h3 class="h5 mb-0">Informazioni sul tuo account</h3>
                        </div>
                        <div class="card-body">

                            <form id="account-form" method="POST" action="account.php">
                                <fieldset>
                                    <legend class="fw-bold mb-3">Dettagli Account</legend>

                                    <div class="mb-3">
                                        <label for="name" class="form-label">Nome Completo</label>
                                        <input type="text" id="name" name="name"
                                            value="<?php echo $templateParams["accountInfo"]["First_name"] . ' ' . $templateParams["accountInfo"]["Last_name"]; ?>"
                                            class="form-control" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" id="email" name="email" value="<?php echo $templateParams["accountInfo"]["Email"]; ?>" class="form-control" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" id="password" name="password" value="" class="form-control" placeholder="Modifica password">
                                    </div>

                                    <div class="mb-3">
                                        <label for="address" class="form-label">Indirizzo</label>
                                        <input type="text" id="address" name="address" value="<?php echo $templateParams["accountInfo"]["Address"]; ?>" class="form-control">
                                    </div>

                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Telefono</label>
                                        <input type="tel" id="phone" name="phone" value="<?php echo $templateParams["accountInfo"]["Phone"]; ?>" class="form-control">
                                    </div>
                                </fieldset>

                                <div class="d-flex justify-content-between mt-4">
                       
                                    <button type="submit" name="action" value="update_account" class="btn btn-secondary">
                                        <span class="bi bi-check-lg me-1"></span> Salva modifiche
                                    </button>
                                    
                                    <a href="utils/delete-account.php" class="btn btn-danger" onclick="return confirm('Sei sicuro di voler eliminare il tuo account? Questa azione è irreversibile.');">
                                        <span class="bi bi-trash me-1"></span> Elimina Account
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>