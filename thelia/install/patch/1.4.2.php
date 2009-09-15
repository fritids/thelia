<?php
	include_once(realpath(dirname(__FILE__)) . "/../../classes/Cnx.class.php");

	/* ------------------------------------------------------------------ */


	$listefichiersplugins = array(
	"dupliprod/dupliprod_admin.php",
	"expeditor/expeditor_admin.php",
	"expeditor/export.php",
	"multifact/multifact_admin.php",
	"prodprixmult/prodprixmult_admin.php",
	"tinymce/tinymce_admin_title.php",
	"validcli/validcli_admin.php",
	"alert/alertstock_admin.php",
	"avoir/avoir_admin.php",
	"changeref/changeref_admin.php",
	"cmdparmois/cmdparmois_admin.php",
	"commentairecmd/commentairecmd_admin_commandedetails.php",
	"declibre/declibre_admin_produitmodifier.php",
	"declibre/gestdeclibre.php",
	"declibre/prixprod.php",
	"declibre/stockprod.php",
	"declistockvide/declistockvide_admin.php",
	"exportcmdebp/exportcmdebp_admin.php",
	"importartebp/importartebp_admin.php",
	"fianet/fianet_admin.php",
	"importartebp/importartebp_admin.php",
	"newsletter/newsletter_admin.php",
	"newsletter/export_newsletter.php",
	"newsletter/export_mailcli.php",
	"osc2thelia/osc2thelia_admin.php",
	"osc2thelia/import.php",
	"produitdispohorsligne/produitdispohorsligne_admin.php",
	"produithorsligne/produithorsligne_admin.php",
	"ventedpt/ventedpt_admin.php",
	"caracdispinfo/caracdispinfo_admin_caracteristiquemodifier.php",
	"caracdispinfo/caracdispinfo_gestion.php",
	"commentaires/commentaires_admin.php",
	"degressif/degressif_admin_produitmodifier.php",
	"degressif/gestdegressif.php",
	"degressif/prixprod.php",
	"lot/lot_admin_produitmodifier.php",
	"lot/lot_gestion.php",
	"lot/lot_produit.php",
	"lot/stockprod.php",
	"messagecmd/messagecmd_admin_commandedetails.php",
	"nuage/nuage_admin.php",
	"parrainage/compteparrain_admin.php",
	"parrainage/gestparrainage_admin.php",
	"parrainage/parrainage_admin_clientvisualiser.php",
	"parrainage/parrainage_admin.php",
	"parrainage/gestranche.php",
	"prodvirtuel/prodvirtuel_admin_produitmodifier.php",
	"prodvirtuel/telecharger.php",
	"tntrelais/tntrelais_admin.php",
	"tntrelais/tntrelais_admin_commandedetails.php",
	"prodabonnement/prodabonnement_admin.php",
	"prodabonnement/prodabonnement_admin_produitmodifier.php",
	"prodprepaiement/prodprepaiement_admin_clientvisualiser.php",
	"prodprepaiement/prodprepaiement_admin_produitmodifier.php",
	"prodprepaiement/prodprepaiement_admin.php"
	);


	if(! file_exists("../client")){
		echo "Le fichier patchplugins.php doit se situer dans votre r&eacute;pertoire d'administration";
		exit;
	}

	if(! is_writable("../fonctions")){
		echo "Impossible d'&eacute;crire dans le r&eacute;pertoire fonctions. Merci de donner les droits d'&eacute;criture &agrave; Apache et de relancez ce script";
		exit;
	} else {

		if(! file_exists("../fonctions/authplugins.php")){
			$authplugins = "<?php\n\n\tinclude_once(realpath(dirname(__FILE__)) . \"/../classes/Administrateur.class.php\");\n\tinclude_once(realpath(dirname(__FILE__)) . \"/../classes/Navigation.class.php\");\n\n\tif(! session_id())\n\t\tsession_start();\n\n\tif( ! isset(\$_SESSION[\"util\"]->id) ) exit;\n\n\tfunction autorisation(\$nomplugin){\t\n\t\t// A venir\n\t}\n\n?>";
			$fp = fopen("../fonctions/authplugins.php", "w");
			fputs($fp, $authplugins);
			fclose($fp);

			echo "Le fichier fonctions/authplugins.php a &eacute;t&eacute; cr&eacute;e<br />";
		}
	}



	if(! is_writable("index.php")){
		echo "Impossible d'&eacute;crire dans le r&eacute;pertoire admin. Merci de donner les droits d'&eacute;criture &agrave; Apache et de relancez ce script";
		exit;
	} else {
		$rec = file_get_contents("index.php");
		if(strstr($rec, "<?php include_once(\"title.php\");?>")){
			$rec = str_replace("<?php include_once(\"title.php\");?>", "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />\n<title>THELIA / BACK OFFICE</title>\n<link rel=\"SHORTCUT ICON\" href=\"favicon.ico\" />\n<link href=\"styles.css\" rel=\"stylesheet\" type=\"text/css\" />", $rec);

			$rec = str_replace("<?php include_once(\"pied.php\");?>", "<div id=\"footerPage\">&nbsp;</div> <p class=\"footer\"><a href=\"http://www.octolys.fr\" class=\"lien\">D&eacute;velopp&eacute; par Octolys</a> - T&eacute;l: +33 (0)4 73 74 31 19 - <a href=\"http://forum.thelia.fr\" class=\"lien\">Forum Thelia</a> - <a href=\"http://contrib.thelia.fr\" class=\"lien\">Contributions Thelia</a></p>", $rec);		

			$fp = fopen("index.php", "w");
			fputs($fp, $rec);
			fclose($fp);
			echo "Le fichier admin/index.php a &eacute;t&eacute; patch&eacute; avec succes<br />";
		}
	}


		foreach($listefichiersplugins as $fichier){

			if(file_exists("../client/plugins/" . $fichier)){
				$rec = file_get_contents("../client/plugins/" . $fichier);
				if(! strstr($rec, "authplugins.php")){
					if(! is_writable("../client/plugins/$fichier"))
						echo "Impossible de modifier $fichier. Merci de donner les droits d'&eacute;criture &agrave; Apache et de relancez ce script.<br />";

					else {
						echo "Patch $fichier <br />";
						preg_match("/([^\/]*)\//", $fichier, $nomplugin);
						$rec =  preg_replace("/<\?php/", "<?php\ninclude_once(realpath(dirname(__FILE__)) . \"/../../../fonctions/authplugins.php\");\n\nautorisation(\"" . $nomplugin[1] . "\");\n\n", $rec, 1);
						$fp = fopen("../client/plugins/" . $fichier, "w");
						fputs($fp, $rec);
						fclose($fp);
					}
				}
			}

		}

	$cnx = new Cnx();
	
	$query_cnx = "ALTER TABLE  `administrateur` CHANGE  `niveau`  `profil` INT( 11 ) NOT NULL";
	$resul_cnx = mysql_query($query_cnx,$cnx->link);

	$query_cnx = "update administrateur set profil=1";
	$resul_cnx = mysql_query($query_cnx,$cnx->link);
	
	$query_cnx = "CREATE TABLE `autorisation` ( 
	  `id` int(11) NOT NULL auto_increment,
	  `nom` text NOT NULL,
	  PRIMARY KEY  (`id`)
	) AUTO_INCREMENT=1 ;";
	$resul_cnx = mysql_query($query_cnx,$cnx->link);

	$query_cnx = "CREATE TABLE `autorisationdesc` (
	  `id` int(11) NOT NULL auto_increment,
	  `autorisation` int(11) NOT NULL,
	  `titre` text NOT NULL,
	  `chapo` text NOT NULL,
	  `description` text NOT NULL,
	  `postscriptum` text NOT NULL,
	  `lang` int(11) NOT NULL,
	  PRIMARY KEY  (`id`)
	) AUTO_INCREMENT=1 ;";
	$resul_cnx = mysql_query($query_cnx,$cnx->link);

	$query_cnx = "CREATE TABLE `autorisation_administrateur` (
	  `id` int(11) NOT NULL auto_increment,
	  `administrateur` int(11) NOT NULL,
	  `autorisation` int(11) NOT NULL,
	  `lecture` smallint(6) NOT NULL,
	  `ecriture` smallint(6) NOT NULL,
	  PRIMARY KEY  (`id`)
	) AUTO_INCREMENT=1 ;";
	$resul_cnx = mysql_query($query_cnx,$cnx->link);

	$query_cnx = "CREATE TABLE `autorisation_profil` (
	  `id` int(11) NOT NULL auto_increment,
	  `profil` int(11) NOT NULL,
	  `autorisation` int(11) NOT NULL,
	  `lecture` int(11) NOT NULL,
	  `ecriture` int(11) NOT NULL,
	  PRIMARY KEY  (`id`)
	) AUTO_INCREMENT=1 ;";
	$resul_cnx = mysql_query($query_cnx,$cnx->link);

	$query_cnx = "CREATE TABLE `profil` (
	  `id` int(11) NOT NULL auto_increment,
	  `nom` text NOT NULL,
	  PRIMARY KEY  (`id`)
	) AUTO_INCREMENT=1 ;";
	$resul_cnx = mysql_query($query_cnx,$cnx->link);

	$query_cnx = "CREATE TABLE `profildesc` (
	  `id` int(11) NOT NULL auto_increment,
	  `profil` int(11) NOT NULL,
	  `titre` text NOT NULL,
	  `chapo` text NOT NULL,
	  `description` text NOT NULL,
	  `postscriptum` text NOT NULL,
	  `lang` int(11) NOT NULL,
	  PRIMARY KEY  (`id`)
	) AUTO_INCREMENT=1 ;";
	$resul_cnx = mysql_query($query_cnx,$cnx->link);


	$listeinsert = array(		
	"INSERT INTO `profildesc` VALUES(1, 1, 'Super administrateur', '', '', '', 1);",
	"INSERT INTO `profildesc` VALUES(2, 2, 'Gestionnaire des commandes', '', '', '', 1);",
	"INSERT INTO `profildesc` VALUES(3, 3, 'Gestionnaire du catalogue', '', '', '', 1);",
	"INSERT INTO `profil` VALUES(1, 'superadministrateur');",
	"INSERT INTO `profil` VALUES(2, 'gestionnairecommande');",
	"INSERT INTO `profil` VALUES(3, 'gestionnairecatalogue');",
	"INSERT INTO `autorisation_profil` VALUES(1, 2, 1, 1, 1);",
	"INSERT INTO `autorisation_profil` VALUES(2, 2, 2, 1, 1);",
	"INSERT INTO `autorisation_profil` VALUES(3, 2, 8, 1, 1);",
	"INSERT INTO `autorisation_profil` VALUES(4, 3, 3, 1, 1);",
	"INSERT INTO `autorisation_profil` VALUES(5, 3, 4, 1, 1);",
	"INSERT INTO `autorisation_profil` VALUES(6, 3, 5, 1, 1);",
	"INSERT INTO `autorisation_profil` VALUES(7, 3, 8, 1, 1);",
	"INSERT INTO `autorisationdesc` VALUES(1, 1, 'Accès aux clients', '', '', '', 1);",
	"INSERT INTO `autorisationdesc` VALUES(2, 2, 'Accès aux commandes', '', '', '', 1);",
	"INSERT INTO `autorisationdesc` VALUES(3, 3, 'Accès au catalogue', '', '', '', 1);",
	"INSERT INTO `autorisationdesc` VALUES(4, 4, 'Accès aux contenus', '', '', '', 1);",
	"INSERT INTO `autorisationdesc` VALUES(5, 5, 'Accès aux codes promos', '', '', '', 1);",
	"INSERT INTO `autorisationdesc` VALUES(6, 6, 'Accès à la configuration', '', '', '', 1);",
	"INSERT INTO `autorisationdesc` VALUES(7, 7, 'Accès aux modules', '', '', '', 1);",
	"INSERT INTO `autorisationdesc` VALUES(8, 8, 'Accès aux recherches', '', '', '', 1);",
	"INSERT INTO `autorisation` VALUES(1, 'acces_clients');",
	"INSERT INTO `autorisation` VALUES(2, 'acces_commandes');",
	"INSERT INTO `autorisation` VALUES(3, 'acces_catalogue');",
	"INSERT INTO `autorisation` VALUES(4, 'acces_contenu');",
	"INSERT INTO `autorisation` VALUES(5, 'acces_codespromos');",
	"INSERT INTO `autorisation` VALUES(6, 'acces_configuration');",
	"INSERT INTO `autorisation` VALUES(7, 'acces_modules');",
	"INSERT INTO `autorisation` VALUES(8, 'acces_rechercher');",
	");";


	foreach($listeinsert as $insert)
		$resul_cnx = mysql_query($insert,$cnx->link);
	

	$query_cnx = "update variable set valeur='142' where nom='version'";
	$resul_cnx = mysql_query($query_cnx, $cnx->link);

		
?>