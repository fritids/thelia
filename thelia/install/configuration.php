<?php
	
	session_start();
	
	$fic = file_get_contents("../classes/Cnx.class.php.orig");

	if(! file_exists("../classes/Cnx.class.php")){
	
		$fic = ereg_replace("votre_serveur", $_SESSION['serveur'], $fic);
		$fic = ereg_replace("votre_login_mysql", $_SESSION['utilisateur'], $fic);
		$fic = ereg_replace("votre_motdepasse_mysql",  $_SESSION['motdepasse'], $fic);
		$fic = ereg_replace("bdd_sql", $_SESSION['choixbase'], $fic);	
		
		$fp = fopen("../classes/Cnx.class.php.orig", "w");
		fputs($fp, $fic);
		fclose($fp);
		
		rename("../classes/Cnx.class.php.orig", "../classes/Cnx.class.php");
		rename("../client.orig", "../client");
		
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
			<dt><a href="#">ETAPE 5</a></dt>
		</dl>
		
		</div>
		
	</div>
		
		<!-- Contenu -->
		
	<div id="contenu"style="overflow:hidden;zoom: 1">
	
		<div id="colonneDeGauche"style="overflow:hidden;zoom: 1">
	<THELIA_rss type="RSS" url="http://blog.thelia.fr/rss.php" deb="0" nb="1">
		
			<div id="chapeau"style="overflow:hidden;zoom: 1">
			<h2>Configuration</h2>
		
				<form action="fin.php" method="post">
			
				<br />
								
				Passons à la configuration de Thelia <br /><br />
				
				<?php
					include("../classes/Variable.class.php");
				?>
			
				<?php										
					$var = new Variable();
					$var->charger("emailcontact");
				?>


				<?php if(isset($_GET['err']) && $_GET['err']) { ?>
				
					<span class="erreur">Veuillez vérifier votre nom d'utilisateur/mot de passe</span>
					
				<?php } ?>				
				
				<div class="col">Nom d'utilisateur (administration) :</div>
				<div class="col"><input type="text" name="utilisateur" value="admin" size="30" /></div> 
				
				<div class="col">Mot de passe :</div>
				<div class="col"><input type="password" name="motdepasse1" size="30" /></div> 
				
				<div class="col">Vérification du mot de passe :</div>
				<div class="col"><input type="password" name="motdepasse2" size="30" /></div> 
				
												
				<div class="col">E-Mail de contact :</div>
				<div class="col"><input type="text" name="emailcontact" value="<?php echo $var->valeur ?>" size="30" /></div> 	
				
				
				<?php										
					$var = new Variable();
					$var->charger("nomsite");
				?>

				<div class="col">Nom du site :</div>
				<div class="col"><input type="text" name="nomsite" value="<?php echo $var->valeur ?>" size="30" /></div> 
								
				<?php										
					$var = new Variable();
					$var->charger("urlsite");
				?>
															
				<div class="col">Adresse du site :</div>
				<div class="col"><input type="text" name="urlsite" value="<?php echo $var->valeur ?>" size="30" /></div> 
			
				<?php										
					$var = new Variable();
					$var->charger("photorubw");
				?>
							
				<div class="col">Taille de la petite vignette produit :</div>
				<div class="col"><input type="text" name="photorubw" value="<?php echo $var->valeur ?>" size="30" /></div> 				
				
				
				<?php										
					$var = new Variable();
					$var->charger("photoprodw");
				?>				
				
				<div class="col">Taille de la petite vignette rubrique :</div>
				<div class="col"><input type="text" name="photoprodw" value="<?php echo $var->valeur ?>" size="30" /></div> 		
				
				<?php										
					$var = new Variable();
					$var->charger("tva");
				?>
				
				<div class="col">TVA par défaut :</div>
				<div class="col"><input type="text" name="tva" value="<?php echo $var->valeur ?>" size="30" /></div> 				
				<?php										
					$var = new Variable();
					$var->charger("style_chem");
				?>
								
				<div class="col">Feuille de style de l'éditeur :</div>
				<div class="col"><input type="text" name="style_chem" value="<?php echo $var->valeur ?>" size="30" /></div> 		

				<?php										
					$var = new Variable();
					$var->charger("rsspass");
				?>
				
				<div class="col">Token RSS ( sécurisation du flux ) :</div>
				<div class="col"><input type="text" name="rsspass" value="<?php echo $var->valeur ?>" size="30" /></div> 				

				<?php										
					$var = new Variable();
					$var->charger("rssadmin");
				?>
				
				<div class="col">Flux RSS de la page accueil admin :</div>
				<div class="col"><input type="text" name="rssadmin" value="<?php echo $var->valeur ?>" size="30" /></div> 			
					
				<div class="col">&nbsp;</div>													
				<br /><br />
				
				
				<input style="clear: both; float: left;" type="submit" value="Continuer" />
	
			 </form>

			</div>

	</THELIA_rss>
	

			
		</div>
		

	</div>
	
	<!-- Plan du site -->
	
	<div id="planDuSite"style="overflow:hidden;zoom: 1">
		<div id="contenuPlanDuSite"style="overflow:hidden;zoom: 1">
		R&eacute;alisation <a href="http://www.octolys.fr">Octolys</a> <br />
		Charte graphique <a href="http://www.amplitude-thiers.com">Amplitude </a></div>
		<div id="footerPlanDuSite"style="overflow:hidden;zoom: 1">
		</div>
	</div>
</div>

</body>

</html>
