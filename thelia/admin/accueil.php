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
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>THELIA / BACK OFFICE</title>
<link href="styles.css" rel="stylesheet" type="text/css" />
<link rel="alternate" type="application/rss+xml" title="Syndiquer tout le site" href="../client/rss/cmd.php?rsspass=<?php echo $rsscmd->valeur; ?>" />
</head>

<?php
	include_once("../classes/Client.class.php");	
	include_once("../classes/Produit.class.php");	
	include_once("../classes/Commande.class.php");	

?>

<?php
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
	
	$query = "select * from $commande->table where statut>=2 and statut<>5";
	$resul = mysql_query($query);
	
	$list="";
	while($row = mysql_fetch_object($resul)){
	
		$list .= "'" . $row->id . "'" . ",";
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
	
	$urlsite = new Variable();
	$urlsite->charger("urlsite");	

	$rsspass = new Variable();
	$rsspass->charger("rsspass");	
	
?>
<body>
<div id="wrapper">
<div id="subwrapper">

<?php
	$menu="accueil";
	include_once("entete.php");

?>
<div id="contenu_int"> 
   <p class="titre_rubrique">Accueil v <?php echo substr($version, 0, 1) . "." . substr($version, 1, 1) . "." . substr($version, 2, 1) ?></p>
   <p class="geneva12Reg_3B4B5B">Bienvenue <span class="geneva12Bold_3B4B5B"> <?php echo($_SESSION["util"]->prenom); ?></span>.</p>
   <table class="espacetable" width="30%"  border="0" cellspacing="0" cellpadding="0">
     <tr>
       <td height="30" align="left" valign="middle" class="titre_cellule">COMMANDES </td>
     </tr>
     <tr>
       <td height="30" align="left" valign="middle" class="cellule_sombre">En instance : <span class="geneva11bol_3B4B5B"><?php echo($nbcmdinstance); ?></span> </td>
     </tr>
     <tr>
       <td height="30" align="left" valign="middle" class="cellule_claire">Traitement en cours : <span class="geneva11bol_3B4B5B"><?php echo($nbcmdtraitement); ?></span> </td>
     </tr>
     <tr>
       <td height="30" align="left" valign="middle" class="cellule_sombre">Livr&eacute;e(s) : <span class="geneva11bol_3B4B5B"><?php echo($nbcmdlivree); ?></span> </td>
     </tr>
   </table>
 
   <table class="espacetable" width="30%"  border="0" cellspacing="0" cellpadding="0">
     <tr>
       <td height="30" align="left" valign="middle" class="titre_cellule">INFORMATIONS</td>
     </tr>
     <tr>
       <td height="30" align="left" valign="middle" class="cellule_sombre">Clients : <span class="geneva11bol_3B4B5B"> <?php echo($nbclient); ?></span> </td>
     </tr>
     <tr>
       <td height="30" align="left" valign="middle" class="cellule_claire">Produits : <span class="geneva11bol_3B4B5B"><?php echo($nbproduit); ?></span> </td>
     </tr>
     <tr>
       <td height="30" align="left" valign="middle" class="cellule_sombre">Chiffre d'affaires  : <span class="geneva11bol_3B4B5B"><?php echo(round($ca, 2)); ?> &euro;</span> </td>
     </tr>
   </table>

   
   <table width="30%"  border="0" cellspacing="0" cellpadding="0">
     <tr>
       <td height="30" align="left" valign="middle" class="titre_cellule">SUIVI</td>
     </tr>
     <tr>
       <td height="30" align="left" valign="middle" class="cellule_sombre"><a href="<?php echo($urlsite->valeur); ?>/client/rss/cmd.php?rsspass=<?php echo($rsspass->valeur); ?>" class="txt_vert_11">Fil RSS des commandes</a></td>
     </tr>
     <tr>
       <td height="30" align="left" valign="middle" class="cellule_claire"><a href="<?php echo($urlsite->valeur); ?>" target="_blank" class="txt_vert_11">Site en ligne</a></td>
     </tr>     
     <tr>
       <td height="30" align="left" valign="middle" class="cellule_sombre"><a href="index.php?action=deconnexion" class="txt_vert_11">Se d&eacute;connecter</a></td>
     </tr>
   </table>
   
<br />

   <table width="100%" border="0" cellpadding="5" cellspacing="0">
     <tr>
       <td width="110" height="30" class="titre_cellule">N&deg; DE COMMANDE </td>
       <td width="110" class="titre_cellule"><a href="#" class="lien_titre_cellule">DATE &amp; HEURE</a> </td>
       <td width="95" class="titre_cellule">SOCI&Eacute;T&Eacute;</td>
       <td width="95" class="titre_cellule"><a href="#" class="lien_titre_cellule">NOM &amp; PR&Eacute;NOM</a></td>
       <td width="60" class="titre_cellule">MONTANT</td>
       <td width="60" class="titre_cellule"><a href="#" class="lien_titre_cellule">STATUT</a></td>
       <td width="90" class="titre_cellule">&nbsp;</td>
       <td width="10" class="titre_cellule">&nbsp;</td>
     </tr>

  <?php
  	$i=0;
  	
    	$query = "select * from $commande->table where statut<3 order by date desc";
  	$resul = mysql_query($query, $commande->link);

  	$venteprod = new Venteprod();
  	
  	while($row = mysql_fetch_object($resul)){
  	
  		$client = new Client();
  		$client->charger_id($row->client);
  		
  		$statutdesc = new Statutdesc();
  		$statutdesc->charger($row->statut);
  		
  		$query2 = "SELECT sum(prixu*quantite) as total FROM $venteprod->table where commande='$row->id'"; 
  		$resul2 = mysql_query($query2, $venteprod->link);
  		$total = round(mysql_result($resul2, 0, "total"), 2);

		$port = $row->port;
		$total -= $row->remise;
		$total += $port;
		
  		$jour = substr($row->date, 8, 2);
  		$mois = substr($row->date, 5, 2);
  		$annee = substr($row->date, 2, 2);
  		
  		$heure = substr($row->date, 11, 2);
  		$minute = substr($row->date, 14, 2);
  		$seconde = substr($row->date, 17, 2);
  		  	
  		if(!($i%2)) $fond="cellule_sombre";
  		else $fond="cellule_claire";

  		$i++;
  ?>
  
     <tr>
       <td height="30" class="<?php echo($fond); ?>"><?php echo($row->ref); ?></td>
       <td class="<?php echo($fond); ?>"><?php echo($jour . "/" . $mois . "/" . $annee . " " . $heure . ":" . $minute . ":" . $seconde); ?></td>
       <td class="<?php echo($fond); ?>"><a href="#" class="txt_vert_11"><?php echo($client->entreprise); ?></a></td>
       <td class="<?php echo($fond); ?>"><a href="client_visualiser.php?ref=<?php echo($client->ref); ?>" class="txt_vert_11"><?php echo($client->prenom . " " . $client->nom); ?></a></td>
       <td class="<?php echo($fond); ?>"><?php echo(round($total, 2)); ?></td>
       <td class="<?php echo($fond); ?>"><?php echo($statutdesc->titre); ?></td>
       <td class="<?php echo($fond); ?>"><a href="commande_details.php?ref=<?php echo($row->ref); ?>" class="txt_vert_11">En savoir plus </a> <a href="commande_details.php?ref=<?php echo($row->ref); ?>"><img src="gfx/suivant.gif" width="12" height="9" border="0" /></a></td>
       <td class="<?php echo($fond); ?>_vide"><a href="#" onclick="supprimer('<?php echo($row->id); ?>')"><img src="gfx/supprimer.gif" width="9" height="9" border="0" /></a></td>
     </tr>

<?php
	}
?>

   </table>
   

   
 <?php 
	admin_inclure("accueil"); 
 ?>

<br /> 

</div>
</div>
</div>
</body>
</html>
