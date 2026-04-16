<?php
/** VUE : Admin / Utilisateurs */
function roleLabel(int $code): string {
    return match($code) {
        ROLE_INVITE      => 'Invité',
        ROLE_ADHERENT    => 'Adhérent',
        ROLE_NATURALISTE => 'Naturaliste',
        ROLE_ADMIN       => 'Administrateur',
        default          => 'Inconnu',
    };
}
?>
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Gestion des utilisateurs</h1>
        <a href="<?= $actual_link ?>parametres/utilisateurs?page=creer" class="btn btn-success">+ Créer un compte</a>
    </div>

    <!-- Recherche AJAX -->
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
            <div id="ajaxResult" style="display:none"
                 class="alert alert-info d-flex justify-content-between align-items-center mb-0">
                <span id="ajaxResultText"></span>
                <div class="d-flex gap-2">
                    <a id="btnEditer" href="#" class="btn btn-sm btn-primary">Éditer</a>
                    <form id="formDeleteSearch" method="POST"
                          action="<?= $actual_link ?>parametres/utilisateurs"
                          class="d-inline"
                          onsubmit="return confirm('Supprimer ce compte ?')">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" id="deleteSearchId" value="">
                        <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                    </form>
                </div>
            </div>
            <div id="ajaxNoResult" style="display:none" class="text-muted">
                <small>Aucun résultat.</small>
            </div>
        </div>
    </div>

    <!-- Liste complète -->
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
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                <tr>
                    <td colspan="7" class="text-center text-muted">Aucun utilisateur.</td>
                </tr>
                <?php else: foreach ($users as $u): ?>
                <tr>
                    <td><?= (int)$u->id ?></td>
                    <td><?= htmlspecialchars($u->name) ?></td>
                    <td><?= htmlspecialchars($u->surname) ?></td>
                    <td><?= htmlspecialchars($u->mail) ?></td>
                    <td><?= roleLabel((int)$u->codeRole) ?></td>
                    <td><?= (int)$u->memberNum ?></td>
                    <td>
                        <a href="<?= $actual_link ?>parametres/utilisateurs?page=editer&id=<?= (int)$u->id ?>"
                           class="btn btn-sm btn-primary">Éditer</a>
                        <?php if ((int)$u->id !== $currentId): ?>
                        <form method="POST"
                              action="<?= $actual_link ?>parametres/utilisateurs"
                              class="d-inline"
                              onsubmit="return confirm('Supprimer ce compte ?')">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= (int)$u->id ?>">
                            <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>

</div>

<script>
$(document).ready(function () {
    $('#searchUser').on('input', function () {
        const term = $(this).val();

        if (term.length < 2) {
            $('#ajaxResult, #ajaxNoResult').hide();
            return;
        }

        $('#ajaxLoader').show();
        $('#ajaxResult, #ajaxNoResult').hide();

        const request = new AjaxRequest(
            '<?= $actual_link ?>ajax?findUsers',
            'POST',
            { name: term }
        );

        request.send(
            (response) => {
                $('#ajaxLoader').hide();
                if (response && response.id) {
                    $('#ajaxResultText').text(
                        response.name + ' ' + response.surname + ' — ' + response.mail
                    );
                    $('#btnEditer').attr(
                        'href',
                        '<?= $actual_link ?>parametres/utilisateurs?page=editer&id=' + response.id
                    );
                    $('#deleteSearchId').val(response.id);
                    $('#ajaxResult').show();
                } else {
                    $('#ajaxNoResult').show();
                }
            },
            () => { $('#ajaxLoader').hide(); }
        );
    });
});
</script>
