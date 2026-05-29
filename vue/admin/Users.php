<?php
function roleBadge(int $code): string
{
    return match ($code) {
        ROLE_INVITE => '<span class="badge bg-secondary">Invité</span>',
        ROLE_ADHERENT => '<span class="badge bg-primary">Adhérent</span>',
        ROLE_NATURALISTE => '<span class="badge bg-success">Naturaliste</span>',
        ROLE_ADMIN => '<span class="badge bg-dark">Admin</span>',
        default => '<span class="badge bg-light text-dark">—</span>',
    };
}

function memberNumCell(object $u, array $activeByUser): string
{
    $role      = (int) $u->codeRole;
    $num       = $u->memberNum ?? '';
    $hasAccess = isset($activeByUser[(int) $u->id]);

    if (empty($num)) {
        return $role === ROLE_INVITE
            ? '<span class="text-muted small">INVITE</span>'
            : '<span class="text-muted">—</span>';
    }

    // Numéro vert si accès actif, rouge sinon (indique que l'adhésion/accès est échu)
    return $hasAccess
        ? '<span class="badge bg-success-subtle text-success border border-success-subtle">' . htmlspecialchars($num) . '</span>'
        : '<span class="badge bg-danger-subtle text-danger border border-danger-subtle">' . htmlspecialchars($num) . '</span>';
}

function subscriptionCell(int $userId, int $role, array $activeByUser): string
{
    if ($role === ROLE_ADMIN) {
        return '<span class="text-muted">—</span>';
    }
    if (!isset($activeByUser[$userId])) {
        return '<span class="text-muted" title="Aucun accès actif">✗</span>';
    }

    $sub      = $activeByUser[$userId];
    $endDate  = new \DateTime($sub->endDate);
    $daysLeft = (int)(new \DateTime('today'))->diff($endDate)->days;
    $endFmt   = $endDate->format('d/m/Y');

    return sprintf(
        '<span class="badge bg-success-subtle text-success border border-success-subtle fw-normal">%s <span class="opacity-75 small">(%dj)</span></span>',
        $endFmt, $daysLeft
    );
}
?>

