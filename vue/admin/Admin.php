<?php
/**
 * VUE : Login
 * Formulaire d'authentification.
 * $erreur : message d'erreur transmis par le contrôleur (null si aucune tentative).
 * Le wrapper HTML (DOCTYPE, head, body) est fourni par MainTemplate — ne pas le redéclarer.
 */
?>
<div class="d-flex justify-content-center align-items-center py-5">
    <div style="width: 100%; max-width: 400px;">
        <div class="border border-dark rounded p-4 bg-white shadow-sm">
            <h4 class="text-center mb-4">Connexion Admin</h4>

            <!-- Erreur de connexion transmise par le contrôleur -->

            <form method="POST" action="<?= $actual_link . URL_ADMIN ?>">

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="text" name="mail" id="mail" class="form-control" placeholder="azerty@gmail.com">
                </div>

                <div class="mb-3">
                    <label for="pwd" class="form-label">Mot de passe</label>
                    <input type="password" name="password" id="password" class="form-control"
                        placeholder="Votre mot de passe">
                </div>

                <?php if (!empty($erreur)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($erreur) ?></div>
                <?php endif; ?>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Connexion</button>
                </div>

            </form>
        </div>
    </div>
</div>