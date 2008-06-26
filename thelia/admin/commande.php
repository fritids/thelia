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
	include_once("auth.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>THELIA / BACK OFFICE</title>
<link href="styles.css" rel="stylesheet" type="text/css" />
</head>
<?php

	include_once("../classes/Commande.class.php");
	include_once("../classes/Client.class.php");
	include_once("../classes/Venteprod.class.php");
	include_once("../classes/Ventedeclidisp.class.php");
	include_once("../classes/Stock.class.php");
	include_once("../classes/Statutdesc.class.php");
	include_once("../fonctions/divers.php");

	if(!isset($action)) $action="";
	if(!isset($client)) $client="";
	if(!isset($page)) $page=0;
	if(!isset($classement)) $classement="";

?>

<?php
	if($action == "supprimer"){
	
		$tempcmd = new Commande();
		$tempcmd->charger($id);
		
		$tempcmd->statut = "5";
		$tempcmd->maj();


   		$venteprod = new Venteprod();
   		$query = "select * from $venteprod->table where commande='" . $id . "'";
   		$resul = mysql_query($query, $venteprod->link);

		while($row = mysql_fetch_object($resul)){
			// incr�mentation du stock g�n�ral
    		$produit = new Produit();   
			$produit->charger($row->ref);
			$produit->stock = $produit->stock + $row->quantite;
    		$produit->maj();

			$vdec = new Ventedeclidisp();
			
			$query2 = "select * from $vdec->table where venteprod='" . $row->id . "'";
			$resul2 = mysql_query($query2, $vdec->link);
			
			
			while($row2 = mysql_fetch_object($resul2)){
				$stock = new Stock();
				if($stock->charger($row2->declidisp, $produit->id)){
					$stock->valeur = $stock->valeur + $row->quantite;
					$stock->maj();					
				}
				
				
			}
			
		}

		modules_fonction("statut", $tempcmd);
		
	}
	
?>

<?php
	if(isset($voir_annule))
  		$search="and statut=5";
  	else
		if(isset($voir_envoye))
  			$search="and statut=4";		
	else
		 $search="and statut not in (5,4)";
			
  	if($client != "") $search .= " and client=\"$client\"";
  	$commande = new Commande();
  	if($page=="") $page=1;
  		 
   	$query = "select * from $commande->table where 1 $search";
  	$resul = mysql_query($query, $commande->link);
  	$num = mysql_num_rows($resul);
  	
  	$nbpage = ceil($num/30);
  	
  	$debut = ($page-1) * 30;
  	
  	if($page>1) $pageprec=$page-1;
  	else $pageprec=$page;

  	if($page<$nbpage) $pagesuiv=$page+1;
  	else $pagesuiv=$page;
  	 
  	if($classement == "client") $ordclassement = "order by client";  	
  	else if($classement == "statut") $ordclassement = "order by statut";
  	else $ordclassement = "order by date desc";

?>

<script type="text/JavaScript">

function supprimer(id){
	if(confirm("Voulez-vous vraiment annuler cette commande ?")) location="commande.php?action=supprimer&id=" + id;

}

</script>

<body>

<?php
	$menu="commande";
	include_once("entete.php");
?>


<div id="contenu_int"> 
   <p class="titre_rubrique">Gestion des commandes</p>
    <p align="right" class="geneva11Reg_3B4B5B"><a href="accueil.php" class="lien04">Accueil </a><img src="gfx/suivant.gif" width="12" height="9" border="0" /><a href="#" class="lien04">Gestion des commandes</a>              
    </p>
   <table width="710" border="0" cellpadding="5" cellspacing="0">
     <tr>
       <td width="450" height="30" class="titre_cellule_tres_sombre"><a href="commande.php" class="lien07">LISTE DES COMMANDES</a></td>
       <td width="150" height="30" class="titre_cellule_tres_sombre"><a href="commande.php?voir_annule=1" class="lien07">Commandes annul�es</a></td>
       <td width="150" height="30" class="titre_cellule_tres_sombre"><a href="commande.php?voir_envoye=1" class="lien07">Commandes envoy�es</a></td>
     </tr>
   </table>
   <table width="710" border="0" cellpadding="5" cellspacing="0">
     <tr>
       <td width="110" height="30" class="titre_cellule">N&deg; DE COMMANDE </td>
       <td width="110" class="titre_cellule"><a href="<?php echo($_SERVER['PHP_SELF']); ?>?page=<?php echo($page); ?>&classement=date" class="lien_titre_cellule">DATE &amp; HEURE</a> </td>
       <td width="95" class="titre_cellule">SOCI&Eacute;T&Eacute;</td>
       <td width="95" class="titre_cellule"><a href="<?php echo($_SERVER['PHP_SELF']); ?>?page=<?php echo($page); ?>&classement=client" class="lien_titre_cellule">NOM &amp; PR&Eacute;NOM</a></td>
       <td width="60" class="titre_cellule">MONTANT</td>
       <td width="60" class="titre_cellule"><a href="<?php echo($_SERVER['PHP_SELF']); ?>?page=<?php echo($page); ?>&classement=statut" class="lien_titre_cellule">STATUT</a></td>
       <td width="90" class="titre_cellule">&nbsp;</td>
       <td width="10" class="titre_cellule">&nbsp;</td>
     </tr>

  <?php
  	$i=0;
  	
    	$query = "select * from $commande->table where 1 $search $ordclassement limit $debut,30";
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
       <td class="<?php echo($fond); ?>_vide"><a href="#" onClick="supprimer('<?php echo($row->id); ?>')"><img src="gfx/supprimer.gif" width="9" height="9" border="0" /></a></td>
     </tr>

<?php
	}
?>
<?php
	$lien_voir = "";
	
	if($voir_annule != "")
		$lien_voir .= "&voir_annule=1";
	if($voir_envoye != "")
		$lien_voir .= "&voir_envoye=1";
?>
   </table>
   <p align="center" class="geneva11Reg_3B4B5B"><a href="<?php echo($_SERVER['PHP_SELF']); ?>?page=<?php echo($pageprec); ?><?php echo $lien_voir; ?>" class="txt_vert_11">Page pr&eacute;c&eacute;dente</a> |
     <?php for($i=0; $i<$nbpage; $i++){ ?>
    	 <?php if($page != $i+1){ ?>
  	  		 <a href="<?php echo($_SERVER['PHP_SELF']); ?>?page=<?php echo($i+1); ?>&classement=<?php echo($classement); ?><?php echo $lien_voir; ?>" class="txt_vert_11"><?php echo($i+1); ?></a> |
    	 <?php } else {?>
    		 <?php echo($i+1); ?>
    		 <span class="txt_vert_11">|</span>
   		  <?php } ?>
     <?php } ?>
     

                    
     <a href="<?php echo($_SERVER['PHP_SELF']); ?>?page=<?php echo($pagesuiv); ?><?php echo $lien_voir; ?>" class="txt_vert_11">Page suivante</a></p>
</div> 

</body>
</html>
