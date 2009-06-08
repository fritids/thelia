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
		
		<!-- Ent�te -->
		
	<div id="entete"style="overflow:hidden;zoom: 1">
		<h1><span>Thelia</span></h1>
	</div>
	
		<!-- Menu -->
		
	<div id="contourMenu"style="overflow:hidden;zoom: 1">
		
		<div id="menuHorizontal">
		<dl>
			<dt><a href="#">Mise � jour</a></dt>
		</dl>
		
		</div>
		
	</div>
		
		<!-- Contenu -->
		
	<div id="contenu"style="overflow:hidden;zoom: 1">
	
		<div id="colonneDeGauche"style="overflow:hidden;zoom: 1">
		
			<div id="chapeau"style="overflow:hidden;zoom: 1">
		
	
			<h2>installation de Thelia</h2>
		
				<br />
				
				Mise � jour en cours ...<br /><br />
				
				<?php
					include_once("config.php");
					$vcur = $_GET['vcur'];
					$vnew = $version;

					while($vcur != $vnew){
						$vcur ++;
						$patch = substr($vcur, 0, 1) . "." . substr($vcur, 1, 1) . "." . substr($vcur, 2, 1);
						
						if(file_exists("patch/") . $patch && ! $_SESSION['maj']){
							include_once("patch/" . $patch . ".php");
				?>
				
				Mise � jour vers <?php echo $patch; ?> .............................. OK <br />
				<?php
						}
						
					}


					$_SESSION['maj']="1";

				?>
				
				<br />
				
				Mise � jour termin�e.<br /><br />
				
				N'oubliez pas de supprimer le r�pertoire install
				

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
