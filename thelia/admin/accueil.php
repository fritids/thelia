<?php
/*************************************************************************************/
/*                                                                                   */
/*      Thelia	                                                            		 */
/*                                                                                   */
/*      Copyright (c) Octolys Development		                                     */
/*		email : thelia@octolys.fr		        	                             	 */
/*      web : http://www.octolys.fr						   							 */
/*                                                                                   */
/*      This program is free software; you can redistribute it and/or modify         */
/*      it under the terms of the GNU General Public License as published by         */
/*      the Free Software Foundation; either version 2 of the License, or            */
/*      (at your option) any later version.                                          */
/*                                                                                   */
/*      This program is distributed in the hope that it will be useful,              */
/*      but WITHOUT ANY WARRANTY; without even the implied warranty of               */
/*      MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the                */
/*      GNU General Public License for more details.                                 */
/*                                                                                   */
/*      You should have received a copy of the GNU General Public License            */
/*      along with this program; if not, write to the Free Software                  */
/*      Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA    */
/*                                                                                   */
/*************************************************************************************/
?>
<?php
		include_once("pre.php");
	    include_once("../classes/Administrateur.class.php");
        include_once("../classes/Variable.class.php");
	    include_once("../classes/Statutdesc.class.php");

        session_start();

		if(!isset($action)) $action="";
		if(!isset($_SESSION["util"])) $_SESSION["util"]=new Administrateur();
		
		if(isset($_POST['identifiant']) && isset($_POST['motdepasse'])){
			$utilisateur = str_replace(" ", "", $_POST['identifiant']);
			$motdepasse = str_replace(" ", "", $_POST['motdepasse']);
		}
		
        if($action == "identifier") {
                $admin = new Administrateur();
                if(! $admin->charger($identifiant, $motdepasse)) {header("Location: index.php");exit;}
                else{
                        $_SESSION["util"] = new Administrateur();
                        $_SESSION["util"] = $admin;

                }
        }

	else if($_SESSION["util"]->id == "") {header("Location: index.php");exit;}
?>
<?php
	$rsscmd = new Variable();
	$rsscmd->charger("rsspass");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include_once("title.php");?>
<link rel="alternate" type="application/rss+xml" title="Syndiquer tout le site" href="../client/rss/cmd.php?rsspass=<?php echo $rsscmd->valeur; ?>" />
</head>

<?php
	include_once("../classes/Client.class.php");	
	include_once("../classes/Produit.class.php");	
	include_once("../classes/Commande.class.php");
	include_once("../lib/magpierss/extlib/Snoopy.class.inc");	
?>

<body>
<div id="wrapper">
<div id="subwrapper">

<?php
	$menu="accueil";
	include_once("entete.php");
