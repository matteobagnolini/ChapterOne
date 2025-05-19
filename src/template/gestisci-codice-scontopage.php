<?php
$isEditing = isset($templateParams["codicesconto"]) && !empty($templateParams["codicesconto"]);
$pageTitle = $isEditing ? "Modifica Codice Sconto" : "Crea Nuovo Codice Sconto";
$submitButtonText = $isEditing ? "Salva Modifiche" : "Crea Codice Sconto";

$defaultCode = "";
$defaultType = "percentage";
$defaultValue = "";
$defaultStartDate = date('Y-m-d');
$defaultEndDate = date('Y-m-d', strtotime('+1 month'));
$defaultSingleUse = false;
$defaultActive = true;

if ($isEditing) {
    $codice = $templateParams["codicesconto"];
    $defaultCode = htmlspecialchars($codice["Code"]);
    $defaultType = htmlspecialchars($codice["Type"]);
    $defaultValue = htmlspecialchars($codice["Value"]);
    $defaultStartDate = htmlspecialchars(date('Y-m-d', strtotime($codice["Start_date"])));
    $defaultEndDate = htmlspecialchars(date('Y-m-d', strtotime($codice["End_date"])));
    $defaultSingleUse = (bool)$codice["Single_use"];
    $defaultActive = (bool)$codice["Active"];
}
?>
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form action="gestisci-codice-sconto.php<?php echo $isEditing ? '?id=' . htmlspecialchars($codice["Id"]) : ''; ?>" method="POST">
                        <h2 class="card-title mb-4"><?php echo $pageTitle; ?></h2>
                        <?php if (isset($_SESSION['form_error_message'])): ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo htmlspecialchars($_SESSION['form_error_message']); unset($_SESSION['form_error_message']); ?>
                            </div>
                        <?php endif; ?>
                        <?php if ($isEditing): ?>
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($codice["Id"]); ?>" />
                        <?php endif; ?>
                        <ul class="list-unstyled">
                            <li class="mb-3">
                                <label for="code" class="form-label">Codice Sconto:</label>
                                <input type="text" class="form-control" id="code" name="code" required maxlength="50" value="<?php echo $defaultCode; ?>" />
                                <small class="form-text text-muted">Es. SCONTOESTATE20, max 50 caratteri.</small>
                            </li>
                            <li class="mb-3">
                                <label for="type" class="form-label">Tipo Sconto:</label>
                                <select class="form-select" id="type" name="type" required>
                                    <option value="" disabled <?php echo empty($defaultType) ? 'selected' : ''; ?>>Seleziona tipo sconto</option>
                                    <option value="percentage" <?php echo ($defaultType == 'percentage') ? 'selected' : ''; ?>>Percentuale (%)</option>
                                    <option value="fixed" <?php echo ($defaultType == 'fixed') ? 'selected' : ''; ?>>Importo Fisso (€)</option>
                                </select>
                            </li>
                            <li class="mb-3">
                                <label for="value" class="form-label">Valore Sconto:</label>
                                <input type="number" class="form-control" id="value" name="value" required step="0.01" min="0" value="<?php echo $defaultValue; ?>" />
                                <small class="form-text text-muted">Se percentuale, inserire il valore (es. 10 per 10%). Se fisso, l'importo (es. 5.50).</small>
                            </li>
                            <li class="mb-3">
                                <label for="start_date" class="form-label">Data Inizio Validità:</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" required value="<?php echo $defaultStartDate; ?>" />
                            </li>
                            <li class="mb-3">
                                <label for="end_date" class="form-label">Data Fine Validità:</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" required value="<?php echo $defaultEndDate; ?>" />
                            </li>
                            <li class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="single_use" name="single_use" value="1" <?php echo $defaultSingleUse ? 'checked' : ''; ?> />
                                <label class="form-check-label" for="single_use">Utilizzabile una sola volta per utente?</label>
                            </li>
                            <li class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="active" name="active" value="1" <?php echo $defaultActive ? 'checked' : ''; ?> />
                                <label class="form-check-label" for="active">Attivo?</label>
                                <small class="form-text text-muted d-block">Se non attivo, il codice non potrà essere utilizzato.</small>
                            </li>
                            <li class="mt-4">
                                <input type="submit" name="submit" value="<?php echo $submitButtonText; ?>" class="btn btn-primary me-2" />
                                <a href="../lista-codicisconto.php" class="btn btn-secondary">Annulla</a>
                            </li>
                        </ul>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>