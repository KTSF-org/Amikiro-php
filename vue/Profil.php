<?php
/**
 * VUE : Profil
 * Variables reçues :
 *   $name    — string  : prénom de l'utilisateur connecté
 *   $surname — string  : nom
 *   $mail    — string  : email
 *   $role    — int     : constante ROLE_* depuis la session
 *   $success — bool|null : résultat de la mise à jour
 *   $error   — string|null : message d'erreur de validation
 */

$roleLabel = match((int)($role ?? -1)) {
    ROLE_INVITE      => 'Invité',
    ROLE_ADHERENT    => 'Adhérent',
    ROLE_NATURALISTE => 'Naturaliste',
    ROLE_ADMIN       => 'Administrateur',
    default          => 'Inconnu',
};
?>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="mb-4">
                <h1 class="mb-0 h3">Mon profil</h1>
                <small class="text-muted"><?= htmlspecialchars($name . ' ' . $surname) ?></small>
            </div>

            <?php if ($success === true): ?>
                <div class="alert alert-success">Modification enregistrée.</div>
            <?php elseif ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <!-- Résumé du compte — lecture seule -->
            <div class="card mb-3">
                <div class="card-header bg-dark text-white py-2">
                    <span class="fw-semibold small">Mes informations</span>
                </div>
                <div class="card-body">
                    <dl class="row mb-0 small">
                        <dt class="col-sm-4 text-muted fw-normal">Prénom</dt>
                        <dd class="col-sm-8"><?= htmlspecialchars($name ?? '') ?></dd>

                        <dt class="col-sm-4 text-muted fw-normal">Nom</dt>
                        <dd class="col-sm-8"><?= htmlspecialchars($surname ?? '') ?></dd>

                        <dt class="col-sm-4 text-muted fw-normal">Email</dt>
                        <dd class="col-sm-8"><?= htmlspecialchars($mail ?? '') ?></dd>

                        <dt class="col-sm-4 text-muted fw-normal">Rôle</dt>
                        <dd class="col-sm-8 mb-0"><?= htmlspecialchars($roleLabel?? '') ?></dd>

                        <dt class="col-sm-4 text-muted fw-normal">N°Adhérent</dt>
                        <dd class="col-sm-8 mb-0"></dd>
                    </dl>
                </div>
            </div>

            <!--
                Deux formulaires indépendants, dispatché via $_POST['action'].
                action=identity : prénom et nom (email non modifiable par l'utilisateur)
                action=password : changement de mot de passe avec vérification de l'actuel
            -->

            <!-- Modifier le nom / prénom -->
            <div class="card mb-3">
                <div class="card-header bg-dark text-white py-2">
                    <span class="fw-semibold small">Modifier l'identité</span>
                </div>
                <div class="card-body">
                    <!-- Les champs ne sont pas pré-remplis : l'utilisateur saisit les nouvelles valeurs -->
                    <form method="POST" action="<?= $actual_link ?>parametres/profil">
                        <input type="hidden" name="action" value="identity">
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
                        <button type="submit" class="btn btn-primary btn-sm px-4">Enregistrer</button>
                    </form>
                </div>
            </div>

            <!-- Modifier le mot de passe -->
            <div class="card">
                <div class="card-header bg-dark text-white py-2">
                    <span class="fw-semibold small">Modifier le mot de passe</span>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?= $actual_link ?>parametres/profil"
                          id="passwordForm">
                        <input type="hidden" name="action" value="password">
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Mot de passe actuel</label>
                            <!-- Vérifié côté serveur via password_verify() avant d'accepter le changement -->
                            <input type="password" class="form-control" id="current_password"
                                   name="current_password" required>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label for="new_password" class="form-label">Nouveau</label>
                                <input type="password" class="form-control" id="new_password"
                                       name="new_password" required>
                            </div>
                            <div class="col-6">
                                <label for="confirm_password" class="form-label">Confirmer</label>
                                <input type="password" class="form-control" id="confirm_password"
                                       name="confirm_password" required>
                            </div>
                        </div>
                        <div id="pwdMismatch" class="text-danger small mb-3" style="display:none">
                            Les mots de passe ne correspondent pas.
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm px-4" id="submitPwd">
                            Changer le mot de passe
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
