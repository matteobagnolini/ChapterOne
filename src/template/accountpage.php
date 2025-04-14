<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <title>Home</title>
    <style>
        .card-img-container {
            height: 300px; /* Imposta un'altezza fissa per il contenitore delle immagini */
            overflow: hidden; /* Nasconde le parti dell'immagine che escono dal contenitore */
        }
        .card-img-top {
            height: 100%; /* Imposta l'altezza dell'immagine al 100% del contenitore */
            object-fit: cover; /* Adatta l'immagine all'interno del contenitore */
            width: 100%; /* Assicura che l'immagine occupi tutta la larghezza del contenitore */
        }
    </style>
</head>

<body>
    
    <main>
        <div class="container py-4">
            <div class="row">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h2 class="card-title text-center mb-4">Il tuo Account</h2>
                            <div class="d-grid gap-3 mb-4">
                                <a href="#" class="btn btn-outline-primary">
                                    <i class="bi bi-cart"></i> Vai al Carrello
                                </a>
                                <a href="#" class="btn btn-outline-secondary">
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


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-TcB5vHnxnKlW1qS6WX1kzeF7L/ZFZ2pT3zYWE7GvJm7XwnR2s4vqJ2UmBa4/qnHp" crossorigin="anonymous"></script>
</body>
</html>