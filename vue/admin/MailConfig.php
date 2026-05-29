<?php /** VUE : Admin / Configuration mail */ ?>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-7">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="mb-0 h3">Configuration mail</h1>
                    <small class="text-muted">Administration · <?= htmlspecialchars(APP_NAME) ?></small>
                </div>
                <a href="<?= $actual_link ?>accueil" class="btn btn-outline-secondary btn-sm px-3">← Retour</a>
            </div>

            <!-- Sous-navigation admin -->
            <div class="d-flex gap-2 mb-4">
                <a href="<?= $actual_link ?>parametres/utilisateurs" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-people me-1"></i>Utilisateurs
                </a>
                <a href="<?= $actual_link ?>parametres/webcam" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-camera-video me-1"></i>Webcam
                </a>
                <span class="btn btn-dark btn-sm disabled" aria-current="page">
                    <i class="bi bi-envelope me-1"></i>Mail
                </span>
            </div>

            <?php if ($success): ?>
                <div class="alert alert-success">Configuration sauvegardée.</div>
            <?php elseif ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="<?= $actual_link ?>parametres/mail">

                <!-- Serveur SMTP -->
                <div class="card mb-3">
                    <div class="card-header bg-dark text-white py-2">
                        <span class="fw-semibold small">Serveur SMTP</span>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-8">
                                <label for="mailHost" class="form-label">
                                    Hôte SMTP <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="mailHost" name="mailHost"
                                       value="<?= htmlspecialchars($config->mailHost ?? '') ?>"
                                       placeholder="smtp-relay.brevo.com">
                            </div>
                            <div class="col-4">
                                <label for="mailPort" class="form-label">Port</label>
                                <select class="form-select" id="mailPort" name="mailPort">
                                    <?php foreach ([587 => '587 (STARTTLS)', 465 => '465 (SMTPS)', 25 => '25', 2525 => '2525'] as $p => $label): ?>
                                        <option value="<?= $p ?>" <?= (int)($config->mailPort ?? 587) === $p ? 'selected' : '' ?>>
                                            <?= $label ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Authentification -->
                <div class="card mb-3">
                    <div class="card-header bg-dark text-white py-2">
                        <span class="fw-semibold small">Authentification</span>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="mailUser" class="form-label">Identifiant SMTP</label>
                            <input type="text" class="form-control" id="mailUser" name="mailUser"
                                   value="<?= htmlspecialchars($config->mailUser ?? '') ?>"
                                   placeholder="user@example.com ou clé API">
                            <div class="form-text text-muted small">Laisser vide pour désactiver l'authentification (Mailpit / Postfix sans auth).</div>
                        </div>
                        <div class="mb-0">
                            <label for="mailPass" class="form-label">Mot de passe / Clé API</label>
                            <input type="password" class="form-control" id="mailPass" name="mailPass"
                                   placeholder="Laisser vide pour conserver le mot de passe actuel"
                                   autocomplete="new-password">
                            <div class="form-text text-muted small">Le champ vide ne modifie pas le mot de passe enregistré.</div>
                        </div>
                    </div>
                </div>

                <!-- Expéditeur -->
                <div class="card mb-4">
                    <div class="card-header bg-dark text-white py-2">
                        <span class="fw-semibold small">Expéditeur</span>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-7">
                                <label for="mailFrom" class="form-label">
                                    Adresse expéditeur <span class="text-danger">*</span>
                                </label>
                                <input type="email" class="form-control" id="mailFrom" name="mailFrom"
                                       value="<?= htmlspecialchars($config->mailFrom ?? '') ?>"
                                       placeholder="noreply@amikiro.fr">
                            </div>
                            <div class="col-5">
                                <label for="mailFromName" class="form-label">Nom affiché</label>
                                <input type="text" class="form-control" id="mailFromName" name="mailFromName"
                                       value="<?= htmlspecialchars($config->mailFromName ?? 'Amikiro') ?>"
                                       placeholder="Amikiro">
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary px-4">Sauvegarder</button>

            </form>

        </div>
    </div>
</div>
