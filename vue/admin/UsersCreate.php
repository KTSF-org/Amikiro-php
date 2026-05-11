<?php /** VUE : Admin / Create account */ ?>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-7">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="mb-0 h3">Créer un compte</h1>
                    <small class="text-muted">Administration · <?= htmlspecialchars(APP_NAME) ?></small>
                </div>
                <a href="<?= $actual_link ?>parametres/utilisateurs" class="btn btn-outline-secondary btn-sm px-3">← Retour</a>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="<?= $actual_link ?>parametres/utilisateurs?page=create">

                <!-- Identité -->
                <div class="card mb-3">
                    <div class="card-header bg-dark text-white py-2">
                        <span class="fw-semibold small">Identité</span>
                    </div>
                    <div class="card-body">
                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label for="name" class="form-label">Prénom <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="col-6">
                                <label for="surname" class="form-label">Nom <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="surname" name="surname" required>
                            </div>
                        </div>
                        <div class="mb-0">
                            <label for="mail" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="mail" name="mail" required>
                        </div>
                    </div>
                </div>

                <!-- Rôle — ROLE_ADMIN absent intentionnellement : non assignable via l'interface -->
                <div class="card mb-3">
                    <div class="card-header bg-dark text-white py-2">
                        <span class="fw-semibold small">Rôle</span>
                    </div>
                    <div class="card-body">
                        <select class="form-select" id="codeRole" name="codeRole"
                            data-role-invite="<?= ROLE_INVITE ?>" data-role-adherent="<?= ROLE_ADHERENT ?>">
                            <option value="<?= ROLE_INVITE ?>">Invité</option>
                            <option value="<?= ROLE_ADHERENT ?>" selected>Adhérent</option>
                            <option value="<?= ROLE_NATURALISTE ?>">Naturaliste</option>
                        </select>
                        <div class="form-text text-muted small mt-2">
                            Le mot de passe sera généré automatiquement et envoyé par email.
                        </div>
                    </div>
                </div>

                <!--
                    Bloc adhésion dynamique selon le rôle sélectionné.
                    Deux variantes affichées/masquées par JS :
                      - accessInvite : dates auto (pas de saisie), valeur = guestDefaultAccessDays depuis Config
                      - accessDates  : saisie manuelle début/fin (obligatoire pour adhérent, informatif pour naturaliste)
                -->

                <!-- Invité : durée fixe depuis Config, pas de saisie possible -->
                <div id="accessInvite" class="card mb-4" style="display:none;">
                    <div class="card-header bg-dark text-white py-2">
                        <span class="fw-semibold small">Adhésion</span>
                    </div>
                    <div class="card-body">
                        <p class="mb-0 text-muted small">
                            Attribuée automatiquement :
                            <strong><?= (int)$guestDefaultAccessDays ?> jours</strong> à partir d'aujourd'hui.
                        </p>
                    </div>
                </div>

                <!-- Adhérent / Naturaliste : saisie manuelle (required côté serveur pour adhérent) -->
                <div id="accessDates" class="card mb-4" style="display:none;">
                    <div class="card-header bg-dark text-white py-2 d-flex align-items-center gap-2">
                        <!-- Le texte du label est mis à jour par JS pour refléter obligatoire vs informatif -->
                        <span class="fw-semibold small" id="accessDatesLabel">Adhésion</span>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-6">
                                <label for="startDate" class="form-label">Début</label>
                                <input type="date" class="form-control" id="startDate" name="startDate">
                            </div>
                            <div class="col-6">
                                <label for="endDate" class="form-label">Fin</label>
                                <input type="date" class="form-control" id="endDate" name="endDate">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success px-4">Créer le compte</button>
                    <a href="<?= $actual_link ?>parametres/utilisateurs" class="btn btn-outline-secondary">Annuler</a>
                </div>

            </form>

        </div>
    </div>
</div>
