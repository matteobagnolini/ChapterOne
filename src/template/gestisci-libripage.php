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
                                    <input type="text" class="form-control" id="titoloarticolo" name="titoloarticolo" value="<?php echo ($templateParams['libro']['Title']); ?>" />
                                </li>
                                <li class="mb-3">
                                    <label for="autore" class="form-label">Autore:</label>
                                    <select class="form-select" id="autore" name="autore">
                                        <option value="">Seleziona un autore</option>
                                        <?php foreach ($templateParams["autori"] as $autore): ?>
                                            <option value="<?php echo $autore["Id"]; ?>" <?php if($autore["Id"] == $templateParams["libro"]["Author_id"]) echo "selected"; ?>>
                                                <?php echo $autore["First_name"] . " " . $autore["Last_name"]; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </li>
                                <li class="mb-3">
                                    <label for="casaeditrice" class="form-label">Casa Editrice:</label>
                                    <select class="form-select" id="casaeditrice" name="casaeditrice">
                                        <option value="">Seleziona una casa editrice</option>
                                        <?php foreach ($templateParams["case_editrici"] as $casa): ?>
                                            <option value="<?php echo $casa['Id']; ?>" <?php if($casa['Id'] == $templateParams["libro"]["Publisher_id"]) echo "selected"; ?>>
                                                <?php echo $casa['Name']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>                                
                                </li>
                                <li class="mb-3">
                                    <label for="categoria" class="form-label">Categoria:</label>
                                    <select class="form-select" id="categoria" name="categoria">
                                        <option value="">Seleziona una categoria</option>
                                        <?php foreach ($templateParams["categorie"] as $categoria): ?>
                                            <option value="<?php echo $categoria['Id']; ?>" <?php if($categoria['Id'] == $templateParams["libro"]["Category_id"]) echo "selected"; ?>>
                                                <?php echo $categoria['Name']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>                                
                                </li>
                                <li class="mb-3">
                                    <label for="prezzo" class="form-label">Prezzo:</label>
                                    <input type="number" step="0.01" class="form-control" id="prezzo" name="prezzo" value="<?php echo ($templateParams['libro']['Price']); ?>" />
                                </li>
                                <li class="mb-3">
                                    <label for="quantita" class="form-label">Quantità:</label>
                                    <input type="number" step="1" class="form-control" id="quantita" name="quantita" value="<?php echo isset($templateParams['libro']['Product_count']) ? ($templateParams['libro']['Product_count']) : '0'; ?>" />
                                </li>
                                <li class="mb-3">
                                    <label for="descrizione" class="form-label">Descrizione:</label>
                                    <textarea class="form-control" id="descrizione" name="descrizione" rows="4"><?php echo ($templateParams['libro']['Description']); ?></textarea>
                                </li>
                                <li class="mb-3">
                                    <label for="copertina" class="form-label">Copertina:</label>
                                    <input type="file" class="form-control" name="copertina" id="copertina" />
                                    <?php if (!empty($templateParams['libro']['Cover'])): ?>
                                        <small>Copertina attuale: <?php echo ($templateParams['libro']['Cover']); ?></small>
                                    <?php endif; ?>
                                </li>
                                <li class="mb-3">
                                    <label for="estratto" class="form-label">Estratto del libro:</label>
                                    <input type="file" class="form-control" name="estratto" id="estratto" />
                                    <?php if (!empty($templateParams['libro']['Exceptr'])): ?>
                                        <small>Estratto attuale: <?php echo ($templateParams['libro']['Exceptr']); ?></small>
                                    <?php endif; ?>
                                </li>
                                <li class="mt-4">
                                    <input type="submit" name="submit" value="Salva Libro" class="btn btn-primary me-2" />
                                    <a href="lista-libri.php" class="btn btn-secondary">Annulla</a>
                                </li>
                            </ul>
                            
                            <input type="hidden" name="idlibro" value="<?php echo ($templateParams['libro']['Id']); ?>" />
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
