<?php /** VUE : Admin / Modifier un compte */ ?>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Modifier le compte</h1>
                <a href="<?= $actual_link ?>parametres/utilisateurs" class="btn btn-outline-secondary">← Retour</a>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST"
                  action="<?= $actual_link ?>parametres/utilisateurs/editer?id=<?= $metier->getId() ?>">

                <div class="mb-3">
                    <label for="name" class="form-label">Prénom <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" name="name"
                           value="<?= htmlspecialchars($metier->getName()) ?>" required>
                </div>

                <div class="mb-3">
                    <label for="surname" class="form-label">Nom <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="surname" name="surname"
                           value="<?= htmlspecialchars($metier->getSurname()) ?>" required>
                </div>

                <div class="mb-3">
                    <label for="mail" class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" id="mail" name="mail"
                           value="<?= htmlspecialchars($metier->getMail()) ?>" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">
                        Nouveau mot de passe
                        <small class="text-muted">(laisser vide pour conserver l'actuel)</small>
                    </label>
                    <input type="password" class="form-control" id="password" name="password">
                </div>

                <div class="mb-3">
                    <label for="codeRole" class="form-label">Rôle</label>
                    <?php if ($isSelf): ?>
                        <p class="form-control-plaintext">Administrateur
                            <small class="text-muted">(non modifiable)</small>
                        </p>
                    <?php else: ?>
                    <select class="form-select" id="codeRole" name="codeRole">
                        <option value="<?= ROLE_INVITE ?>"
                            <?= $metier->getCodeRole() === ROLE_INVITE ? 'selected' : '' ?>>
                            Invité
                        </option>
                        <option value="<?= ROLE_ADHERENT ?>"
                            <?= $metier->getCodeRole() === ROLE_ADHERENT ? 'selected' : '' ?>>
                            Adhérent
                        </option>
                        <option value="<?= ROLE_NATURALISTE ?>"
                            <?= $metier->getCodeRole() === ROLE_NATURALISTE ? 'selected' : '' ?>>
                            Naturaliste
                        </option>
                    </select>
                    <?php endif; ?>
                </div>

                <div class="mb-4">
                    <label for="memberNum" class="form-label">N° adhérent</label>
                    <input type="number" class="form-control" id="memberNum" name="memberNum"
                           value="<?= (int)$metier->getMemberNum() ?>" min="0">
                </div>

                <button type="submit" class="btn btn-primary">Enregistrer</button>

            </form>

        </div>
    </div>
</div>
