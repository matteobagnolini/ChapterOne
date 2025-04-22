<main>
    
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <form action="#" method="POST" enctype="multipart/form-data">
                            <h2 class="card-title mb-4">Gestisci Casa Editrice</h2>
                            
                            <ul class="list-unstyled">
                                <li class="mb-3">
                                    <label for="nome" class="form-label">Nome:</label>
                                    <input type="text" class="form-control" id="nome" name="nome" required />
                                </li>
                                <li class="mb-3">
                                    <label for="informazioni" class="form-label">Informazioni:</label>
                                    <textarea class="form-control" id="informazioni" name="informazioni" rows="4" required></textarea>
                                </li>
                                <li class="mb-3">
                                    <label for="annofondazione" class="form-label">Anno di Fondazione:</label>
                                    <input type="number" class="form-control" id="annofondazione" name="annofondazione" />
                                </li>
                                <li class="mb-3">
                                    <label for="sitoWeb" class="form-label">Sito Web:</label>
                                    <input type="url" class="form-control" id="sitoWeb" name="sitoWeb" placeholder="https://www.esempio.it" />
                                </li>
                                <li class="mt-4">
                                    <input type="submit" name="submit" value="Salva Casa Editrice" class="btn btn-primary me-2" />
                                    <a href="#" class="btn btn-secondary">Annulla</a>
                                </li>
                            </ul>
                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</main>