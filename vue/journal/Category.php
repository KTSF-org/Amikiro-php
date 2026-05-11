<?php

/**
 * VUE : Category.php
 */
use app\util\Helper;
?>

<link rel="stylesheet" href="https://cdn.datatables.net/2.3.8/css/dataTables.dataTables.css" />

<script src="https://cdn.datatables.net/2.3.8/js/dataTables.js"></script>


<div class="container mt-4">
    <h2>Liste des Catégories</h2>

    <table id="categoryTable" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Id</th>
				<th>Nom</th>
				<th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categories as $category): ?>
                <tr>
                    <td><?= $category->getId()?></td>
                    <td><?= $category->getName()?></td>
					<td>
						<button id="modif" class=" btn btn-primary">Modifier</button>
						<button id="delete" class="btn btn-danger">Supprimer</button>
					</td>

                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

<div class="mt-4 pb-5">
        <div class="card card-body">
            <form method="POST" action="<?= $actual_link ?>category"></form> id="addCategory">
                <div class="row align-items-end">
                    <div class="col-md-3">
                        <label for="cat" class="form-label">Rajouter une catégorie</label>
                        <input type="text" class="form-control" id="name" placeholder="Nom de la catégorie" name="categoryName">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary">Ajouter</button>
						<span id="formMessage"></span>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready( function () {
        const table = $('#categoryTable').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json' // Pour avoir l'interface en français
            },
            pageLength: 10,
            responsive: true,
			columnDefs: [
				{"width": "2px" , "targets":0},

			]
        }

	);

	$('#addCategory').on('submit',function(e)){
		e.preventDefault();

		const spanMessage = $('#formMessage');
		const url = "<?= $actual_link ?>ajax?addCategory",

		data = {
			'name' : $('#name').val(),
		};

		const request = new AjaxRequest(url, 'POST', data);
		request.send(
			(response)=>{
				if(response === "Success") {
					spanMessage.text('Catégorie créée avec succès').css('color', 'green');
					table.ajax.reload();
				}else {
					 spanMessage.text('Problème lors de la création').css('color', 'red');
				}
			}


		)



	}


	} );
</script>
