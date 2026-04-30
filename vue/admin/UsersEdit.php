<?php /** VUE : Admin / Edit account */ ?>
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

            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">Informations du compte</h5>
                    <form method="POST"
                          action="<?= $actual_link ?>parametres/utilisateurs?page=edit&id=<?= $user->getId() ?>">
                        <input type="hidden" name="action" value="identity">

                        <div class="mb-3">
                            <label for="name" class="form-label">Prénom <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name"
                                   value="<?= htmlspecialchars($user->getName()) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="surname" class="form-label">Nom <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="surname" name="surname"
                                   value="<?= htmlspecialchars($user->getSurname()) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="mail" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="mail" name="mail"
                                   value="<?= htmlspecialchars($user->getMail()) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">
                                Nouveau mot de passe
                                <small class="text-muted">(laisser vide pour conserver l'actuel)</small>
                            </label>
                            <input type="password" class="form-control" id="password" name="password"
                                   oninput="checkPasswords()">
                        </div>

                        <div class="mb-3">
                            <label for="passwordConfirm" class="form-label">Confirmer le mot de passe</label>
                            <input type="password" class="form-control" id="passwordConfirm"
                                   oninput="checkPasswords()">
                            <div id="passwordMismatch" class="form-text text-danger" style="display:none">
                                Les mots de passe ne correspondent pas.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="codeRole" class="form-label">Rôle</label>
                            <?php if ($isSelf): ?>
                                <p class="form-control-plaintext">Administrateur
                                    <small class="text-muted">(non modifiable)</small>
                                </p>
                            <?php else: ?>
                            <select class="form-select" id="codeRole" name="codeRole">
                                <?php if ($user->getCodeRole() === ROLE_INVITE): ?>
                                <option value="<?= ROLE_INVITE ?>" selected>Invité</option>
                                <?php endif; ?>
                                <option value="<?= ROLE_ADHERENT ?>"
                                    <?= $user->getCodeRole() === ROLE_ADHERENT ? 'selected' : '' ?>>
                                    Adhérent
                                </option>
                                <option value="<?= ROLE_NATURALISTE ?>"
                                    <?= $user->getCodeRole() === ROLE_NATURALISTE ? 'selected' : '' ?>>
                                    Naturaliste
                                </option>
                            </select>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">N° adhérent</label>
                            <?php
                            $role = $user->getCodeRole();
                            $num  = $user->getMemberNum();
                            if ($role === ROLE_INVITE && empty($num)): ?>
                                <p class="mb-0 text-muted">INVITE</p>
                            <?php elseif ($role === ROLE_INVITE): ?>
                                <p class="mb-0 text-danger fw-semibold"><?= htmlspecialchars($num) ?></p>
                            <?php elseif (!empty($num)): ?>
                                <p class="mb-0 text-success fw-semibold"><?= htmlspecialchars($num) ?></p>
                            <?php else: ?>
                                <p class="mb-0 text-muted">—</p>
                            <?php endif; ?>
                        </div>

                        <button type="submit" class="btn btn-primary" id="submitIdentity">Enregistrer</button>
                    </form>

                    <script>
                    function checkPasswords() {
                        const pwd     = document.getElementById('password').value;
                        const confirm = document.getElementById('passwordConfirm').value;
                        const mismatch = document.getElementById('passwordMismatch');
                        const submit   = document.getElementById('submitIdentity');
                        const differs  = pwd.length > 0 && pwd !== confirm;
                        mismatch.style.display = differs ? '' : 'none';
                        submit.disabled = differs;
                    }
                    </script>
                </div>
            </div>

            <?php if (!$isSelf): ?>
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">Temps d'accès</h5>

                    <?php if ($activeSubscription): ?>
                        <div class="alert alert-success py-2 mb-3">
                            Accès actif jusqu'au
                            <strong><?= date('d/m/Y', strtotime($activeSubscription->endDate)) ?></strong>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning py-2 mb-3">Aucun temps d'accès actif.</div>
                    <?php endif; ?>

                    <?php if ($subscriptionSuccess): ?>
                        <div class="alert alert-success py-2">Temps d'accès enregistré.</div>
                    <?php elseif ($subscriptionError): ?>
                        <div class="alert alert-danger py-2"><?= htmlspecialchars($subscriptionError) ?></div>
                    <?php endif; ?>

                    <form method="POST"
                          action="<?= $actual_link ?>parametres/utilisateurs?page=edit&id=<?= $user->getId() ?>">
                        <input type="hidden" name="action" value="subscription">
                        <div class="row g-2 align-items-end">
                            <div class="col">
                                <label for="startDate" class="form-label">Début</label>
                                <input type="date" class="form-control" id="startDate" name="startDate"
                                       value="<?= date('Y-m-d') ?>" required>
                            </div>
                            <div class="col">
                                <label for="endDate" class="form-label">Fin</label>
                                <input type="date" class="form-control" id="endDate" name="endDate" required>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-success">Activer</button>
                            </div>
                        </div>
                        <?php if ($user->getCodeRole() === ROLE_INVITE): ?>
                        <div class="form-check mt-3">
                            <input class="form-check-input" type="checkbox"
                                   name="promoteToAdherent" id="promoteToAdherent" value="1">
                            <label class="form-check-label" for="promoteToAdherent">
                                Promouvoir en adhérent
                            </label>
                        </div>
                        <?php endif; ?>
                    </form>

                    <?php if (!empty($subscriptionHistory)): ?>
                    <hr>
                    <h6 class="text-muted mb-2">Historique</h6>
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Début</th>
                                <th>Fin</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($subscriptionHistory as $sub): ?>
                            <tr>
                                <td><?= date('d/m/Y', strtotime($sub->startDate)) ?></td>
                                <td><?= date('d/m/Y', strtotime($sub->endDate)) ?></td>
                                <td>
                                    <?php if ($sub->endDate >= date('Y-m-d')): ?>
                                        <span class="badge bg-success">Actif</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Expiré</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php endif; ?>
                    <?php if ($user->getCodeRole() === ROLE_NATURALISTE): ?>
                    <p class="text-muted small mt-3 mb-0">
                        Le temps d'accès est purement informatif pour un naturaliste,
                        il n'affecte pas sa capacité à se connecter.
                    </p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

        </div>
    </div>
</div>
