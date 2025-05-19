<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow border-0">
                <div class="card-body text-center py-5">                    
                    <i class="bi bi-exclamation-circle text-danger mb-3" style="font-size: 3rem;"></i>
                    
                    <h2 class="mb-4">Oops! Qualcosa è andato storto</h2>
                    
                    <div class="alert alert-light border my-4">
                        <p class="mb-0 lead">
                            <?php echo isset($templateParams["errore"]) ? $templateParams["errore"] : "La risorsa richiesta non è disponibile."; ?>
                        </p>
                    </div>
                    
                    <div class="d-flex justify-content-center gap-3">
                        <a href="index.php" class="btn btn-primary">
                            <i class="bi bi-house-door me-2"></i>Torna alla Home
                        </a>
                        <a href="javascript:history.back()" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Torna indietro
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>