<?php
/**
 * VUE : Profil
 * Variables reçues :
 *   $user    — stdClass|null : utilisateur connecté (depuis $_SESSION['user'])
 *   $success — bool|null     : résultat de la mise à jour (null = pas de tentative)
 *
 * Accessible uniquement aux utilisateurs connectés (Guard::requireLogin()).
 * ROLE_INVITE (0) = visiteur non connecté — ne peut pas atteindre cette page.
 * Le rôle est affiché en lecture seule — modification réservée à /parametres/utilisateurs (admin).
 */

$roleLabel = match((int)($user->codeRole ?? -1)) {
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

            <!-- Feedback de la soumission -->
            <?php if ($success === true): ?>
                <div class="alert alert-success">Profil mis à jour.</div>
            <?php elseif ($success === false): ?>
                <div class="alert alert-danger">Une erreur est survenue.</div>
            <?php endif; ?>

            <!--
                Formulaire POST vers parametres/profil.
                $actual_link est fourni par Template::print().
            -->
            <form method="POST" action="<?= $actual_link ?>parametres/profil">

                <div class="mb-3">
                    <label for="name" class="form-label">Prénom</label>
                    <input type="text"
                           class="form-control"
                           id="name"
                           name="name"
                           value="<?= htmlspecialchars($user->name ?? '') ?>"
                           required>
                </div>

                <div class="mb-3">
                    <label for="surname" class="form-label">Nom</label>
                    <input type="text"
                           class="form-control"
                           id="surname"
                           name="surname"
                           value="<?= htmlspecialchars($user->surname ?? '') ?>"
                           required>
                </div>

                <!-- Rôle en lecture seule — modification via /parametres/utilisateurs (admin) -->
                <div class="mb-4">
                    <label class="form-label">Rôle</label>
                    <p class="form-control-plaintext"><?= htmlspecialchars($roleLabel) ?></p>
                </div>

                <button type="submit" class="btn btn-primary">Enregistrer</button>

            </form>

        </div>
    </div>
</div>
