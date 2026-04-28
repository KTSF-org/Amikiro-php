<?php
function roleLabel(int $code): string {
    return match($code) {
        ROLE_INVITE      => 'Invité',
        ROLE_ADHERENT    => 'Adhérent',
        ROLE_NATURALISTE => 'Naturaliste',
        ROLE_ADMIN       => 'Administrateur',
        default          => 'Inconnu',
    };
}

function memberNumCell(object $u): string {
    $role = (int)$u->codeRole;
    $num  = $u->memberNum ?? '';

    if ($role === ROLE_INVITE) {
        return empty($num)
            ? '<span class="text-muted">INVITE</span>'
            : '<span class="text-danger fw-semibold">' . htmlspecialchars($num) . '</span>';
    }
    if (empty($num)) {
        return '<span class="text-muted">—</span>';
    }
    return '<span class="text-success fw-semibold">' . htmlspecialchars($num) . '</span>';
}

function accessCell(int $userId, array $activeByUser): string {
    if (!isset($activeByUser[$userId])) {
        return '<span class="badge bg-secondary">Aucun</span>';
    }
    $sub     = $activeByUser[$userId];
    $end     = new \DateTime($sub->endDate);
    $today   = new \DateTime('today');
    $daysLeft = (int)$today->diff($end)->days;
    $label   = date('d/m/Y', strtotime($sub->endDate));

    if ($daysLeft <= 7) {
        return '<span class="badge bg-warning text-dark" title="Expire le ' . $label . '">Expire dans ' . $daysLeft . 'j</span>';
    }
    return '<span class="badge bg-success">' . $label . '</span>';
}
?>
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Gestion des utilisateurs</h1>
        <a href="<?= $actual_link ?>parametres/utilisateurs?page=create" class="btn btn-success">+ Créer un compte</a>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3">Paramètres d'accès</h5>
            <form method="POST" action="<?= $actual_link ?>parametres/utilisateurs"
                  class="row g-2 align-items-end">
                <input type="hidden" name="action" value="saveConfig">
                <div class="col-auto">
                    <label for="guestDefaultAccessDays" class="form-label mb-1">
                        Durée d'accès invité par défaut
                        <small class="text-muted">(après expiration d'une adhésion)</small>
                    </label>
                    <div class="input-group">
                        <input type="number" class="form-control" id="guestDefaultAccessDays"
                               name="guestDefaultAccessDays" min="1"
                               value="<?= (int)$guestDefaultAccessDays ?>" style="max-width:100px">
                        <span class="input-group-text">jours</span>
                    </div>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3">Recherche rapide</h5>
            <div class="input-group mb-3" style="max-width:450px">
                <input type="text" id="searchUser" class="form-control"
                       placeholder="Nom ou prénom..." autocomplete="off">
                <span class="input-group-text">
                    <span id="ajaxLoader" style="display:none">
                        <span class="spinner-border spinner-border-sm"></span>
                    </span>
                </span>
            </div>
            <div id="ajaxResults" style="display:none"></div>
            <div id="ajaxNoResult" style="display:none" class="text-muted">
                <small>Aucun résultat.</small>
            </div>
        </div>
    </div>

    <!-- Filtre par rôle -->
    <ul class="nav nav-pills mb-3">
        <?php
        $roles = [
            -1 => 'Tous',
            ROLE_INVITE      => 'Invités',
            ROLE_ADHERENT    => 'Adhérents',
            ROLE_NATURALISTE => 'Naturalistes',
        ];
        foreach ($roles as $code => $label):
            $active = ($roleFilter === $code) ? 'active' : '';
            $href   = $actual_link . 'parametres/utilisateurs' . ($code >= 0 ? '?role=' . $code : '');
        ?>
        <li class="nav-item">
            <a class="nav-link <?= $active ?>" href="<?= $href ?>"><?= $label ?></a>
        </li>
        <?php endforeach; ?>
    </ul>

    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Prénom</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>N° adhérent</th>
                    <th>Temps d'accès</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                <tr>
                    <td colspan="8" class="text-center text-muted">Aucun utilisateur.</td>
                </tr>
                <?php else: foreach ($users as $u): ?>
                <tr>
                    <td><?= (int)$u->id ?></td>
                    <td><?= htmlspecialchars($u->name) ?></td>
                    <td><?= htmlspecialchars($u->surname) ?></td>
                    <td><?= htmlspecialchars($u->mail) ?></td>
                    <td><?= roleLabel((int)$u->codeRole) ?></td>
                    <td><?= memberNumCell($u) ?></td>
                    <td><?= accessCell((int)$u->id, $activeByUser) ?></td>
                    <td>
                        <a href="<?= $actual_link ?>parametres/utilisateurs?page=edit&id=<?= (int)$u->id ?>"
                           class="btn btn-sm btn-primary">Éditer</a>
                        <?php if ((int)$u->id !== $currentId): ?>
                        <button type="button" class="btn btn-sm btn-danger"
                                data-bs-toggle="modal" data-bs-target="#deleteModal"
                                data-id="<?= (int)$u->id ?>"
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

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
    <nav class="mt-3">
        <ul class="pagination justify-content-center">
            <?php for ($i = 1; $i <= $totalPages; $i++):
                $active = ($i === $currentPage) ? 'active' : '';
                $roleParam = $roleFilter >= 0 ? '&role=' . $roleFilter : '';
            ?>
            <li class="page-item <?= $active ?>">
                <a class="page-link"
                   href="<?= $actual_link ?>parametres/utilisateurs?p=<?= $i . $roleParam ?>">
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
            <div class="modal-header">
                <h5 class="modal-title">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Supprimer le compte de <strong id="deleteModalName"></strong> ?
                Cette action est irréversible.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form id="deleteForm" method="POST" action="<?= $actual_link ?>parametres/utilisateurs">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" id="deleteModalId" value="">
                    <button type="submit" class="btn btn-danger">Supprimer</button>
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
                    let html = '<ul class="list-group">';
                    response.forEach(u => {
                        html += `<li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>${u.name} ${u.surname} <small class="text-muted">— ${u.mail}</small></span>
                            <div class="d-flex gap-2">
                                <a href="<?= $actual_link ?>parametres/utilisateurs?page=edit&id=${u.id}"
                                   class="btn btn-sm btn-primary">Éditer</a>
                                <button type="button" class="btn btn-sm btn-danger"
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
