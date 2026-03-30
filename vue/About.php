<?php

/**
 * VUE : About.php
 */

?>
<div class="container">

	<div class="spacer"></div>

	<div class="left">
		<li>C'est un test sur une route chargé depuis <b>: <?= $cls ?> </b></li>
		<li>Son espace de nom est <b>: <?= $ns ?> </b></li>
		<?php if ($version > 0) { ?>
			<li> Nous avons reçu GET['app'] qui a la valeur <b>: <?= $version ?> </b></li>
		<?php } ?>
		<?php if ($test !== false) { ?>
			<li> Nous avons reçu POST['aboutPost'] qui a la valeur <b>: <?= $test ?> </b></li>
		<?php } ?>
		<?php if (isset($about)) { ?>
			<li>Lecture du fichier <b><?= basename($readme) ?></b> :</li>
		<?php } ?>
	</div>
	
	<button id="popupTrigger" type="button" class="btn btn-primary btn-sm">Ouvrir ...</button>
	
	<div id="about" style="display:none">
		<?= $about ?>
	</div>

</div>

<script>
/** 
	### JS POPUP ###
	Dépendances : 
	-> asset/js/popup.js
	-> asset/css/popup.css
**/
document.addEventListener('DOMContentLoaded', function() {

	const popup = document.getElementById("jsPopup");
	const btn = document.getElementById("popupTrigger");
	const closeBtn = document.getElementsByClassName("close")[0];
	const popupContent = document.getElementById('popup-content');

	function togglePopup() {
		document.body.classList.toggle('popup-open');
		popup.style.display = popup.style.display === "block" ? "none" : "block";
	}

	btn.onclick = togglePopup;
	closeBtn.onclick = togglePopup;
	window.onclick = function(event) {
		if (event.target == popup) {
			togglePopup();
		}
	};

	// Affichage des contenus :
	document.getElementById('popup-title').textContent = 'Information';
	popupContent.innerHTML = document.getElementById("about").innerHTML;

});

/*
//adapté pour JQuery
$(document).ready(function() {
	$popup = $("#jsPopup"),
	$btn = $("#popupTrigger"),
	$closeBtn = $(".close"),
	$popupContent = $('#popup-content');

	function togglePopup() {
		$("body").toggleClass('popup-open');
		$popup.toggle();
	}

	$btn.on('click', togglePopup);
	$closeBtn.on('click', togglePopup);

	$(window).on('click', function(event) {
		if ($(event.target).is($popup)) {
			togglePopup();
		}
	});

	// Affichage des contenus :
	$('#popup-title').text('Information');
	$popupContent.html($("#about").html());
});
*/

</script>
