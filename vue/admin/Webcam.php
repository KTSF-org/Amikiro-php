<?php /** VUE : Admin / Webcam */ ?>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-7">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="mb-0 h3">Configuration Webcam</h1>
                    <small class="text-muted">Administration · <?= htmlspecialchars(APP_NAME) ?></small>
                </div>
                <a href="<?= $actual_link ?>accueil" class="btn btn-outline-secondary btn-sm px-3">← Retour</a>
            </div>

            <?php if ($success): ?>
                <div class="alert alert-success">Configuration sauvegardée.</div>
            <?php elseif ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="<?= $actual_link ?>parametres/webcam">

                <!-- Flux -->
                <div class="card mb-3">
                    <div class="card-header bg-dark text-white py-2">
                        <span class="fw-semibold small">Flux vidéo</span>
                    </div>
                    <div class="card-body">
                        <label for="streamUrl" class="form-label">URL du flux</label>
                        <!-- Accepte RTSP, HLS, HTTP — interprété côté client par le lecteur vidéo -->
                        <input type="text" class="form-control" id="streamUrl" name="streamUrl"
                               value="<?= htmlspecialchars($config->streamUrl ?? '') ?>"
                               placeholder="rtsp://... ou http://...">
                    </div>
                </div>

                <!-- Session -->
                <div class="card mb-4">
                    <div class="card-header bg-dark text-white py-2">
                        <span class="fw-semibold small">Paramètres de session</span>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-6">
                                <label for="sessionDuration" class="form-label">
                                    Durée de session
                                    <small class="text-muted">(secondes)</small>
                                </label>
                                <!-- Durée maximale d'une session viewer avant expiration automatique.
                                     Contrôleur impose un minimum de 60 s. -->
                                <div class="input-group">
                                    <input type="number" class="form-control" id="sessionDuration"
                                           name="sessionDuration"
                                           value="<?= (int)($config->sessionDuration ?? 3600) ?>" min="0">
                                    <span class="input-group-text text-muted small">s</span>
                                </div>
                                <div class="form-text text-muted small">0 = illimité, sinon min. 60 s</div>
                            </div>
                            <div class="col-6">
                                <label for="viewerLimit" class="form-label">
                                    Viewers simultanés
                                </label>
                                <!-- Nombre maximum de viewers actifs en même temps.
                                     viewerCount (compteur temps réel) est géré séparément via AJAX et non modifiable ici. -->
                                <div class="input-group">
                                    <input type="number" class="form-control" id="viewerLimit"
                                           name="viewerLimit"
                                           value="<?= (int)($config->viewerLimit ?? 10) ?>" min="0">
                                    <span class="input-group-text text-muted small">max</span>
                                </div>
                                <div class="form-text text-muted small">0 = illimité</div>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary px-4">Sauvegarder</button>

            </form>

        </div>
    </div>
</div>
