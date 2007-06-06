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

<?php
	include_once("../classes/Commande.class.php");
	include_once("../classes/Client.class.php");
	include_once("../classes/Venteprod.class.php");
	include_once("../classes/Statut.class.php");
	include_once("../classes/Modules.class.php");
	include_once("../classes/Rubrique.class.php");

	if(!isset($action)) $action="";
	if(!isset($statutch)) $statutch="";
	if(!isset($fichier)) $fichier="";
	
?>

<?php
	$commande = new Commande();
	$commande->charger_ref($ref);
	$modules = new Modules();
	$modules->charger_id($commande->paiement);

?>

<?php
        if($statutch){
                $commande->statut = $statutch;


                if($statutch == 2 && $commande->facture == 0) 
                	$commande->genfact();

                $commande->maj();
        }

    if($colis != ""){
		$commande->colis = $colis;
		$commande->maj();		
	}
		
	if($fichier) copy("$fichier", "../client/commande/" . $ref . ".pdf");

	if($action == "supprfic") unlink("../client/commande/" . $ref . ".pdf");
?>

</head>

<body>

<?php
	$menu="commande";
	include_once("entete.php");
?> 

<div id="contenu_int"> 
   <p class="titre_rubrique">Gestion des commandes / D&eacute;tail de la commande n&deg;  <?php echo($commande->ref); ?></p>
    <p align="right" class="geneva11Reg_3B4B5B"><a href="accueil.php" class="lien04">Accueil </a><img src="gfx/suivant.gif" width="12" height="9" border="0" /><a href="#" class="lien04">Gestion des commandes</a>              
    </p>   
   <table width="710" border="0" cellpadding="5" cellspacing="0">
     <tr>
       <td width="600" height="30" class="titre_cellule_tres_sombre">INFORMATIONS SUR LA COMMANDE </td>
     </tr>
   </table>
   <table width="710" border="0" cellpadding="5" cellspacing="0">
     <tr>
       <td width="370" height="30" class="titre_cellule">D&Eacute;SIGNATION</td>
       <td width="100" class="titre_cellule">PRIX UNITAIRE</td>
       <td width="100" class="titre_cellule">QT&Eacute;</td>
       <td width="100" class="titre_cellule">TOTAL</td>
     </tr>
     
  
  <?php
  	
	$venteprod = new Venteprod();

  	$query = "select * from $venteprod->table where commande='$commande->id'";
  	$resul = mysql_query($query, $venteprod->link);
  	
  	$i=0;
  	
  	while($row = mysql_fetch_object($resul)){
  	
  	$venteprod->charger($row->id);
  	
  	$produit = new Produit();
  	$produitdesc = new Produitdesc();
  	
  	$produit->charger($venteprod->ref);
  	$produitdesc->charger($produit->id);
  	
  	$rubrique = new Rubrique();
  	$rubrique->charger($produit->rubrique);
  	
  	$rubriquedesc = new Rubriquedesc();
  	$rubriquedesc->charger($rubrique->id);
  	
  	if($rubriquedesc->titre !="") $titrerub = $rubriquedesc->titre;
  	else $titrerub = "//";

  		if(!($i%2)) $fond="cellule_sombre";
  		else $fond="cellule_claire";
  		$i++;
  		  	
  ?>     
     
     
     <tr>
       <td height="30" class="<?php echo($fond); ?>"><?php echo($venteprod->titre); ?> - <?php echo($titrerub); ?></td>
       <td class="<?php echo($fond); ?>"><?php echo(round($venteprod->prixu, 2)); ?></td>
       <td class="<?php echo($fond); ?>"><?php echo($venteprod->quantite); ?></td>
       <td class="<?php echo($fond); ?>"><?php echo(round($venteprod->quantite*$venteprod->prixu, 2)); ?></td>
     </tr>
  
  
 <?php } ?> 

 <?php
   	
  		$client = new Client();
  		$client->charger_id($commande->client);
 
 		$venteprod = new Venteprod();
 
   		$query2 = "SELECT sum(prixu*quantite) as total FROM $venteprod->table where commande='$commande->id'"; 
  		$resul2 = mysql_query($query2, $venteprod->link);
  		$total = mysql_result($resul2, 0, "total");

  		$totalremise = $total - $commande->remise;
		
  		$port = $commande->port;
  		if($port<0) $port=0;
  		
  		$statutdesc = new Statutdesc();
  		$statutdesc->charger($commande->statut);
  		
  		$dateaff = substr($commande->date, 8, 2) . "/" . substr($commande->date, 5, 2) . "/" . substr($commande->date, 2, 2);
  		$heureaff =  substr($commande->date, 11); 
 
 ?>
 
   
     <tr>
       <td height="30" colspan="3" class="titre_cellule">TOTAL</td>
       <td class="titre_cellule"><?php echo(round($total, 2)); ?></td>
     </tr>
   </table>
   <br />
   <table width="710" border="0" cellpadding="5" cellspacing="0">
     <tr>
       <td width="600" height="30" class="titre_cellule_tres_sombre">INFORMATIONS SUR LA FACTURE</td>
     </tr>
   </table>
   <table width="710" border="0" cellpadding="5" cellspacing="0">
     <tr>
       <td width="100" height="30" class="titre_cellule">N&deg; DE LA FACT.</td>
       <td width="120" class="titre_cellule">SOCI&Eacute;T&Eacute;</td>
       <td width="130" class="titre_cellule">NOM &amp; PR&Eacute;NOM </td>
       <td width="200" class="titre_cellule">E-MAIL</td>
       <td width="110" class="titre_cellule">DATE &amp; HEURE </td>
     </tr>
     <tr>
       <td height="30" class="cellule_sombre"><?php echo($commande->facture); ?></td>
       <td class="cellule_sombre"><?php echo($client->entreprise); ?></td>
       <td class="cellule_sombre"><a href="client_visualiser.php?ref=<?php echo($client->ref); ?>" class="txt_vert_11"><?php echo($client->prenom); ?> <?php echo($client->nom); ?></a></td>
       <td class="cellule_sombre"><a href="mailto:<?php echo($client->email); ?>" class="txt_vert_11"><?php echo($client->email); ?></a></td>
       <td class="cellule_sombre"><?php echo($dateaff . " " . $heureaff); ?></td>
     </tr>
   </table>
   <br />
   <table width="710" border="0" cellpadding="5" cellspacing="0">
     <tr>
       <td width="600" height="30" class="titre_cellule_tres_sombre">INFORMATIONS SUR LE R&Egrave;GLEMENT </td>
     </tr>
   </table>
   <table width="710" border="0" cellpadding="5" cellspacing="0">
     <tr>
       <td height="30" class="titre_cellule">TYPE DE R&Egrave;GLEMENT </td>
       <td class="cellule_sombre">
			<?php echo($modules->getTitre()); ?>
	  </td>
     </tr>
     <tr>
       <td height="30" class="titre_cellule">R&Eacute;F&Eacute;RENCE DE LA TRANSACTION </td>
       <td class="cellule_claire"><p><?php echo($commande->transaction); ?></p>
       </td>
     </tr>
     <tr>
       <td height="30" class="titre_cellule">TOTAL DE LA COMMANDE AVANT REMISE </td>
       <td class="cellule_sombre"><?php echo(round($total, 2)); ?></td>
     </tr>
     <tr>
       <td height="30" class="titre_cellule">REMISE</td>
       <td class="cellule_claire"><?php echo(round($commande->remise, 2)); ?></td>
     </tr>
     <tr>
       <td width="250" height="30" class="titre_cellule">TOTAL </td>
       <td width="440" class="cellule_sombre"><?php echo(round($totalremise, 2)); ?></td>
     </tr>
     <tr>
       <td height="30" class="titre_cellule">FRAIS DE TRANSPORT </td>
       <td class="cellule_claire"><?php echo(round($port, 2)); ?></td>
     </tr>
     <tr>
       <td height="30" class="titre_cellule">TOTAL</td>
       <td class="cellule_sombre"><?php echo(round($totalremise + $port, 2)); ?></td>
     </tr>
   </table>
   <br />
   <table width="710" border="0" cellpadding="5" cellspacing="0">
     <tr>
       <td width="600" height="30" class="titre_cellule_tres_sombre">STATUT DU R&Egrave;GLEMENT </td>
     </tr>
   </table>
   <table width="100%"  border="0" cellspacing="0" cellpadding="0">
     <tr>
       <td height="30" align="left" valign="middle" class="titre_cellule"><form action="<?php echo($_SERVER['PHP_SELF']); ?>" name="formchange" method="post">
                  <input type="hidden" name="ref" value="<?php echo($ref); ?>">
                  <select name="statutch" class="arial11_bold_626262" onChange="formchange.submit()">
                    <?php
                	$statut = new Statut();
                	$query = "select * from $statut->table";
                	$resul = mysql_query($query);
                	while($row = mysql_fetch_object($resul)){
                		$statutcurdes = new Statutdesc();
                		$statutcurdes->charger($row->id);
                		if($row->id == $statutdesc->statut) $selected="selected"; else $selected="";
                ?>
                    <option value="<?php echo($row->id); ?>" <?php echo($selected); ?>>
                    <?php echo($statutcurdes->titre); ?>
                    </option>
                    <?php
                	
                	}
                	
                ?>
                  </select>
                </form></td>
     </tr>
   </table>
   <br />
