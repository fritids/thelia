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
		<h1><span>Thélia</span></h1>
	</div>
	
		<!-- Menu -->
		
	<div id="contourMenu"style="overflow:hidden;zoom: 1">
		
		<div id="menuHorizontal">
		<dl>
			<dt><a href="#">ETAPE 2</a></dt>
		</dl>
		
		</div>
		
	</div>
		
		<!-- Contenu -->
		
	<div id="contenu"style="overflow:hidden;zoom: 1">
	
		<div id="colonneDeGauche"style="overflow:hidden;zoom: 1">
	<THELIA_rss type="RSS" url="http://blog.thelia.fr/rss.php" deb="0" nb="1">
		
			<div id="chapeau"style="overflow:hidden;zoom: 1">
			<h2>Connexion &agrave; la base de données</h2>
		
				<br />
				Nous allons installé les informations nécessaires en base de donneés.<br /><br />
				
				Nous allons pour cela vous demander différentes informations. <br /><br />
				
				<?php if(isset($_GET['err']) && $_GET['err']) { ?>
					<span class="erreur">Erreur ! Veuillez vérifier vos informations de connexion</span>
				<?php } ?>
				<form action="choixbase.php" method="post">
				
				<div class="col">Serveur MySQL :</div>
				<div class="col"><input type="text" name="serveur" size="30" /></div> 
				<div class="col">Nom d'utilisateur :</div> 
				<div class="col"><input type="text" name="utilisateur" size="30" /></div>
				<div class="col">Mot de passe :</div> 
				<div class="col"><input type="password" name="motdepasse" size="30" /></div> 
			
				<div class="col">&nbsp;</div>
				
				<br /><br />
				
				<input style="clear: both; float: left;" type="submit" value="Continuer" /></a>
				
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

</body>

</html>
