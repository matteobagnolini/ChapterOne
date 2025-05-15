<section class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow p-5">
                <h2 class="text-center mb-4">Registrazione Utente</h2>
                
                <form action="processa-registrazione.php" method="POST">
                    <fieldset>
                        <legend class="visually-hidden">Informazioni Utente</legend>
                        
                        <div class="mb-3">
                            <label for="nomeCompleto" class="form-label">Nome Completo</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" class="form-control" id="nomeCompleto" name="nomeCompleto" placeholder="Inserisci il tuo nome completo" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Inserisci la tua email" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Inserisci la tua password" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="indirizzo" class="form-label">Indirizzo</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                <input type="text" class="form-control" id="indirizzo" name="indirizzo" placeholder="Inserisci il tuo indirizzo" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="telefono" class="form-label">Telefono</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                <input type="tel" class="form-control" id="telefono" name="telefono" placeholder="Inserisci il tuo numero di telefono" required>
                            </div>
                        </div>
                    </fieldset>
                    
                    <button type="submit" class="btn btn-primary btn-lg w-100 mt-3">
                        <i class="bi bi-person-plus me-2"></i>Registrati
                    </button>
                </form>
                
                <hr class="my-4">
                
                <p class="text-center mb-0">Hai già un account?</p>
                <a href="login.php" class="btn btn-outline-secondary w-100 mt-2">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Accedi
                </a>
            </div>
        </div>
    </div>
</section>