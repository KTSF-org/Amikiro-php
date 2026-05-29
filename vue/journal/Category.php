<?php

/**
 * VUE : Category.php
 * Variables reçues : aucune (données chargées via AJAX DataTable)
 *
 * Note : DataTables v1.13.6 est déjà chargé dans header.php.
 * NE PAS importer une autre version ici — un double chargement
 * provoque des conflits et casse l'initialisation du tableau.
 */
?>

<div class="container py-4">

    <h1 class="h3 mb-4">Catégories</h1>

    <!-- Tableau DataTable — données chargées par AJAX via ajax?getCategories -->
    <div class="card mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="categoryTable" class="table table-hover align-middle mb-0" style="width:100%">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Nom</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Formulaire d'ajout -->
    <div class="card">
        <div class="card-header bg-dark text-white py-2">
            <span class="fw-semibold small">Ajouter une catégorie</span>
        </div>
        <div class="card-body">
            <form method="POST" action="<?= $actual_link ?>category" id="addCategory">
                <div class="row g-2 align-items-end">
                    <div class="col-md-5">
                        <label for="name" class="form-label">Nom</label>
                        <input type="text" class="form-control" id="name"
                               placeholder="Nom de la catégorie" name="categoryName">
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">Ajouter</button>
                    </div>
                    <div class="col-12">
                        <p id="formMessage" class="form-feedback mb-0"></p>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
$(document).ready(function () {

    const table = $('#categoryTable').DataTable({
        language: {
            url: '<?= ASSET ?>/lib/datatables/i18n/fr-FR.json'
        },
        dom: '<"m-1"lf>rt<"m-1"ip>',
        pageLength: 10,
        responsive: true,
        columnDefs: [
            { width: '50px',  targets: 0 },
            { width: '25%',   targets: 2, className: 'text-end' },
        ],
        ajax: {
            url: '<?= $actual_link ?>ajax?getCategories',
            dataSrc: ''
        },
        columns: [
            { data: 'id' },
            { data: 'name' },
            {
                data: 'id',
                render: function (id) {
                    /*
                     * Les classes icon-edit et icon-delete sont définies dans main.css.
                     * On évite les inline style= pour rester cohérent avec la charte graphique.
                     * d-none est l'utilitaire Bootstrap à la place de style="display:none".
                     */
                    return `<button class="btn btn-sm btn-link p-1 btn-modif" data-id="${id}" title="Modifier">
                                <i class="bi bi-pencil-square icon-edit fs-5"></i>
                            </button>
                            <button class="btn btn-sm btn-link p-1 btn-delete" data-id="${id}" title="Supprimer">
                                <i class="bi bi-trash3 icon-delete fs-5"></i>
                            </button>
                            <div class="update d-none mt-2">
                                <input class="form-control form-control-sm nameUpdate mb-1"
                                       type="text" placeholder="Nouveau nom">
                                <button class="btn btn-sm btn-success btn-update" data-id="${id}">Valider</button>
                            </div>`;
                }
            }
        ]
    });

    // Ajout d'une catégorie
    $('#addCategory').on('submit', function (e) {
        e.preventDefault();
        const $msg = $('#formMessage').removeClass('success error');
        const url  = '<?= $actual_link ?>ajax?addCategory';
        const data = { name: $('#name').val() };

        const request = new AjaxRequest(url, 'POST', data);
        request.send((response) => {
            if (response === 'Success') {
                $msg.addClass('success').text('Catégorie créée avec succès.');
                table.ajax.reload();
                $('#name').val('');
            } else {
                $msg.addClass('error').text('Erreur lors de la création.');
            }
        });
    });

    // Suppression
    $(document).on('click', '.btn-delete', function () {
        const id   = $(this).data('id');
        const $msg = $('#formMessage').removeClass('success error');
        if (!confirm('Supprimer cette catégorie ?')) return;

        const request = new AjaxRequest('<?= $actual_link ?>ajax?delCategory', 'POST', { id });
        request.send((response) => {
            if (response === 'Success') {
                $msg.addClass('success').text('Catégorie supprimée.');
                table.ajax.reload();
            } else {
                $msg.addClass('error').text('Erreur lors de la suppression.');
            }
        });
    });

    // Affichage du champ inline d'édition
    $(document).on('click', '.btn-modif', function (e) {
        e.stopPropagation();
        $(this).closest('td').find('.update').toggleClass('d-none');
    });

    // Mise à jour
    $(document).on('click', '.btn-update', function () {
        const id   = $(this).data('id');
        const name = $(this).prev('.nameUpdate').val();
        const $msg = $('#formMessage').removeClass('success error');

        const request = new AjaxRequest('<?= $actual_link ?>ajax?updateCategory', 'POST', { id, name });
        request.send((response) => {
            if (response === 'Success') {
                $msg.addClass('success').text('Catégorie modifiée.');
                table.ajax.reload();
            } else {
                $msg.addClass('error').text('Erreur lors de la modification.');
            }
        });
    });

});
</script>
