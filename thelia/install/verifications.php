<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>THELIA</title>

<link href="styles.css" rel="stylesheet" type="text/css" />
<link href="menuDeroulant.css" rel="stylesheet" type="text/css" />
<link href="menuDeroulanthorizontal.css" rel="stylesheet" type="text/css" />
</head>

<body>


	<!-- global wrapper -->

<div id="wrapper"style="overflow:hidden;zoom: 1">
		
		<!-- Entête -->
		
	<div id="entete"style="overflow:hidden;zoom: 1">
		<h1><span>Thelia</span></h1>
	</div>
	
		<!-- Menu -->
		
	<div id="contourMenu"style="overflow:hidden;zoom: 1">
		
		<div id="menuHorizontal">
		<dl>
			<dt><a href="#">ETAPE 4</a></dt>
		</dl>
		
		</div>
		
	</div>
		
		<!-- Contenu -->
		
	<div id="contenu"style="overflow:hidden;zoom: 1">
	
		<div id="colonneDeGauche"style="overflow:hidden;zoom: 1">
		
			<div id="chapeau"style="overflow:hidden;zoom: 1">
				
			<?php $verification = 1; ?>
				
			<h2>V&eacute;rification de la configuration syst&egrave;me</h2>
		
				<form action="bdd.php" method="post">
			
				<br />

				Nous allons v&eacute;rifier votre version de PHP <br /><br />

			    <?php
					if ( version_compare(PHP_VERSION, '5.0.0', '<')) {
						$verification = 0;
				?>
					<span class="erreur">Votre serveur n'est pas compatible avec PHP5</span>
					<br /><hr /><br />
				<?php
				    } else {
				?>
				<span class="valide">Votre version de PHP est correct.</span>
				<br /><hr /><br />				
				<?php
					}
				?>

				Nous allons v&eacute;rifier la pr&eacute;sence des librairies n&eacute;cessaires <br /><br />

			    <?php
					if ( ! function_exists ("imagefilledarc")) {
						$verification = 0;
				?>
				<span class="erreur">Veuillez installer/activer la librairie GD</span>
				<br /><hr /><br />
				<?php
				    } else {
				?>
					<span class="valide">Les librairies n&eacute;cessaires sont pr&eacute;sente.</span>
					<br /><hr /><br />
							
				<?php
					}
				?>	
				Nous allons v&eacute;rifier certains droits sur les fichiers et les r&eacute;pertoires <br />
				
			
				<?php 
					$err=0;
					$liste = array("../", "../admin", "../lib", "../fonctions", "../classes", "../client.orig", "../client.orig/cache", "../client.orig/commande", "../client.orig/document",  "../client.orig/plugins", "../client.orig/gfx", "../client.orig/gfx/photos", "../client.orig/gfx/photos/produit", "../client.orig/gfx/photos/rubrique", "../client.orig/gfx/photos/contenu", "../client.orig/gfx/photos/dossier", "../client.orig/gfx/utilisateur", "../client.orig/gfx/utilisateur/Image", "../client.orig/gfx/utilisateur/Flash");
				?>
				
				<?php 
				
				for($i=0; $i<count($liste); $i++)
			
					
						if(! is_writable($liste[$i])) {
				
				?>
						<span class="erreur">Le r&eacute;pertoire <?php echo $liste[$i] ?> n'est pas accessible en &eacute;criture</span><br />
				<?php	
							$err=1;	
						}
		
					
				?>
				
				
					<?php 
						$liste = array("../classes/Cnx.class.php.orig");
					?>

					<?php 

					for($i=0; $i<count($liste); $i++)

						if(! is_writable($liste[$i])) {

					?>
						<span class="erreur">Le fichier <?php echo $liste[$i] ?> n'est pas accessible en &eacute;criture</span><br />
					<?php	
						$verification = 0;
						}
					?>
					
					<br />
									
				<?php
					if($verification){
				?>
				
					<span class="valide">Les droits sont corrects</span>
					<br /><br />
					<input type="submit" value="Continuer" />
					
				<?php
				
					} else {
				?>
					
					<input type="button" value="Recharger" onclick="location='verifications.php'" />
				
				<?php
					}
				?>
				
			 </form>

			</div>
			
		</div>
		

	</div>
	
	<!-- Plan du site -->
	
	<div id="planDuSite"style="overflow:hidden;zoom: 1">
		<div id="contenuPlanDuSite"style="overflow:hidden;zoom: 1">
		R&eacute;alisation <a href="http://www.octolys.fr">Octolys</a> <br />
		Charte graphique <a href="http://www.scopika.com">Scopika </a></div>
		<div id="footerPlanDuSite"style="overflow:hidden;zoom: 1">
		</div>
	</div>
</div>

</body>

</html>
