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

        </tbody>
    </table>

<div class="mt-4 pb-5">
        <div class="card card-body">
            <form method="POST" action="<?= $actual_link ?>category" id="addCategory">
                <div class="row align-items-end">
                    <div class="col-md-3">
                        <span id="formMessage"></span>
                        <label for="cat" class="form-label">Ajouter une catégorie</label>
                        <input type="text" class="form-control" id="name" placeholder="Nom de la catégorie" name="categoryName">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary">Ajouter</button>
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
                url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json'
            },
            pageLength: 10,
            responsive: true,
            columnDefs: [
                {"width": "2px", "targets": 0},
            ],

            ajax: {
                url: "<?= $actual_link ?>ajax?getCategories",
                dataSrc: ''
            },
            columns: [
                { data: 'id' },
                { data: 'name' },
                {
                    data: 'id',
                    render: function(id) {
                        return `<button class="btn btn-primary btn-modif" data-id="${id}">Modifier</button>
                            <button class="btn btn-danger btn-delete" data-id="${id}">Supprimer</button>
                            <div class="update" style="display:none">
                            <input class="form-control nameUpdate" type="text" placeholder="saisir le nom à modifier">
                            <button class="btn btn-success btn-update" data-id="${id}">Valider</button>
                            </div>
                            `;
                        //ne pas mettre des id mais des classes car répétées plusieurs fois dans le tableau
                    }
                }//TODO gérer la modif et suppression comme au stage
        ]
        });



	$('#addCategory').on('submit',function(e){
		e.preventDefault();

		const spanMessage = $('#formMessage');
		const url = "<?= $actual_link ?>ajax?addCategory";

		data = {
			'name' : $('#name').val(),
		};

		const request = new AjaxRequest(url, 'POST', data);
		request.send(
			(response)=>{
				if(response === "Success") {
					spanMessage.text('Catégorie créée avec succès').css('color', 'green');
                    table.ajax.reload(); //reload du datatable
                    $('#name').val(''); //on vide le champ texte
				}else {
					 spanMessage.text('Problème lors de la création').css('color', 'red');
				}
			}


		)



	});

    $(document).on('click', '.btn-delete', function(){
        const id = $(this).data('id');
        const spanMessage = $('#formMessage');
        const url = "<?= $actual_link ?>ajax?delCategory";
        data={
            'id' : id,
        };

        const request = new AjaxRequest(url, 'POST', data);

        if(confirm("Voulez-vous vraiment supprimer cette catégorie? ")){
            request.send(
                (response)=>{
                    if(response === "Success") {
                        spanMessage.text('Catégorie supprimée avec succès').css('color', 'green');
                        table.ajax.reload(); //reload du datatable
                        $('#name').val(''); //on vide le champ texte
                    }else {
                        spanMessage.text('Problème lors de la suppression').css('color', 'red');
                    }
                }
            )

        }

    })


    $(document).on('click', '.btn-modif', function(event){ //affichage d'un champ et d'un bouton pour l'update
        event.stopPropagation();
        const divUpdate = $(this).parent().find('.update');
        divUpdate.slideToggle();
    })

    $(document).on('click', '.btn-update', function(){
        const id =$(this).data('id');
        const spanMessage = $('#formMessage');
        const url = "<?= $actual_link ?>ajax?updateCategory";

        data={
            'id' : id,
            'name' : $(this).prev().val(),
        }

        const request = new AjaxRequest(url, 'POST', data);

        request.send(
            (response)=>{
                if(response === "Success"){
                    spanMessage.text('Catégorie modifiée avec succès').css('color', 'green');
                    table.ajax.reload();
                }else{
                    spanMessage.text('Problème lors de la modification').css('color','red');
                }
            }
        )
    })



});
</script>
