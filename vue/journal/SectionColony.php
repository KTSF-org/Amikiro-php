<?php

/**
 * VUE : SectionColony.php
 */

?>

<div class="container">

    <p>Création fiche colonie (formulaire avec les champs à remplir)</p>

    <form method="POST" action="<?= $actual_link?>sectionColony">
        <!-- Champs du formulaire -->
        <div class="mb-3 col-3">
            <label for="colonyTitle" class="form-label">Titre rubrique</label>
            <input type="text" class="form-control" id="title" placeholder="Titre rubrique" >
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
           <select class="form-select" id="floatingSelect" aria-label="Floating label select example" name="colonyCategory">
                <option selected>Veuillez choisir une catégorie...</option>
                <?= $categories?>
            </select>
        </div>
        <div class="mb-3 col-3">
            <label for="colonyObservation" class="form-label">Observations</label>
            <textarea class="form-control" id="observation" rows="3" placeholder="" name="colonyNotes"></textarea>
        </div>
        <div class="mb-3 col-3">
            <button type="submit" class="btn btn-primary">Enregistrer</button>
        </div>  

    </form>



</div>
<script>
    $doument.ready(function(){
        $(document).on("submit", "form", function(e){
            e.preventDefault();
            $.ajax({
                url: "controller/SectionColony.php",
                method: "POST",
               
            });
        });

    })