<?php

/**
 * VUE : Accueil.php
 */

?>
<div class="accueil-hero">
	<div class="container">

		<div class="accueil-logo">
			<img src="<?= ASSET ?>/img/app.png" alt="Logo Amikiro" />
		</div>

		<h1 class="accueil-titre">AMIKIRO LIVE</h1>
		<p class="accueil-sous-titre">Maison des Chauves-Souris</p>

		<?php if ($user): ?>
		<p class="accueil-connecte">Bienvenue, <?= htmlspecialchars($user['name']) ?> <?= htmlspecialchars($user['surname']) ?></p>
	<?php endif; ?>

	<hr class="accueil-sep" />

		<div class="row justify-content-center">
			<div class="col-md-7">
				<section class="accueil-bloc">
					<h2>La Maison des Chauves-Souris</h2>
					<p>
						La Maison des Chauves-Souris est un centre dédié à l'étude, la protection et la sensibilisation
						autour des chiroptères. Nous accueillons colonies et spécimens dans un environnement contrôlé,
						et travaillons avec chercheurs et bénévoles pour mieux comprendre ces animaux essentiels à
						nos écosystèmes.
					</p>
				</section>

				<section class="accueil-bloc">
					<h2>L'application Amikiro</h2>
					<p>
						Amikiro centralise le suivi des spécimens, la gestion des sections de colonie et la diffusion
						de contenus vidéo en direct. Elle est réservée aux membres et collaborateurs de la Maison
						des Chauves-Souris.
					</p>
				</section>
			</div>
		</div>

	</div>
</div>
