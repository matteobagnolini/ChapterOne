<div class="container-fluid py-4"></div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <form action="#" method="POST" enctype="multipart/form-data">
                            <h2 class="card-title mb-4">Gestisci Libro</h2>
                            
                            <ul class="list-unstyled">
                                <li class="mb-3">
                                    <label for="titoloarticolo" class="form-label">Titolo:</label>
                                    <input type="text" class="form-control" id="titoloarticolo" name="titoloarticolo" placeholder="Titolo del libro" />
                                </li>
                                <li class="mb-3">
                                    <label for="autore" class="form-label">Autore:</label>
                                    <select class="form-select" id="autore" name="autore">
                                        <option selected>Seleziona un autore</option>
                                        <?php foreach ($templateParams["autori"] as $autore): ?>
                                            <option value="<?php echo $autore["Id"]; ?>">
                                                <?php echo $autore["First_name"] . " " . $autore["Last_name"]; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </li>
                                <li class="mb-3">
                                    <label for="casaeditrice" class="form-label">Casa Editrice:</label>
                                    <select class="form-select" id="casaeditrice" name="casaeditrice">
                                        <option selected>Seleziona una casa editrice</option>
                                        <?php foreach ($templateParams["case_editrici"] as $casa): ?>
                                            <option value="<?php echo $casa['Id']; ?>">
                                                <?php echo $casa['Name']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>                                
                                </li>
                                <li class="mb-3">
                                    <label for="categoria" class="form-label">Categoria:</label>
                                    <select class="form-select" id="categoria" name="categoria">
                                        <option selected>Seleziona una categoria</option>
                                        <?php foreach ($templateParams["categorie"] as $categoria): ?>
                                            <option value="<?php echo $categoria['Id']; ?>">
                                                <?php echo $categoria['Name']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>                                
                                </li>
                                <li class="mb-3">
                                    <label for="prezzo" class="form-label">Prezzo:</label>
                                    <input type="number" step="0.01" class="form-control" id="prezzo" name="prezzo" value="19.99" />
                                </li>
                                <li class="mb-3">
                                    <label for="descrizione" class="form-label">Descrizione:</label>
                                        <textarea class="form-control" id="descrizione" name="descrizione" rows="4" placeholder="Descrizione del libro"></textarea>
                                </li>
                                <li class="mb-3">
                                    <label for="copertina" class="form-label">Copertina:</label>
                                    <input type="file" class="form-control" name="copertina" id="copertina" />
                                </li>
                                <li class="mb-3">
                                    <label for="estratto" class="form-label">Estratto del libro:</label>
                                    <input type="file" class="form-control" name="estratto" id="estratto" />
                                </li>
                                <li class="mt-4">
                                    <input type="submit" name="submit" value="Salva Libro" class="btn btn-primary me-2" />
                                    <a href="lista-libri.php" class="btn btn-secondary">Annulla</a>
                                </li>
                            </ul>
                            
                            <input type="hidden" name="idlibro" value="123" />
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
