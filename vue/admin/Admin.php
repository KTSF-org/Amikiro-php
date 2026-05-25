<?php
/**
 * VUE : Login Admin
 * Formulaire d'authentification réservé à l'administrateur.
 * $erreur : message d'erreur transmis par le contrôleur (null si aucune tentative).
 * Le wrapper HTML (DOCTYPE, head, body) est fourni par MainTemplate — ne pas le redéclarer.
 */
?>
<div class="login-page">
    <div class="login-card-wrapper">
        <div class="card">

            <div class="login-brand">
                <img src="<?= ASSET ?>/img/app.png" alt="Logo <?= htmlspecialchars(MAIN_TITLE) ?>">
                <h5><?= htmlspecialchars(MAIN_TITLE) ?></h5>
                <p>Espace administrateur</p>
            </div>

            <div class="card-body px-4 pb-4">
                <hr class="login-divider">

                <form method="POST" action="<?= $actual_link . URL_ADMIN ?>">

                    <div class="mb-3">
                        <label for="mail" class="form-label">Email</label>
                        <input type="email" name="mail" id="mail" class="form-control"
                               placeholder="admin@email.com" autocomplete="email">
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe</label>
                        <input type="password" name="password" id="password" class="form-control"
                               placeholder="••••••••" autocomplete="current-password">
                    </div>

                    <?php if (!empty($erreur)): ?>
                        <div class="alert alert-danger py-2 small"><?= htmlspecialchars($erreur) ?></div>
                    <?php endif; ?>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary">Connexion</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
