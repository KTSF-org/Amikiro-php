<?php /** VUE : Admin / Créer un compte */ ?>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Créer un compte</h1>
                <a href="<?= $actual_link ?>parametres/utilisateurs" class="btn btn-outline-secondary">← Retour</a>
            </div>

            <?php if ($error): // $error est défini dans le contrôleur si la validation échoue ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <!-- Le formulaire poste vers la même route ; le contrôleur détecte la méthode POST -->
            <form method="POST" action="<?= $actual_link ?>parametres/utilisateurs?page=creer">

                <div class="mb-3">
                    <label for="name" class="form-label">Prénom <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>

                <div class="mb-3">
                    <label for="surname" class="form-label">Nom <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="surname" name="surname" required>
                </div>

                <div class="mb-3">
                    <label for="mail" class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" id="mail" name="mail" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Mot de passe <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>

                <div class="mb-3">
                    <label for="codeRole" class="form-label">Rôle</label>
                    <!-- ROLE_ADMIN absent volontairement : bloqué aussi côté contrôleur -->
                    <select class="form-select" id="codeRole" name="codeRole">
                        <option value="<?= ROLE_INVITE ?>">Invité</option>
                        <option value="<?= ROLE_ADHERENT ?>">Adhérent</option>
                        <option value="<?= ROLE_NATURALISTE ?>">Naturaliste</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="memberNum" class="form-label">N° adhérent</label>
                    <input type="number" class="form-control" id="memberNum" name="memberNum"
                           value="0" min="0">
                </div>

                <button type="submit" class="btn btn-success">Créer le compte</button>

            </form>

        </div>
    </div>
</div>
