<?php

	session_start();
	
	if($_POST['choixbase']) $_SESSION['choixbase'] = $_POST['choixbase'];
	
	mysql_connect($_SESSION['serveur'], $_SESSION['utilisateur'], $_SESSION['motdepasse']);
	if( ! mysql_select_db($_SESSION['choixbase']))
		{ header("Location: choixbase.php?err=1"); exit; }
	
	$sql = file_get_contents("thelia.sql");
	$sql = ereg_replace(";',", "-CODE-", $sql);
		
	$tab = explode(";", $sql);
	
	for($i=0; $i<count($tab); $i++){
		$query = str_replace("-CODE-", ";',", $tab[$i]);
		$query = str_replace("|", ";", $query);
		mysql_query($query);
	}
?>

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
			<h2>V&eacute;rification des droits</h2>
		
				<form action="configuration.php" method="post">
				<input type="hidden" name="serveur" value="<?php echo $_SESSION['serveur']; ?>" />
				<input type="hidden" name="utilisateur" value="<?php echo $_SESSION['utilisateur']; ?>" />
				<input type="hidden" name="motdepasse" value="<?php echo $_SESSION['motdepasse']; ?>" />
				<input type="hidden" name="choixbase" value="<?php echo $_SESSION['choixbase']; ?>" />				
				<br />
								
				Nous allons v&eacute;rifier certains droits sur les fichiers et les r&eacute;pertoires <br /><br />
				
			
			
				<?php 
					$err=0;
					$liste = array("../", "../classes", "../client.orig", "../client.orig/cache", "../client.orig/commande", "../client.orig/document",  "../client.orig/plugins", "../client.orig/gfx", "../client.orig/gfx/photos", "../client.orig/gfx/photos/produit", "../client.orig/gfx/photos/rubrique", "../client.orig/gfx/photos/contenu", "../client.orig/gfx/photos/dossier", "../client.orig/gfx/utilisateur", "../client.orig/gfx/utilisateur/Image", "../client.orig/gfx/utilisateur/Flash");
				?>
				
				<?php 
				
				for($i=0; $i<count($liste); $i++)
			
					
						if( ! is_writable($liste[$i])) {
				
				?>
						<span class="erreur">Le répertoire <?php echo $liste[$i] ?> n'est pas accessible en écriture</span><br />
				<?php	
							$err=1;	
						}
		
					
				?>
				
				<br />
				
					<?php 
						$liste = array("../classes/Cnx.class.php.orig");
					?>

					<?php 

					for($i=0; $i<count($liste); $i++)

						if( ! is_writable($liste[$i])) {

					?>
						<span class="erreur">Le fichier <?php echo $liste[$i] ?> n'est pas accessible en écriture</span><br />
					<?php	
						$err=1;	
						}
					?>
					
					<br />
									
				<?php
					if(!$err){
				?>
				
					<span class="valide">Les droits sont corrects</span>
					<br /><br />
					<input type="submit" value="Continuer" />
					
				<?php
				
					} else {
				?>
					
					<input type="button" value="Rafraichir" onclick="location='verifdroits.php'" />
				
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
