$(document).ready(function () {
    const baseUrl = document.getElementById('usersApp').dataset.baseUrl;

    $('#searchUser').on('input', function () {
        const term = $(this).val();

        if (term.length < 2) {
            $('#ajaxResults, #ajaxNoResult').hide();
            return;
        }

        $('#ajaxLoader').show();
        $('#ajaxResults, #ajaxNoResult').hide();

        const request = new AjaxRequest(baseUrl + 'ajax?findUsers', 'POST', { name: term });

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
                            <a href="${baseUrl}parametres/utilisateurs?page=edit&id=${u.id}"
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

    $('#deleteModal').on('show.bs.modal', function (e) {
        const btn = e.relatedTarget;
        $('#deleteModalId').val(btn.dataset.id);
        $('#deleteModalName').text(btn.dataset.name);
    });
});
