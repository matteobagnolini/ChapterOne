<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow">
                <div class="card-body p-5">
                    <h2 class="text-center mb-4">Accedi</h2>
                    
                    <?php if(isset($templateParams["errorelogin"])): ?>
                    <div class="alert alert-danger" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <?php echo $templateParams["errorelogin"]; ?>
                    </div>
                    <?php endif; ?>
                    
                    <form action="#" method="POST">
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
                        
                        
                        <div class="d-grid gap-2">
                            <button type="submit" name="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Accedi
                            </button>
                        </div>
                    </form>
                    
                    <hr class="my-4">
                    
                    <div class="text-center">
                        <p class="mb-0">Non hai un account?</p>
                        <a href="register.php" class="btn btn-outline-secondary mt-2">
                            <i class="bi bi-person-plus me-2"></i>Registrati
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>