?>
<div id="contenu_int">

 <?php if(est_autorise("acces_commandes")){ ?>
	<img src="graph.php" alt="-" />
<?php } ?>

   
 <?php 
	admin_inclure("accueil"); 
	
	$client = new Client();
	$query = "select count(*) as nb from $client->table";
	$resul = mysql_query($query, $client->link);
	$nbclient = mysql_result($resul, 0, "nb");
	
	$produit = new Produit();
	$query = "select count(*) as nb from $produit->table";
	$resul = mysql_query($query, $produit->link);
	$nbproduit = mysql_result($resul, 0, "nb");
	
	$commande = new Commande();
	$query = "select count(*) as nb from $commande->table where statut<'3'";
	$resul = mysql_query($query, $commande->link);
	$nbcmdinstance = mysql_result($resul, 0, "nb");
	
	$query = "select count(*) as nb from $commande->table where statut='3'";
	$resul = mysql_query($query, $commande->link);
	$nbcmdtraitement = mysql_result($resul, 0, "nb");
		
	$query = "select count(*) as nb from $commande->table where statut='4'";
	$resul = mysql_query($query, $commande->link);
	$nbcmdlivree = mysql_result($resul, 0, "nb");
	
	$query = "select count(*) as nbproduitenligne from $produit->table where ligne=1";
	$resul = mysql_query($query);
	$nbproduitenligne = mysql_result($resul,0,'nbproduitenligne');
	
	$nbproduithorsligne = $nbproduit-$nbproduitenligne;
	
	$query = "select * from $commande->table where statut>=2 and statut<>5";
	$resul = mysql_query($query);
	
	$list="";
	$port = 0;
	while($row = mysql_fetch_object($resul)){
	
		$list .= "'" . $row->id . "'" . ",";
		$port += $row->port;
	}	
	
	$list = substr($list, 0, strlen($list)-1);
	$list == "";
	
	if($list == "") $list="''";
	
	$query = "SELECT sum(venteprod.quantite*venteprod.prixu) as ca FROM venteprod where commande in ($list)";
	$resul = mysql_query($query);
	$ca = round(mysql_result($resul, 0, "ca"), 2);
	
	$query = "SELECT sum(port)as ca FROM commande where id in ($list)";
	$resul = mysql_query($query);
	
	$ca += mysql_result($resul, 0, "ca");

	$query = "SELECT sum(remise)as ca FROM commande where id in ($list)";
	$resul = mysql_query($query);
	
	$ca -= mysql_result($resul, 0, "ca");
	
	$casf = $ca - $port;
	
	
	$query = "SELECT count(*) as nbCommande FROM commande where statut>=2 and statut<>5";
	$resul = mysql_query($query);
	
	$nbCommande = mysql_result($resul,0,"nbCommande");
	
    if($nbCommande)
         $panierMoyen = round(($ca/$nbCommande),2);
    else
         $panierMoyen = 0;

	
	
	$query = "select * from commande where datefact like '".date("Y")."-".date("m")."-%%' and statut>=2 and statut<>5";
	$resul = mysql_query($query);
	
	$list = "";
	while($row = mysql_fetch_object($resul)){
		$list .= "'" . $row->id . "'" . ",";
	}
	
	$list = substr($list, 0, strlen($list)-1);
	$list == "";
	
	if($list == "") $list="''";
	$camois = 0;
	$query = "SELECT sum(venteprod.quantite*venteprod.prixu) as camois FROM venteprod where commande in ($list)";
	$resul = mysql_query($query);
	$camois = round(mysql_result($resul, 0, "camois"), 2);
	
	$query = "SELECT sum(port)as port FROM commande where id in ($list)";
	$resul = mysql_query($query);
	
	$camois += mysql_result($resul, 0, "port");

	$query = "SELECT sum(remise)as remise FROM commande where id in ($list)";
	$resul = mysql_query($query);
	
	$camois -= mysql_result($resul, 0, "remise");
	
	
	
	$query = "select count(*) as nbcmdannulee from $commande->table where statut IN (5)";
	$resul = mysql_query($query);
	$nbcmdannulee = mysql_result($resul,0,"nbcmdannulee");
	
	$version = new Variable();
	$version->charger("version");
	
	$rubrique = new Rubrique();
	$query = "select count(id) as nbRubrique from $rubrique->table";
	$resul = mysql_query($query);
	$nbrubrique = mysql_result($resul,0,"nbRubrique");
	
	$snoopy = new Snoopy();
	
	
	if($snoopy->fetch("http://thelia.fr/version.php")){
		$versiondispo = $snoopy->results;
	}
	else{
		$versiondispo = "";
	}
	
	
 ?>
