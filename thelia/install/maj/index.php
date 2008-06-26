<?php
	session_start();
	$_SESSION['serveur']="";
	$_SESSION['utilisateur']="";
	$_SESSION['motdepasse']="";
	$_SESSION['choixbase']="";
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
			<dt><a href="#">ETAPE 1</a></dt>
		</dl>
		
		</div>
		
	</div>
		
		<!-- Contenu -->
		
	<div id="contenu"style="overflow:hidden;zoom: 1">
	
		<div id="colonneDeGauche"style="overflow:hidden;zoom: 1">
		
			<div id="chapeau"style="overflow:hidden;zoom: 1">
			<h2>installation de Thelia</h2>
		
				<br />
				
				Bienvenue au sein du programme d'installation de Thelia.<br />
				Nous allons vous guider tout au long de ce processus afin d'installer l'application sur votre système.<br /><br />
				
				<form action="bdd.php" method="post">
					<input type="submit" value="Continuer" />
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
