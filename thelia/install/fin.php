<?php

	if(! $_POST['utilisateur'] || ! $_POST['motdepasse1'] || ($_POST['motdepasse1'] != $_POST['motdepasse2'])) header("Location: configuration.php?err=1");
	
	include("../classes/Variable.class.php");
	include("../classes/Administrateur.class.php");
	include("../fonctions/divers.php");
	
	$admin = new Administrateur();
	$admin->charger_id(1);
	
	$admin->identifiant=$_POST['utilisateur'];
	$admin->motdepasse=$_POST['motdepasse1'];
	$admin->crypter();
	$admin->maj();
	
	$var = new Variable();
	
		
	$var->charger("emailcontact");
	$var->valeur=$_POST['emailcontact'];
	$var->maj();
	
	$var->charger("nomsite");
	$var->valeur=$_POST['nomsite'];
	$var->maj();
			
	$var->charger("urlsite");
	$var->valeur=$_POST['urlsite'];
	$var->maj();
		
	$var->charger("tva");
	$var->valeur=$_POST['tva'];
	$var->maj();
			
	$var->charger("style_chem");
	$var->valeur=$_POST['style_chem'];
	$var->maj();
			
	$var->charger("rsspass");
	$var->valeur=genpass(40);
	$var->maj();
			
	$var->charger("rssadmin");
	$var->valeur=$_POST['rssadmin'];
	$var->maj();	

	$_SESSION['serveur']="";
	$_SESSION['utilisateur']="";
	$_SESSION['motdepasse']="";
	$_SESSION['choixbase']="";

	unset($_SESSION['serveur']);
	unset($_SESSION['utilisateur']);
	unset($_SESSION['motdepasse']);
	unset($_SESSION['choixbase']);
	
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
			<dt><a href="#">ETAPE 6</a></dt>
		</dl>
		
		</div>
		
	</div>
		
		<!-- Contenu -->
		
	<div id="contenu"style="overflow:hidden;zoom: 1">
	
		<div id="colonneDeGauche"style="overflow:hidden;zoom: 1">
		
			<div id="chapeau"style="overflow:hidden;zoom: 1">
			<h2>Fin de l'installation</h2>
		
			<br />
								
				Thelia est installé avec Succès <br />
				Vous pouvez maintenant vous connecter sur l'interface d'administration
				
				<br /><br />
				
				<span class="erreur">Pensez à supprimer le répertoire install !</span>
				
				<br /><br />
				
				<form action="../admin/index.php" method="post">				
					<input type="submit" value="Continuer" />
				</form>
				
			 </form>

			</div>

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
