    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <form action="#" method="POST" enctype="multipart/form-data">
                            <h2 class="card-title mb-4">Gestisci Autore</h2>
                            
                            <ul class="list-unstyled">
                                <li class="mb-3">
                                    <label for="nome" class="form-label">Nome:</label>
                                    <input type="text" class="form-control" id="nome" name="nome" required value="<?php echo $templateParams["autore"][0]["First_name"]; ?>" />
                                </li>
                                <li class="mb-3">
                                    <label for="cognome" class="form-label">Cognome:</label>
                                    <input type="text" class="form-control" id="cognome" name="cognome" required value="<?php echo $templateParams["autore"][0]["Last_name"]; ?>" />
                                </li>
                                <li class="mb-3">
                                    <label for="dataNascita" class="form-label">Data di Nascita:</label>
                                    <input type="date" class="form-control" id="dataNascita" name="dataNascita" required />
                                </li>
                                <li class="mb-3">
                                    <label for="biografia" class="form-label">Biografia (opzionale):</label>
                                    <textarea class="form-control" id="biografia" name="biografia" rows="4"></textarea>
                                </li>
                                <li class="mt-4">
                                    <input type="submit" name="submit" value="Salva Autore" class="btn btn-primary me-2" />
                                    <a href="#" class="btn btn-secondary">Annulla</a>
                                </li>
                            </ul>
                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
