<?php /** VUE : Admin / Edit account */ ?>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-7">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="mb-0 h3">Modifier le compte</h1>
                    <small class="text-muted">
                        <?= htmlspecialchars($user->getSurname() . ' ' . $user->getName()) ?>
                        · Administration
                    </small>
                </div>
                <a href="<?= $actual_link ?>parametres/utilisateurs" class="btn btn-outline-secondary btn-sm px-3">← Retour</a>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <!--
                Deux formulaires indépendants sur la même page.
                action=identity : nom, email, mot de passe, rôle
                action=subscription : ajout d'une période d'adhésion
                Ils postent sur la même URL — le contrôleur dispatche via $_POST['action'].
            -->

            <!-- Formulaire identité -->
            <div class="card mb-3">
                <div class="card-header bg-dark text-white py-2">
                    <span class="fw-semibold small">Identité</span>
                </div>
                <div class="card-body">
                    <form method="POST"
                          action="<?= $actual_link ?>parametres/utilisateurs?page=edit&id=<?= $user->getId() ?>">
                        <input type="hidden" name="action" value="identity">

                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label for="name" class="form-label">Prénom <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name"
                                       value="<?= htmlspecialchars($user->getName()) ?>" required>
                            </div>
                            <div class="col-6">
                                <label for="surname" class="form-label">Nom <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="surname" name="surname"
                                       value="<?= htmlspecialchars($user->getSurname()) ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="mail" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="mail" name="mail"
                                   value="<?= htmlspecialchars($user->getMail()) ?>" required>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label for="password" class="form-label">Nouveau mot de passe</label>
                                <!-- Champ vide = mot de passe inchangé (contrôleur ignore si vide) -->
                                <input type="password" class="form-control" id="password" name="password"
                                       placeholder="Laisser vide pour conserver">
                            </div>
                            <div class="col-6">
                                <label for="passwordConfirm" class="form-label">Confirmer</label>
                                <input type="password" class="form-control" id="passwordConfirm">
                            </div>
                        </div>
                        <div id="passwordMismatch" class="text-danger small mb-3" style="display:none">
                            Les mots de passe ne correspondent pas.
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label for="codeRole" class="form-label">Type de compte</label>
                                <?php if ($isSelf): ?>
                                    <!-- Un admin ne peut pas modifier son propre rôle : champ verrouillé,
                                         la valeur POST codeRole est ignorée côté serveur ($isSelf). -->
                                    <p class="form-control-plaintext mb-0">Administrateur
                                        <small class="text-muted">(non modifiable)</small>
                                    </p>
                                <?php else: ?>
                                <select class="form-select" id="codeRole" name="codeRole">
                                    <?php if ($user->getCodeRole() === ROLE_INVITE): ?>
                                    <!-- ROLE_INVITE affiché uniquement si le compte est déjà invité.
                                         La rétrogradation manuelle vers invité est bloquée côté serveur :
                                         elle ne peut se produire qu'automatiquement au login (adhésion expirée). -->
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
                                    <!-- ROLE_ADMIN absent : non assignable via l'interface -->
                                </select>
                                <?php endif; ?>
                            </div>
                            <div class="col-6">
                                <label class="form-label">N° adhérent</label>
                                <?php
                                $role = $user->getCodeRole();
                                $num  = $user->getMemberNum();
                                // Invité sans numéro = jamais été adhérent
                                if ($role === ROLE_INVITE && empty($num)): ?>
                                    <p class="form-control-plaintext mb-0 text-muted small">INVITE</p>
                                <?php elseif ($role === ROLE_INVITE): ?>
                                    <!-- Invité avec numéro = ex-adhérent rétrogradé (adhésion expirée au login) -->
                                    <p class="form-control-plaintext mb-0">
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle">
                                            <?= htmlspecialchars($num) ?>
                                        </span>
                                    </p>
                                <?php elseif (!empty($num)): ?>
                                    <p class="form-control-plaintext mb-0">
                                        <span class="badge bg-success-subtle text-success border border-success-subtle">
                                            <?= htmlspecialchars($num) ?>
                                        </span>
                                    </p>
                                <?php else: ?>
                                    <p class="form-control-plaintext mb-0 text-muted">—</p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-sm px-4" id="submitIdentity">
                            Enregistrer
                        </button>
                    </form>
                </div>
            </div>

            <!--
                Bloc adhésion — masqué pour l'admin connecté ($isSelf) car il ne peut pas s'auto-modifier.
                Pour ROLE_NATURALISTE : bloc visible mais purement informatif (les dates n'affectent pas la connexion).
                Pour ROLE_INVITE : titre "Temps d'accès" + case "Promouvoir en adhérent" visible.
            -->
            <?php if (!$isSelf): ?>
            <div class="card mb-3">
                <div class="card-header bg-dark text-white py-2 d-flex justify-content-between align-items-center">
                    <span class="fw-semibold small">
                        <?php if ($user->getCodeRole() === ROLE_INVITE): ?>
                            Temps d'accès
                        <?php else: ?>
                            Adhésion
                        <?php endif; ?>
                    </span>
                    <?php if ($user->getCodeRole() === ROLE_NATURALISTE): ?>
                        <span class="text-white-50 small fw-normal">informatif — n'affecte pas la connexion</span>
                    <?php endif; ?>
                </div>
                <div class="card-body">

                    <?php if ($activeSubscription): ?>
                        <div class="alert alert-success py-2 mb-3">
                            Accès actif jusqu'au
                            <strong><?= date('d/m/Y', strtotime($activeSubscription->endDate)) ?></strong>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning py-2 mb-3">Aucune adhésion active.</div>
                    <?php endif; ?>

                    <?php if ($subscriptionSuccess): ?>
                        <div class="alert alert-success py-2">Adhésion enregistré.</div>
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
                                <button type="submit" class="btn btn-success btn-sm px-3">Activer</button>
                            </div>
                        </div>
                        <?php if ($user->getCodeRole() === ROLE_INVITE): ?>
                        <!-- Promotion invité → adhérent :
                             le contrôleur efface l'historique d'adhésion invité et génère un numéro adhérent
                             (ou conserve l'existant si le compte avait déjà été adhérent). -->
                        <div class="form-check mt-3">
                            <input class="form-check-input" type="checkbox"
                                   name="promoteToAdherent" id="promoteToAdherent" value="1">
                            <label class="form-check-label small" for="promoteToAdherent">
                                Promouvoir en adhérent
                            </label>
                        </div>
                        <?php endif; ?>
                    </form>

                    <?php if (!empty($subscriptionHistory)): ?>
                    <hr class="my-3">
                    <p class="text-muted small mb-2 fw-semibold">Historique</p>
                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="fw-normal text-muted small">Début</th>
                                <th class="fw-normal text-muted small">Fin</th>
                                <th class="fw-normal text-muted small">Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($subscriptionHistory as $sub): ?>
                            <tr>
                                <td class="small"><?= date('d/m/Y', strtotime($sub->startDate)) ?></td>
                                <td class="small"><?= date('d/m/Y', strtotime($sub->endDate)) ?></td>
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

                </div>
            </div>
            <?php endif; ?>

        </div>
    </div>
</div>