<div class="container py-4" id="usersApp" data-base-url="<?= $actual_link ?>">

    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-0 h3">Utilisateurs</h1>
            <small class="text-muted">Administration · <?= htmlspecialchars(APP_NAME) ?></small>
        </div>
        <div class="d-flex gap-2">
            <?php if ($purgeableCount > 0): ?>
            <button type="button" class="btn btn-outline-danger btn-sm px-3"
                    data-bs-toggle="modal" data-bs-target="#purgeModal">
                Purger les invités expirés
                <span class="badge bg-danger ms-1"><?= $purgeableCount ?></span>
            </button>
            <?php endif; ?>
            <a href="<?= $actual_link ?>parametres/utilisateurs?page=create" class="btn btn-success btn-sm px-3">
                + Créer un compte
            </a>
        </div>
    </div>

    <!-- Sous-navigation admin -->
    <div class="d-flex gap-2 mb-4">
        <span class="btn btn-dark btn-sm disabled" aria-current="page">
            <i class="bi bi-people me-1"></i>Utilisateurs
        </span>
        <a href="<?= $actual_link ?>parametres/webcam" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-camera-video me-1"></i>Webcam
        </a>
        <a href="<?= $actual_link ?>parametres/mail" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-envelope me-1"></i>Mail
        </a>
    </div>

    <?php if ($purgedCount !== null): ?>
    <div class="alert alert-success alert-dismissible fade show py-2" role="alert">
        <?= $purgedCount ?> compte<?= $purgedCount > 1 ? 's' : '' ?> invité<?= $purgedCount > 1 ? 's' : '' ?> supprimé<?= $purgedCount > 1 ? 's' : '' ?>.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <!-- Barre outils : recherche + config -->
    <div class="row g-3 mb-4">
        <div class="col-md-7">
            <div class="card h-100">
                <div class="card-header bg-dark text-white py-2">
                    <span class="fw-semibold small">Recherche rapide</span>
                </div>
                <div class="card-body">
                    <div class="input-group">
                        <input type="text" id="searchUser" class="form-control" placeholder="Nom ou prénom..."
                            autocomplete="off">
                        <span class="input-group-text bg-white">
                            <span id="ajaxLoader" style="display:none">
                                <span class="spinner-border spinner-border-sm text-secondary"></span>
                            </span>
                        </span>
                    </div>
                    <div id="ajaxResults" class="mt-2" style="display:none"></div>
                    <div id="ajaxNoResult" style="display:none" class="mt-2 text-muted small">Aucun résultat.</div>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card h-100">
                <div class="card-header bg-dark text-white py-2">
                    <span class="fw-semibold small">Paramètres d'accès</span>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?= $actual_link ?>parametres/utilisateurs"
                        class="d-flex align-items-end gap-2 flex-wrap">
                        <input type="hidden" name="action" value="saveConfig">
                        <div>
                            <label for="guestDefaultAccessDays" class="form-label small mb-1">
                                Accès invité par défaut
                            </label>
                            <div class="input-group input-group-sm">
                                <input type="number" class="form-control" id="guestDefaultAccessDays"
                                    name="guestDefaultAccessDays" min="1" value="<?= (int) $guestDefaultAccessDays ?>"
                                    style="max-width:80px">
                                <span class="input-group-text">jours</span>
                            </div>
                        </div>
                        <div>
                            <label for="naturalisteDefaultAccessDays" class="form-label small mb-1">
                                Accès naturaliste par défaut
                            </label>
                            <div class="input-group input-group-sm">
                                <input type="number" class="form-control" id="naturalisteDefaultAccessDays"
                                    name="naturalisteDefaultAccessDays" min="1"
                                    value="<?= (int) $naturalisteDefaultAccessDays ?>"
                                    style="max-width:80px">
                                <span class="input-group-text">jours</span>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">OK</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtre par rôle -->
    <ul class="nav nav-pills mb-3">
        <?php
        $roles = [
            -1 => 'Tous',
            ROLE_INVITE => 'Invités',
            ROLE_ADHERENT => 'Adhérents',
            ROLE_NATURALISTE => 'Naturalistes',
        ];
        foreach ($roles as $code => $label):
            $active = ($roleFilter === $code) ? 'active' : '';
            $href = $actual_link . 'parametres/utilisateurs' . ($code >= 0 ? '?role=' . $code : '');
            ?>
            <li class="nav-item">
                <a class="nav-link <?= $active ?>" href="<?= $href ?>"><?= $label ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
    <!-- Tableau -->
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Identité</th>
                        <th>Type de compte</th>
                        <th>N° adhérent</th>
                        <th class="text-center">Temps d'accès</th>
                        <th class="text-center">Connexions</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Aucun utilisateur.</td>
                        </tr>
                    <?php else:
                        foreach ($users as $u): ?>
                            <tr>
                                <td>
                                    <div class="fw-semibold"><?= htmlspecialchars($u->surname . ' ' . $u->name) ?></div>
                                    <small class="text-muted"><?= htmlspecialchars($u->mail) ?></small>
                                </td>
                                <td><?= roleBadge((int) $u->codeRole) ?></td>
                                <td><?= memberNumCell($u, $activeByUser) ?></td>
                                <td class="text-center"><?= subscriptionCell((int) $u->id, (int) $u->codeRole, $activeByUser) ?></td>
                                <td class="text-center text-muted small"><?= (int) $u->countConnect ?></td>
                                <td class="text-end">
                                    <a href="<?= $actual_link ?>parametres/utilisateurs?page=edit&id=<?= (int) $u->id ?>"
                                        class="btn btn-sm btn-outline-primary">Éditer</a>
                                    <?php if ((int) $u->id !== $currentId): ?>
                                        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal"
                                            data-bs-target="#deleteModal" data-id="<?= (int) $u->id ?>"
                                            data-name="<?= htmlspecialchars($u->name . ' ' . $u->surname) ?>">
                                            Supprimer
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
        <nav class="mt-3">
            <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $totalPages; $i++):
                    $active = ($i === $currentPage) ? 'active' : '';
                    $roleParam = $roleFilter >= 0 ? '&role=' . $roleFilter : '';
                    ?>
                    <li class="page-item <?= $active ?>">
                        <a class="page-link" href="<?= $actual_link ?>parametres/utilisateurs?p=<?= $i . $roleParam ?>">
                            <?= $i ?>
                        </a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php endif; ?>

</div>

<!-- Modal suppression -->
<div class="modal fade" id="purgeModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Purger les invités expirés</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <strong><?= $purgeableCount ?></strong> compte<?= $purgeableCount > 1 ? 's' : '' ?> invité<?= $purgeableCount > 1 ? 's' : '' ?> sans historique
                <?= $purgeableCount > 1 ? 'seront supprimés' : 'sera supprimé' ?> définitivement.
                <div class="text-muted small mt-1">Les ex-adhérents (numéro en rouge) ne sont pas concernés.</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Annuler</button>
                <form method="POST" action="<?= $actual_link ?>parametres/utilisateurs">
                    <input type="hidden" name="action" value="purge">
                    <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Confirmer la suppression</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Supprimer le compte de <strong id="deleteModalName"></strong> ?
                <div class="text-muted small mt-1">Cette action est irréversible.</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Annuler</button>
                <form id="deleteForm" method="POST" action="<?= $actual_link ?>parametres/utilisateurs">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" id="deleteModalId" value="">
                    <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>