<div id="bloc_informations">
	<ul>
	<li class="entete">INFORMATIONS SITE</li>
	<li class="lignetop" style="width:222px; background-color:#9eb0be;border-bottom: 1px dotted #FFF;">Clients</li>
	<li class="lignetop" style="width:72px;"><?php echo($nbclient); ?></li>
	<li class="fonce" style="width:222px; background-color:#9eb0be;border-bottom: 1px dotted #FFF;">Rubriques</li>
	<li class="fonce" style="width:72px;"><?php echo($nbrubrique); ?></li>
	<li class="claire" style="width:222px; background-color:#9eb0be;border-bottom: 1px dotted #FFF;">Produits</li>
	<li class="claire" style="width:72px;"><?php echo($nbproduit); ?></li>
	<li class="fonce" style="width:222px; background-color:#9eb0be;border-bottom: 1px dotted #FFF;">Produits en ligne </li>
	<li class="fonce" style="width:72px;"><?php echo($nbproduitenligne); ?></li>
	<li class="claire" style="width:222px; background-color:#9eb0be;border-bottom: 1px dotted #FFF;">Produits hors ligne</li>
	<li class="claire" style="width:72px;"><?php echo($nbproduithorsligne); ?></li>
	 <?php if(est_autorise("acces_commandes")){ ?>
	<li class="fonce" style="width:222px; background-color:#9eb0be;border-bottom: 1px dotted #FFF;">Commandes</li>
	<li class="fonce" style="width:72px;"><?php echo($nbCommande); ?></li>
	<li class="claire" style="width:222px; background-color:#9eb0be;border-bottom: 1px dotted #FFF;">Commandes en instance </li>
	<li class="claire" style="width:72px;"><?php echo($nbcmdinstance); ?></li>
	<li class="fonce" style="width:222px; background-color:#9eb0be;border-bottom: 1px dotted #FFF;">Commandes en traitement</li>
	<li class="fonce" style="width:72px;"><?php echo($nbcmdtraitement); ?></li>
	<li class="lignebottom" style="width:222px; background-color:#9eb0be;">Commandes annulées </li>
	<li class="lignebottom" style="width:72px;"><?php echo($nbcmdannulee); ?></li>
	<?php } ?>	
	</ul>
	<ul>
	 <?php if(est_autorise("acces_commandes")){ ?>
	<li class="entete">STATISTIQUES DE VENTE</li>
	<li class="lignetop" style="width:222px; background-color:#9eb0be;border-bottom: 1px dotted #FFF;">Chiffre d'affaires TTC </li>
	<li class="lignetop" style="width:72px;"><?php echo(round($ca, 2)); ?> &euro;</li>
	<li class="fonce" style="width:222px; background-color:#9eb0be;border-bottom: 1px dotted #FFF;">Chiffre d'affaires hors frais de port</li>
	<li class="fonce" style="width:72px;"><?php echo(round($casf, 2)); ?> &euro;</li>
	<li class="claire" style="width:222px; background-color:#9eb0be;border-bottom: 1px dotted #FFF;">Chiffre d'affaires du mois en cours</li>
	<li class="claire" style="width:72px;"><?php echo(round($camois, 2)); ?> &euro;</li>
	<li class="lignebottomfonce" style="width:222px; background-color:#9eb0be;">Panier moyen </li>
	<li class="lignebottomfonce" style="width:72px;"><?php echo $panierMoyen; ?> &euro;</li>
	</ul>
	<?php } ?>
	<ul>
	<li class="entete" >INFOS THELIA</li>
	<li class="lignetop" style="width:222px; background-color:#9eb0be;border-bottom: 1px dotted #FFF;">Version en cours</li>
	<li class="lignetop" style="width:72px;">V<?php
											$vers = "";
											for($i=0;$i<strlen($version->valeur);$i++){
												$vers .= substr($version->valeur,$i,1).".";
											}
											$vers = substr($vers,0,strlen($vers)-1);
											echo $vers;
										?></li>
	<?php if($versiondispo != "") {?>
	<li class="fonce" style="width:222px; background-color:#9eb0be;border-bottom: 1px dotted #FFF;">Dernière version disponible</li>
	<li class="fonce" style="width:72px;"><a href="http://www.thelia.fr/fichiers/thelia_<?php echo $versiondispo ?>.zip">V<?php echo $versiondispo; ?></a></li>
	<?php } ?>
	<li class="lignebottom" style="width:222px; background-color:#9eb0be;">Actualités</li>
	<li class="lignebottom" style="width:72px;"><a href="http://blog.thelia.fr" target="_blank">cliquer ici</a></li>
	</ul>
</div>
</div>
<?php include_once("pied.php");?>

</div>
</body>
</html>
