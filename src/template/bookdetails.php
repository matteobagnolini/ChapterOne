<article class="container mt-4">
            <section class="row mb-4">
                <div class="col-md-4">
                    <img src="stevejobs.jpg" class="img-fluid rounded shadow" alt="Copertina del libro">
                </div>
                <div class="col-md-8 d-flex flex-column justify-content-between">
                    <div>
                        <h1>Il Nome del Libro</h1>
                        <p><strong>Autore:</strong><?php $templateParams["libro"][0]["Author_full_name"]; ?></p>
                        <p><strong>Casa Editrice:</strong> <?php $templateParams["libro"][0]["Publisher_name"]; ?></p>
                        <p><strong>Prezzo:</strong> € <?php $templateParams["libro"][0]["Price"]; ?></p>
                    </div>
                    <div>
                        <button class="btn btn-primary me-2"><i class="bi bi-cart-plus"></i> Aggiungi al carrello</button>
                        <button class="btn btn-outline-secondary"><i class="bi bi-file-earmark-arrow-down"></i> Scarica estratto</button>
                    </div>
                </div>
            </section>
            
            <section class="mb-4">
                <h2>Descrizione</h2>
                <p><?php $templateParams["libro"][0]["Description"]; ?></p>
            </section>

            <section class="mb-4">
                <h2>Recensioni</h2>
                <ul class="list-group"></ul>
                <?php foreach($templateParams["recensioni"] as $recensione):?>
                    <li class="list-group-item">
                        <article>
                            <header class="d-flex align-items-center">
                                <h3 class="h5 mb-0 me-2">Pinco</h3>
                                <div class="text-warning">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star"></i>
                                </div>
                            </header>
                            <p>Questo libro è molto bello</p>
                        </article>
                    </li>
                    <li class="list-group-item">
                        <article>
                            <header class="d-flex align-items-center">
                                <h3 class="h5 mb-0 me-2">Pallo</h3>
                                <div class="text-warning">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star"></i>
                                    <i class="bi bi-star"></i>
                                </div>
                            </header>
                            <p>Questo libro è molto bello</p>
                        </article>
                    </li>
                    <li class="list-group-item">
                        <article>
                            <header class="d-flex align-items-center">
                                <h3 class="h5 mb-0 me-2">orso</h3>
                                <div class="text-warning">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                </div>
                            </header>
                            <p>Questo libro è molto bello</p>
                        </article>
                    </li>
                </ul>
            </section>
            
            <section>
                <h2>Dello stesso autore:</h2>
                <div>
                    <div class="row row-cols-2 row-cols-md-3 row-cols-xl-6 g-4 m-0">
                        <div class="col">
                            <div class="card mb-4">
                                <div class="card-img-container">
                                    <img src="deepwork.jpg" class="card-img-top" alt="Copertina Libro 1">
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">Titolo Libro 1</h5>
                                    <p class="card-text">Autore Libro 1</p>
                                    <p class="card-text">Prezzo: €10.00</p>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card mb-4">
                                <div class="card-img-container">
                                    <img src="stevejobs.jpg" class="card-img-top" alt="Copertina Libro 2">
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">Titolo Libro 2</h5>
                                    <p class="card-text">Autore Libro 2</p>
                                    <p class="card-text">Prezzo: €15.00</p>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card mb-4">
                                <div class="card-img-container">
                                    <img src="shining.jpg" class="card-img-top" alt="Copertina Libro 3">
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">Titolo Libro 3</h5>
                                    <p class="card-text">Autore Libro 3</p>
                                    <p class="card-text">Prezzo: €20.00</p>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card mb-4">
                                <div class="card-img-container">
                                    <img src="stevejobs.jpg" class="card-img-top" alt="Copertina Libro 4">
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">Titolo Libro 4</h5>
                                    <p class="card-text">Autore Libro 4</p>
                                    <p class="card-text">Prezzo: €25.00</p>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card mb-4">
                                <div class="card-img-container">
                                    <img src="shining.jpg" class="card-img-top" alt="Copertina Libro 5">
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">Titolo Libro 5</h5>
                                    <p class="card-text">Autore Libro 5</p>
                                    <p class="card-text">Prezzo: €30.00</p>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card mb-4">
                                <div class="card-img-container">
                                    <img src="deepwork.jpg" class="card-img-top" alt="Copertina Libro 6">
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">Titolo Libro 6</h5>
                                    <p class="card-text">Autore Libro 6</p>
                                    <p class="card-text">Prezzo: €35.00</p>
                                </div>
                            </div>
                        </div>
        
                </div>
            </section>
        </article>
