<?php
	session_start();
				
	if(isset($_POST['serveur']) && isset($_POST['utilisateur'])  && isset($_POST['motdepasse'])){
		if(! $cnx = @mysql_connect($_POST['serveur'], $_POST['utilisateur'], $_POST['motdepasse'])){
			header("Location: bdd.php?err=1");	
			exit;
		} else {

			if(file_exists("../config/Cnx.class.php.orig")) 
				$fic = file_get_contents("../config/Cnx.class.php.orig");
			
			if(! file_exists("../config/Cnx.class.php")){

				$fic = str_replace("votre_serveur", $_POST['serveur'], $fic);
				$fic = str_replace("votre_login_mysql", $_POST['utilisateur'], $fic);
				$fic = str_replace("votre_motdepasse_mysql",  $_POST['motdepasse'], $fic);

				$fp = fopen("../config/Cnx.class.php.orig", "w");
				fputs($fp, $fic);
				fclose($fp);

				rename("../config/Cnx.class.php.orig", "../config/Cnx.class.php");

				$droitscreation=false;
				
			}
			
		}

	}		
	
	else {
		header("Location: bdd.php?err=1");	
		exit;
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
			<h2>Choix de la base</h2>
		
				<br />
				
				<form action="configuration.php" method="post">
								
				Veuillez choisir votre base de données. <br /><br />
				
				
				<?php 
					$i=0;
					
					$listbdd = @mysql_list_dbs();
					if($listbdd)
					 while($row = mysql_fetch_object($listbdd)){
				?>
	
					<input type="radio" name="choixbase" value="<?php echo $row->Database; ?>"  <?php if($i == 0) echo "checked=\"checked\""; ?>  /><?php echo $row->Database; ?> <br />
				
				<?php
				
					$i++;
						} else {
				?>
				
					<input type="text" name="choixbase" /> 
					
					<?php 
						if(isset($_GET['err']) && $_GET['err']) {					
					?>
					
						<span class="erreur">(vous n'avez pas accés à cette base)</span>
						
					<?php
						}
					?>
					
					<br />
				
				<?php 
						}
										try{
				$connexion = mysql_connect($_POST['serveur'], $_POST['utilisateur'],$_POST['motdepasse']);
				$db = mysql_select_db("information_schema");
				$req = mysql_query("SELECT COUNT( * ) FROM  `USER_PRIVILEGES` 
					WHERE PRIVILEGE_TYPE =  'CREATE'
					AND GRANTEE LIKE  '%".$_POST['utilisateur']."%'
					AND IS_GRANTABLE =  'YES';");
				$data = mysql_fetch_array($req);  
				mysql_free_result($req); 
				mysql_close($connexion);
				if($data[0]>0)
					$droitscreation=true;
				}catch(Exception $e) {}
						
				?>
				 
				<br />
				<?php if($droitscreation==true) {?>
				Vous pouvez aussi choisir de cr&eacute;er une base : <input type="text" name="creerbase" />
				<br />
				<?php }?>
				<input type="submit" value="Continuer" />
				
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
