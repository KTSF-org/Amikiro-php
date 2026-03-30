<?php

/**
 * VUE : AJAX : ajaxRechercher.php
 */

?>
<div class="d-flex justify-content-center align-items-center">
	<div class="input-group" style="max-width:450px">
		<span class="input-group-text"><small>Requête AJAX :</small></span>
		<input type="text" id="users" placeholder="Rechercher un utilisateur" autocomplete="off" class="form-control">
		<span id='bg' class="input-group-text">
			<div id="loader" style="display:none"><span class="spinner-border spinner-border-sm"></span></div>
			<span id="results"><small>Résultat</small></span>
		</span>
	</div>
</div>

<script>
	//le DOM est chargé :
	$(document).ready(function() {
		//action sur les touches du clavier
		$('#users').on('input', function() {

			//Sélecteurs
			const divAvatar = $('#avatar'),
				divResult = $('#results'),
				divBg = $('#bg');

			//Préparation de la requête :
			divResult.hide();
			divBg.css('background', '#F8F9FA');
			$('#loader, .load-bloc').show();
			divAvatar.css('opacity', '0');

			let imgsrc = divAvatar.attr('src');
			
			// La constante issue de : controleur/util/CustomJS.php
			// peut être utilisée comme ceci :
			// const url = mainUrl + 'ajax?findUsers',
			//
			// Dans l'exemple ci-dessus PHP est directement utilisé avec :
			// <?= $actual_link ?>
			//
			//url réelle : http://localhost/apptest/ajax?findUsers
			const url = '<?= $actual_link ?>ajax?findUsers',
				data = {
					'name': $(this).val(), //données du textbox "name"
				};
			//Requête AJAX issue de asset/js/main.js, deux promesses (response) : success et complete
			const request = new AjaxRequest(url, 'POST', data);
			request.send(
				//AJAX : success :
				(response) => {
					// retour JSON sur la console
					// console.log(response);
					divBg.css('background', '#F8F9FA');
					divResult.html('<small>Aucune donnée</small>');
					if (response) {
						divResult.text(response.prenom + ' ' + response.nom);
						$('#userid').text(divResult.text());
						//url rélle : http://localhost/img=?=f=nom_de_limage_sans_extension_png
						imgsrc = '<?= $actual_link ?>img?f=' + response.avatar;
						divBg.css('background', '#DFFFCC');
					}
				},
				//AJAX : complete :
				(response) => {
					//on charge l'image :
					divAvatar.attr('src', imgsrc); 
					divAvatar[0].onload = function() {
						divAvatar.css('opacity', '1');
						$('.load-bloc').hide()
					};
					//fin de la requête AJAX :
					$('#loader').hide()
					$('#results').show();
				}
			);
		});
	});
</script>