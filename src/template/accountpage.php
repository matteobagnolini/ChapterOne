<div class="container py-4">
    <div class="row">
        <div class="col-lg-8 offset-lg-2"> 
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="card-title mb-0">Il tuo Account</h2>
                        <a href="logout.php" class="btn btn-danger">
                            <i class="bi bi-box-arrow-right me-1"></i> Logout
                        </a>
                    </div>
                    <div class="d-grid gap-3 mb-4">
                        <a href="cart.php" class="btn btn-outline-primary">
                            <i class="bi bi-cart"></i> Vai al Carrello
                        </a>
                        <a href="../orders.php" class="btn btn-outline-secondary">
                            <i class="bi bi-clock-history"></i> Cronologia acquisti
                        </a>
                    </div>

   
                    <?php if (isset($_SESSION['update_message'])): ?>
                        <div class="alert alert-<?php echo $_SESSION['update_message_type'] ?? 'info'; ?> alert-dismissible fade show" role="alert">
                            <?php echo htmlspecialchars($_SESSION['update_message']); ?>
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

                            <form id="account-form" method="POST" action="../update-account.php">
                                <fieldset>
                                    <legend class="fw-bold mb-3">Dettagli Account</legend>

                                    <div class="mb-3">
                                        <label for="name" class="form-label">Nome Completo</label>
                                        <input type="text" id="name" name="name"
                                            value="<?php echo htmlspecialchars($templateParams["accountInfo"]["First_name"] . ' ' . $templateParams["accountInfo"]["Last_name"]); ?>"
                                            class="form-control" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($templateParams["accountInfo"]["Email"]); ?>" class="form-control" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <!-- Campo pre-compilato con la password attuale (mascherata) -->
                                        <input type="password" id="password" name="password"
                                               value="<?php echo htmlspecialchars($templateParams["accountInfo"]["Password"]); // Visualizza la password attuale ?>"
                                               class="form-control">
                                        <div id="passwordHelpBlock" class="form-text">
                                          Modifica questo campo solo se desideri cambiare la password.
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="address" class="form-label">Indirizzo</label>
                                        <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($templateParams["accountInfo"]["Address"]); ?>" class="form-control">
                                    </div>

                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Telefono</label>
                                        <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($templateParams["accountInfo"]["Phone"]); ?>" class="form-control">
                                    </div>
                                </fieldset>

                                <div class="d-flex gap-2 mt-4">
                       
                                    <button type="submit" name="action" value="update_account" class="btn btn-secondary" disabled>
                                        <i class="bi bi-check-lg me-1"></i> Salva modifiche
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('account-form');
    const saveButton = form.querySelector('button[type="submit"][name="action"][value="update_account"]');
    const inputFields = form.querySelectorAll('input[type="text"], input[type="email"], input[type="password"], input[type="tel"]');
    let originalValues = {};

    inputFields.forEach(field => {
        originalValues[field.id] = field.value;
    });

    // Disabilita e imposta stile iniziale (grigio)
    saveButton.disabled = true;
    // La classe iniziale è già btn-secondary dall'HTML

    function checkChanges() {
        let hasChanges = false;
        inputFields.forEach(field => {
            if (field.value !== originalValues[field.id]) {
                hasChanges = true;
            }
        });

        // Abilita/disabilita e cambia colore
        if (hasChanges) {
            saveButton.disabled = false;
            saveButton.classList.remove('btn-secondary'); // Rimuovi grigio
            saveButton.classList.add('btn-success');     // Aggiungi verde
        } else {
            saveButton.disabled = true;
            saveButton.classList.remove('btn-success');   // Rimuovi verde
            saveButton.classList.add('btn-secondary');  // Aggiungi grigio
        }
    }

    inputFields.forEach(field => {
        field.addEventListener('input', checkChanges);
    });
});
</script>