<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <title>Carrello</title>
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
        <div class="container my-4">
            <section>
                <h1 class="mb-4">Carrello</h1>
                
                <section class="mb-5">
                    <h2 class="mb-3">Articoli</h2>
                    
                    <ul class="list-unstyled">
                        <li class="card mb-3">
                            <div class="row g-0">
                                <div class="col-md-2">
                                    <figure class="m-3">
                                        <img src="deepwork.jpg" alt="Copertina libro 1" class="img-fluid rounded">
                                    </figure>
                                </div>
                                <div class="col-md-10">
                                    <div class="card-body">
                                        <h3 class="card-title">Il Nome del Vento</h3>
                                        <p class="card-text">Autore: Patrick Rothfuss</p>
                                        <p class="card-text">Quantità: 1</p>
                                        <p class="card-text">Prezzo: 18,50€</p>
                                        <button class="btn btn-danger btn-sm">Rimuovi</button>
                                    </div>
                                </div>
                            </div>
                        </li>
                        
                        <li class="card mb-3">
                            <div class="row g-0">
                                <div class="col-md-2">
                                    <figure class="m-3">
                                        <img src="stevejobs.jpg" alt="Copertina libro 2" class="img-fluid rounded">
                                    </figure>
                                </div>
                                <div class="col-md-10">
                                    <div class="card-body">
                                        <h3 class="card-title">1984</h3>
                                        <p class="card-text">Autore: George Orwell</p>
                                        <p class="card-text">Quantità: 2</p>
                                        <p class="card-text">Prezzo: 29,00€</p>
                                        <button class="btn btn-danger btn-sm">Rimuovi</button>
                                    </div>
                                </div>
                            </div>
                        </li>
                        
                        <li class="card mb-3">
                            <div class="row g-0">
                                <div class="col-md-2">
                                    <figure class="m-3">
                                        <img src="deepwork.jpg" alt="Copertina libro 1" class="img-fluid rounded">
                                    </figure>
                                </div>
                                <div class="col-md-10">
                                    <div class="card-body">
                                        <h3 class="card-title">Il Nome del Vento</h3>
                                        <p class="card-text">Autore: Patrick Rothfuss</p>
                                        <p class="card-text">Quantità: 1</p>
                                        <p class="card-text">Prezzo: 18,50€</p>
                                        <button class="btn btn-danger btn-sm">Rimuovi</button>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
    
                </section>
                
                <section class="card p-4 bg-light">
                    <h2 class="mb-3">Riepilogo</h2>
                    <p class="fw-bold">Totale articoli: 4</p>
                    <p class="fw-bold">Prezzo totale: 72,50€</p>
                    <button class="btn btn-primary mt-2">Procedi all'acquisto</button>
                </section>
            </section>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-TcB5vHnxnKlW1qS6WX1kzeF7L/ZFZ2pT3zYWE7GvJm7XwnR2s4vqJ2UmBa4/qnHp" crossorigin="anonymous"></script>
</body>
</html>