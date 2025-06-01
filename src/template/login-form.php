<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow">
                <div class="card-body p-5">
                    <h2 class="text-center mb-4">Accedi</h2>
                    
                    <?php if(isset($templateParams["errorelogin"])): ?>
                    <div class="alert alert-danger" role="alert">
                        <span class="bi bi-exclamation-triangle-fill me-2"></span>
                        <?php echo $templateParams["errorelogin"]; ?>
                    </div>
                    <?php endif; ?>
                    
                    <form action="#" method="POST">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><span class="bi bi-envelope"></span></span>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Inserisci la tua email" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><span class="bi bi-lock"></span></span>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Inserisci la tua password" required>
                            </div>
                        </div>
                        
                        
                        <div class="d-grid gap-2">
                            <button type="submit" name="submit" class="btn btn-primary btn-lg">
                                <span class="bi bi-box-arrow-in-right me-2"></span>Accedi
                            </button>
                        </div>
                    </form>
                    
                    <hr class="my-4">
                    
                    <div class="text-center">
                        <p class="mb-0">Non hai un account?</p>
                        <a href="registrazione.php" class="btn btn-outline-secondary mt-2">
                            <span class="bi bi-person-plus me-2"></span>Registrati
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>