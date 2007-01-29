<?php

	mysql_connect($_POST['serveur'], $_POST['utilisateur'], $_POST['motdepasse']);
	mysql_select_db($_POST['choixbase']);
	
	$sql = file_get_contents("../../bdd/thelia.sql");
	
	$tab = explode(";", $sql);
	
	if(! mysql_numrows(mysql_list_tables($_POST['choixbase']))) 
		for($i=0; $i<count($tab); $i++)
		mysql_query($tab[$i]);
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
		
		<!-- Ent�te -->
		
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
	<THELIA_rss type="RSS" url="http://blog.thelia.fr/rss.php" deb="0" nb="1">
		
			<div id="chapeau"style="overflow:hidden;zoom: 1">
			<h2>V�rification des droits</h2>
		
				<form action="configuration.php" method="post">
				<input type="hidden" name="serveur" value="<?php echo $_POST['serveur']; ?>" />
				<input type="hidden" name="utilisateur" value="<?php echo $_POST['utilisateur']; ?>" />
				<input type="hidden" name="motdepasse" value="<?php echo $_POST['motdepasse']; ?>" />
				<input type="hidden" name="choixbase" value="<?php echo $_POST['choixbase']; ?>" />				
				<br />
								
				Nous allons v�rifier certains droits sur les fichiers et les r�pertoires <br /><br />
				
				<?php 
					$err=0;
					$liste = array("../classes/Cnx.class.php.orig");
				?>
				
				<?php 
				
				for($i=0; $i<count($liste); $i++)
				
					if( ! is_writable($liste[$i])) {
				
				?>
					<span class="erreur">Le fichier <?php echo $liste[$i] ?> n'est pas accessible en �criture</span><br />
				<?php	
					$err=1;	
					}
				?>
				


				<?php 

					$liste = array("../client.orig", "../client.orig/cache", "../client.orig/commande", "../client.orig/document",  "../client.orig/gfx", "../client.orig/gfx/photos", "../client.orig/gfx/photos/produit", "../client.orig/gfx/photos/produit/petite", "../client.orig/gfx/photos/produit/grande", "../client.orig/gfx/photos/rubrique", "../client.orig/gfx/photos/rubrique/petite", "../client.orig/gfx/photos/rubrique/grande", "../client.orig/gfx/photos/contenu", "../client.orig/gfx/photos/contenu/petite", "../client.orig/gfx/photos/contenu/grande", "../client.orig/gfx/photos/dossier", "../client.orig/gfx/photos/dossier/petite", "../client.orig/gfx/photos/dossier/grande"
					);
				?>
				
				<?php 
				
				for($i=0; $i<count($liste); $i++)
			
					
						if( ! is_writable($liste[$i])) {
				
				?>
						<span class="erreur">Le r�pertoire <?php echo $liste[$i] ?> n'est pas accessible en �criture</span><br />
				<?php	
							$err=1;	
						}
		
					
				?>
				
				
				
				<?php
					if(!$err){
				?>
				
					<span class="valide">Les droits sont corrects</span>
				
				<?php
				
					}
				?>
				
				<br /><br />
				
				
				<input type="submit" value="Continuer" />
	
			 </form>

			</div>

	</THELIA_rss>
	

			
		</div>
		

	</div>
	
	<!-- Plan du site -->
	
	<div id="planDuSite"style="overflow:hidden;zoom: 1">
		<div id="contenuPlanDuSite"style="overflow:hidden;zoom: 1">
		R&eacute;alisation <a href="http://www.octolys.fr">Octolys</a> <br />
		Charte graphique <a href="http://www.amplitude-thiers.com">Amplitude</a></div>
		<div id="footerPlanDuSite"style="overflow:hidden;zoom: 1">
		</div>
	</div>
</div>

<script type="text/javascript">
    initMenu();
</script>
</body>

</html>
