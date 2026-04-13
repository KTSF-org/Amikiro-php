<?php /** VUE : Admin / Webcam */ ?>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-7">

            <h1 class="mb-4">Configuration Webcam</h1>

            <?php if ($success): ?>
                <div class="alert alert-success">Configuration sauvegardée.</div>
            <?php elseif ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="<?= $actual_link ?>parametres/webcam">

                <div class="mb-3">
                    <label for="streamUrl" class="form-label">URL du flux</label>
                    <input type="text" class="form-control" id="streamUrl" name="streamUrl"
                           value="<?= htmlspecialchars($config->streamUrl ?? '') ?>"
                           placeholder="rtsp://... ou http://...">
                </div>

                <div class="mb-3">
                    <label for="sessionDuration" class="form-label">
                        Durée de session par défaut
                        <small class="text-muted">(secondes, min. 60)</small>
                    </label>
                    <input type="number" class="form-control" id="sessionDuration" name="sessionDuration"
                           value="<?= (int)($config->sessionDuration ?? 3600) ?>" min="60">
                </div>

                <div class="mb-4">
                    <label for="viewerLimit" class="form-label">
                        Limite de viewers par session
                        <small class="text-muted">(min. 1)</small>
                    </label>
                    <input type="number" class="form-control" id="viewerLimit" name="viewerLimit"
                           value="<?= (int)($config->viewerLimit ?? 10) ?>" min="1">
                </div>

                <button type="submit" class="btn btn-primary">Sauvegarder</button>

            </form>

        </div>
    </div>
</div>
