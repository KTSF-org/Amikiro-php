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

function memberNumCell(object $u): string
{
    $role = (int) $u->codeRole;
    $num = $u->memberNum ?? '';

    if ($role === ROLE_INVITE) {
        // Un invité avec un numéro est un ex-adhérent rétrogradé (adhésion expirée au login) — affiché en rouge.
        return empty($num)
            ? '<span class="text-muted small">INVITE</span>'
            : '<span class="badge bg-danger-subtle text-danger border border-danger-subtle">' . htmlspecialchars($num) . '</span>';
    }
    if (empty($num)) {
        return '<span class="text-muted">—</span>';
    }
    return '<span class="badge bg-success-subtle text-success border border-success-subtle">' . htmlspecialchars($num) . '</span>';
}

function subscriptionCell(int $userId, array $activeByUser): string
{
    if (!isset($activeByUser[$userId])) {
        return '<span class="text-muted" title="Aucune adhésion active">✗</span>';
    }
    return '<span class="text-success" title="Adhésion active">✓</span>';
}
?>

<div class="container py-4">

    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-0 h3">Utilisateurs</h1>
            <small class="text-muted">Administration · <?= htmlspecialchars(APP_NAME) ?></small>
        </div>
        <a href="<?= $actual_link ?>parametres/utilisateurs?page=create" class="btn btn-success btn-sm px-3">
            + Créer un compte
        </a>
    </div>

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
                        class="d-flex align-items-end gap-2">
                        <input type="hidden" name="action" value="saveConfig">
                        <div class="flex-grow-1">
                            <label for="guestDefaultAccessDays" class="form-label small mb-1">
                                Durée accès invité par défaut
                            </label>
                            <div class="input-group input-group-sm">
                                <input type="number" class="form-control" id="guestDefaultAccessDays"
                                    name="guestDefaultAccessDays" min="1" value="<?= (int) $guestDefaultAccessDays ?>"
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
                        <th>Rôle</th>
                        <th>N° adhérent</th>
                        <th class="text-center">Adhésion</th>
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
                                <td><?= memberNumCell($u) ?></td>
                                <td class="text-center"><?= subscriptionCell((int) $u->id, $activeByUser) ?></td>
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

<script>
    $(document).ready(function () {

        // --- Recherche AJAX ---
        $('#searchUser').on('input', function () {
            const term = $(this).val();

            if (term.length < 2) {
                $('#ajaxResults, #ajaxNoResult').hide();
                return;
            }

            $('#ajaxLoader').show();
            $('#ajaxResults, #ajaxNoResult').hide();

            const request = new AjaxRequest(
                '<?= $actual_link ?>ajax?findUsers',
                'POST',
                { name: term }
            );

            request.send(
                (response) => {
                    $('#ajaxLoader').hide();
                    if (response && response.length > 0) {
                        let html = '<ul class="list-group list-group-flush">';
                        response.forEach(u => {
                            html += `<li class="list-group-item d-flex justify-content-between align-items-center py-2">
                            <div>
                                <span class="fw-semibold">${u.surname} ${u.name}</span>
                                <small class="text-muted ms-2">${u.mail}</small>
                            </div>
                            <div class="d-flex gap-1">
                                <a href="<?= $actual_link ?>parametres/utilisateurs?page=edit&id=${u.id}"
                                   class="btn btn-sm btn-outline-primary">Éditer</a>
                                <button type="button" class="btn btn-sm btn-outline-danger"
                                        data-bs-toggle="modal" data-bs-target="#deleteModal"
                                        data-id="${u.id}"
                                        data-name="${u.name} ${u.surname}">
                                    Supprimer
                                </button>
                            </div>
                        </li>`;
                        });
                        html += '</ul>';
                        $('#ajaxResults').html(html).show();
                    } else {
                        $('#ajaxNoResult').show();
                    }
                },
                () => { $('#ajaxLoader').hide(); }
            );
        });

        // --- Modal suppression ---
        $('#deleteModal').on('show.bs.modal', function (e) {
            const btn = e.relatedTarget;
            $('#deleteModalId').val(btn.dataset.id);
            $('#deleteModalName').text(btn.dataset.name);
        });

    });
</script>
