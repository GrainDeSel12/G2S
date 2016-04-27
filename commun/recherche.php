<?php
	/* Traitement du post */ 
	$cuisine = array ('Burger', 'Italien', 'Japonais', 'Végétarien', 'Chinois');
	$ambiance = array ('Romantique', "Repas_d'affaire", 'Entre_amis', 'Sur_le_pouce');
	$location = array ('Paris', 'Londres');
	foreach ($cuisine as $element) {
		if (isset($_POST[$element])) {
			$cuisinechecked[] = $element;
		}
	}
	foreach ($ambiance as $element) {
		if (isset($_POST[$element])) {
			$ambiancechecked[] = $element;
		}
	}
	foreach ($location as $element) {
		if (isset($_POST[$element])) {
			$locationchecked[] = $element;
		}
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		</meta><link rel="stylesheet" media = "all and (min-width:1024px)" href="CSS/style.css" />
		<link rel="stylesheet" media="screen and (min-width: 480px) and (max-width: 1024px)" href="CSS/style_medium.css" />
		<!--<link rel="stylesheet" media="all and (max-device-width: 480px)" href="CSS/style_small.css" />-->
		<link rel="stylesheet" media="all and (max-width: 480px)" href="CSS/style_small.css" />
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
		<script src="JS/jquery.flexslider.js"></script>
		<title>GdS - Recherche</title>
	</head>
	<body>
		<div id="bloc_page">
			<header>
<!-- ********************************************************************************************************************************* -->
				<nav> <!--Début Menu Principal-->
					<div id ="top">
						<img id = "logotop"src = "Images/logositegrand.png" width="90" height="90" alt = "Logo">
						<label for="show-menu" class="show-menu">
							<div class="menu-icon">
								<span></span>
							</div>
							<p id = titresmall>Nol Bou</p>
						</label>
					</div>
					<input type="checkbox" id="show-menu" role="button">
					<img id = "slogan" src ="Images/slogan1.png" alt = "slogan">
					<img id = "ustensil1"src = "Images/ustensil1.png" alt = "Ustensiles de cuisine1">
					<img id = "ustensil2"src = "Images/ustensil2.png" alt = "Ustensiles de cuisine2">
					<ul class = "menu">
						<li><a href="#">Accueil</a></li><!--
						--><li><a href="#">Critiques  </a><!--
							--><ul class = "menu2"><!--
								--><li><a href="#">Ile-de-France</a></li><!--
								--><li><a href="#">France</a></li><!--
								--><li><a href="#">Monde</a></li><!--
							--></ul><!--
						--></li><!--
					--><li class = "logo"><!--
					--><img id = "logo"src = "Images/test.png" width="160" height="160" alt = "Logo"></li><!--
						--><li><a href="#">Coups de coeur</a></li><!--
						--><li><a href="#">Articles</a></li>
					</ul>
				</nav>
			</header>
<!-- ********************************************************************************************************************************* -->			
				<aside id="bloc_aside">
						<div id="reseaux_sociaux">
							<a href="#" id ="fb" class="rs"></a>
							<a href="#" id = "twitter" class="rs"></a>
							<a href="#" id = "instagram" class="rs" ></a>
							<a href="#" id = "pinterest" class="rs"></a>
						</div>
						<div id="bloc_recherche">
						</div>
				</aside>
			<section>
				<p> Voici les résultats de la recherche </p>
				<em> Actualisés sans recharger la page bien sur. </em>
				<div id="res_recherche">
					<?php
						if (isset($cuisinechecked)) {
							echo "<p> Vous souhaitez donc manger :";
							foreach ($cuisinechecked as $element) {
								echo str_replace('_', ' ',$element);
								echo ", ";
							}
						}
						if (isset($ambiancechecked)) {
							echo "</p><p>dans une ambiance :";
							foreach ($ambiancechecked as $element) {
								echo str_replace('_', ' ',$element);
								echo ", ";
							}
						}
						if (isset($locationchecked)) {
							echo "</p><p>à  :";
							foreach ($locationchecked as $element) {
								echo str_replace('_', ' ',$element);
								echo ", ";
							}
						}
					?>
				</div>
			</section>
		</div>
	</body>
</html>