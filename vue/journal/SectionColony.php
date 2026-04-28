<?php

/**
 * VUE : SectionColony.php
 */

?>

<div class="container">

    <p>Création fiche colonie (formulaire avec les champs à remplir)</p>

    <form id="colonyForm" method="POST" action="<?= $actual_link?>sectionColony">
        <!-- Champs du formulaire -->
        <div class="mb-3 col-3">
            <label for="colonyTitle" class="form-label">Titre rubrique</label>
            <input type="text" class="form-control" id="title" placeholder="Titre rubrique" name="colonyTitle">
        </div>
        <div class="mb-3 row col-8">
            <div class="mb-3 col-3">
            <label for="Date" class="form-label">Date</label>
            <input type="datetime-local" class="" id="date" name="colonyDate">
            </div>
            <div class="mb-3 col-3">
            <label for="Date" class="form-label">Heure</label>
            <input type="time" class="form-control" id="time" name="colonyHour">
            </div>
        </div>
        <div class="mb-3 col-3">
            <label for="colonyCategory" class="form-label">Catégorie</label>
           <select class="form-select" id="ategory" aria-label="Floating label select example" name="colonyCategory">
                <option selected>Veuillez choisir une catégorie...</option>
                <?= $categories?>
            </select>
        </div>
        <div class="mb-3 col-3">
            <label for="colonyObservation" class="form-label">Observations</label>
            <textarea class="form-control" id="observation" rows="3" placeholder="" name="colonyNotes"></textarea>
        </div>
        <span id="formMessage"></span>
        <div class="mb-3 col-3">
            <button type="submit" class="btn btn-primary">Enregistrer</button>
        </div>  

    </form>



</div>
<script>
    $(document).ready(function(){
        $("#colonyForm").on("submit", function(e){
            e.preventDefault();

            const spanMessage = $('#formMessage');  

            const url = "<?= $actual_link ?>ajax?addSectionColony",
                data = {
                    'title': $('#title').val(),
                    'date': $('#date').val(),
                    'category': $('#category').val(),
                    'notes' : $('#observation').val(),
                };
        
        const request = new AjaxRequest(url, 'POST', data );
        request.send(
            //success
            (reponse)=> {
                console.log(response);
                if(reponse){
                    spanMessage.text('Rubrique créée avec succès');
                }else{
                    spanMessage.text('Erreur lors de la création de la rubrique');
                }
            }
        )      


        });

    })
</script>    