<table width="710" border="0" cellpadding="5" cellspacing="0">
  <tr>
    <td width="600" height="30" class="titre_cellule_tres_sombre">N&deg; de colis</td>
  </tr>
</table>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="30" align="left" valign="middle" class="titre_cellule"><form action="<?php echo($_SERVER['PHP_SELF']); ?>" name="formcolis" method="post">
               <input type="hidden" name="ref" value="<?php echo($ref); ?>">
				<input type="text" name="colis" value="<?php echo $commande->colis ?>" /> <input type="submit" value="Valider" />

             </form></td>
  </tr>
</table>
<br />
   <table width="710" border="0" cellpadding="5" cellspacing="0">
     <tr>
       <td width="600" height="30" class="titre_cellule_tres_sombre">FICHIER JOINT </td>
     </tr>
   </table>
   <table width="100%"  border="0" cellspacing="0" cellpadding="0">
     <tr>
       <td height="30" align="left" valign="middle" class="titre_cellule">
       <form action="<?php echo($_SERVER['PHP_SELF']); ?>" method="post" ENCTYPE="multipart/form-data">
                  <input type="hidden" name="ref" value="<?php echo($ref); ?>">
                  <input type="file" name="fichier" />
                  <input type="submit" value="Valider" />
                  
                  <br /><br />
                  <?php if(file_exists("../client/commande/" . $ref . ".pdf")) { ?>
               	   <a href="<?php echo($_SERVER['PHP_SELF']); ?>?action=supprfic&ref=<?php echo($ref); ?>" class="lien04">Supprimer le fichier joint</a>
   				  <?php } ?>
       </form></td>
     </tr>
   </table>
<br />   
   <table width="710" border="0" cellpadding="5" cellspacing="0">
     <tr>
       <td width="70" height="30" class="cellule_sombre2"><span class="geneva11Reg_3B4B5B"><a href="commande.php"" class="txt_vert_11">Retour </a></span><a href="commande.php"><img src="gfx/suivant.gif" width="12" height="9" border="0" /></a></td>
       <td width="220" class="cellule_sombre2"><span class="geneva11Reg_3B4B5B"><a href="../client/pdf/facture.php?ref=<?php echo($commande->ref); ?>" class="txt_vert_11">Visualiser la facture au format PDF </a></span><a href="facture.php?ref=<?php echo($commande->ref); ?>"><img src="gfx/suivant.gif" width="12" height="9" border="0" /></a></td>
       <td width="390" class="cellule_sombre2"><span class="geneva11Reg_3B4B5B"><a href="livraison.php?ref=<?php echo($commande->ref); ?>" class="txt_vert_11">Visualiser le bordereau de livraison au format PDF</a> </span><a href="livraison.php?ref=<?php echo($commande->ref); ?>"><img src="gfx/suivant.gif" width="12" height="9" border="0" /></a></td>
     </tr>
   </table>
   </div>
</body>
</html>
