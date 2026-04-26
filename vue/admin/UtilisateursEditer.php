<?php /** VUE : Admin / Modifier un compte */ ?>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Modifier le compte</h1>
                <a href="<?= $actual_link ?>parametres/utilisateurs" class="btn btn-outline-secondary">← Retour</a>
            </div>

            <!-- Formulaire identité -->
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">Informations du compte</h5>
                    <!-- name="action" permet au contrôleur de distinguer ce formulaire de celui d'abonnement -->
                    <form method="POST"
                          action="<?= $actual_link ?>parametres/utilisateurs?page=editer&id=<?= $metier->getId() ?>">
                        <input type="hidden" name="action" value="identity">

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
                            <?php if ($isSelf): // un admin ne peut pas modifier son propre rôle ?>
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

                        <div class="mb-3">
                            <label for="memberNum" class="form-label">N° adhérent</label>
                            <input type="number" class="form-control" id="memberNum" name="memberNum"
                                   value="<?= (int)$metier->getMemberNum() ?>" min="0">
                        </div>

                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </form>
                </div>
            </div>

            <!-- Abonnement : masqué quand l'admin édite son propre compte -->
            <?php if (!$isSelf): ?>
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">Abonnement</h5>

                    <!-- Statut actuel -->
                    <?php if ($abonnementActif): ?>
                        <div class="alert alert-success py-2 mb-3">
                            Abonnement actif jusqu'au
                            <strong><?= date('d/m/Y', strtotime($abonnementActif->endDate)) ?></strong>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning py-2 mb-3">Aucun abonnement actif.</div>
                    <?php endif; ?>

                    <!-- Feedback -->
                    <?php if ($successAbo): ?>
                        <div class="alert alert-success py-2">Abonnement enregistré.</div>
                    <?php elseif ($errorAbo): ?>
                        <div class="alert alert-danger py-2"><?= htmlspecialchars($errorAbo) ?></div>
                    <?php endif; ?>

                    <!-- Formulaire nouvel abonnement : action="abonnement" pour distinguer du formulaire identité -->
                    <form method="POST"
                          action="<?= $actual_link ?>parametres/utilisateurs?page=editer&id=<?= $metier->getId() ?>">
                        <input type="hidden" name="action" value="abonnement">
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
                    </form>

                    <!-- Historique -->
                    <?php if (!empty($historiqueAbo)): ?>
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
                            <?php foreach ($historiqueAbo as $abo): ?>
                            <tr>
                                <td><?= date('d/m/Y', strtotime($abo->startDate)) ?></td>
                                <td><?= date('d/m/Y', strtotime($abo->endDate)) ?></td>
                                <td>
                                    <?php if ($abo->endDate >= date('Y-m-d')): // comparaison de chaînes ISO 8601 (Y-m-d), fonctionne car format fixe ?>
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
