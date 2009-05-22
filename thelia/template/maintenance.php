<?php
	header("HTTP/1.x 503 Service Unavailable");
	header("Status:503 Service Unavailable");
?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
		<head>

	<link href="styles.css" rel="stylesheet" type="text/css" />

	</head>
	<body>

	<!-- wrapper & subwrapper -->

	<div id="wrapper">
		<div id="subwrapper">

	<!-- chemin -->	

			<div id="chemin">
			Vous &ecirc;tes ici :
			<a href="index.php" class="LIEN_chemin">Accueil boutique</a> / Maintenance

			</div>

	<!-- contenu -->

			<div id="content">

				<div id="contenu">

	<!-- Titre de la rubrique -->

					<div class="titrePage">
					<h2>Maintenance</h2>
					</div>

	<!-- Contenu de la page -->
		  <p class="commentaires">La boutique est en cours de maintenance</p>
	          <p class="commentaires">Merci pour votre compréhension</p>
				</div>
			</div>
		</div>
	</div>
	</body>
	</html>
