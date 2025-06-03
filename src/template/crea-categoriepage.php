<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form action="crea-categorie.php" method="POST">
                        <h2 class="card-title mb-4">Aggiungi Categoria</h2>
                        <ul class="list-unstyled">
                            <li class="mb-3">
                                <label for="nome" class="form-label">Nome:</label>
                                <input type="text" class="form-control" id="nome" name="nome" required />
                            </li>
                            <li class="mt-4">
                                <input type="submit" name="submit" value="Aggiungi Categoria" class="btn btn-success me-2" />
                                <a href="lista-categorie.php" class="btn btn-secondary">Annulla</a>
                            </li>
                        </ul>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>