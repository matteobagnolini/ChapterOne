
    <main>
        <div class="container py-4">
            <div class="row">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h2 class="card-title text-center mb-4">Il tuo Account</h2>
                            <div class="d-grid gap-3 mb-4">
                                <a href="../cart.php" class="btn btn-outline-primary">
                                    <i class="bi bi-cart"></i> Vai al Carrello
                                </a>
                                <a href="../orders.php" class="btn btn-outline-secondary">
                                    <i class="bi bi-clock-history"></i> Cronologia acquisti
                                </a>
                            </div>
                            
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h3 class="h5 mb-0">Informazioni sul tuo account</h3>
                                </div>
                                <div class="card-body">
                                    <form id="account-form">
                                        <fieldset>
                                            <legend class="fw-bold mb-3">Dettagli Account</legend>
                                            
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Nome</label>
                                                <input type="text" id="name" name="name" value="Mario Rossi" readonly class="form-control">
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label for="username" class="form-label">Username</label>
                                                <input type="text" id="username" name="username" value="mario.rossi" readonly class="form-control">
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label for="email" class="form-label">Email</label>
                                                <input type="email" id="email" name="email" value="mario.rossi@gmail.com" readonly class="form-control">
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label for="password" class="form-label">Password</label>
                                                <input type="password" id="password" name="password" value="password123" readonly class="form-control">
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label for="card-number" class="form-label">Numero della Carta</label>
                                                <input type="text" id="card-number" name="card-number" value="1234 5678 9012 3456" readonly class="form-control">
                                            </div>
                                        </fieldset>
                                        
                                        <div class="d-flex gap-2 mt-4">
                                            <button type="button" class="btn btn-primary">Modifica</button>
                                            <button type="submit" disabled class="btn btn-success">Salva modifiche</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
       
    </main>

