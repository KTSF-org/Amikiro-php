<?php
/**
 * VUE : Profil
 * Variables reçues :
 *   $user    — stdClass|null : utilisateur connecté (depuis $_SESSION['user'])
 *   $success — bool|null     : résultat de la mise à jour
 *   $error   — string|null   : message d'erreur de validation
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

            <h1 class="mb-4">Mon Profil</h1>

            <?php if ($success === true): ?>
                <div class="alert alert-success">Modification enregistrée.</div>
            <?php elseif ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <!-- Informations actuelles -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">Mes informations</h5>
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Prénom</dt>
                        <dd class="col-sm-8"><?= $name ?? ''?></dd>

                        <dt class="col-sm-4">Nom</dt>
                        <dd class="col-sm-8"><?= htmlspecialchars($surname ?? '') ?></dd>

                        <dt class="col-sm-4">Email</dt>
                        <dd class="col-sm-8"><?= htmlspecialchars($mail ?? '') ?></dd>

                        <dt class="col-sm-4">Rôle</dt>
                        <dd class="col-sm-8 mb-0"><?= $roleLabel ?></dd>
                    </dl>
                </div>
            </div>

            <!-- Modifier nom et prénom -->
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title mb-3">Modifier le nom et le prénom</h5>
                    <form method="POST" action="<?= $actual_link ?>parametres/profil">
                        <input type="hidden" name="action" value="identity">
                        <div class="mb-3">
                            <label for="name" class="form-label">Prénom</label>
                            <input type="text" class="form-control" id="name" name="name">
                        </div>
                        <div class="mb-3">
                            <label for="surname" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="surname" name="surname">
                        </div>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </form>
                </div>
            </div>

            <!-- Modifier le mot de passe -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Modifier le mot de passe</h5>
                    <form method="POST" action="<?= $actual_link ?>parametres/profil">
                        <input type="hidden" name="action" value="password">
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Mot de passe actuel</label>
                            <input type="password" class="form-control" id="current_password"
                                   name="current_password" required>
                        </div>
                        <div class="mb-3">
                            <label for="new_password" class="form-label">Nouveau mot de passe</label>
                            <input type="password" class="form-control" id="new_password"
                                   name="new_password" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirmer le mot de passe</label>
                            <input type="password" class="form-control" id="confirm_password"
                                   name="confirm_password" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Changer le mot de passe</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
