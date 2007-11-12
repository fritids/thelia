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
	include_once("../classes/Client.class.php");
	include_once("../classes/Commande.class.php");
	include_once("../classes/Venteprod.class.php");
	include_once("../classes/Statutdesc.class.php");
	include_once("../classes/Produit.class.php");
	
	$menu="";
?>
</head>

<script language="JavaScript" type="text/JavaScript">

function supprimer(id){
	if(confirm("Voulez-vous vraiment supprimer cette commande ?")) location="commande.php?action=supprimer&id=" + id;

}

function supprimer_produit(ref, parent){
	if(confirm("Voulez-vous vraiment supprimer ce produit ?")) location="produit_modifier.php?ref=" + ref + "&action=supprimer&parent=" + parent;

}

function supprimer_rubrique(id, parent){
	if(confirm("Voulez-vous vraiment supprimer cette rubrique ? Vous devez d'abord vider celle-ci")) location="rubrique_modifier.php?id=" + id + "&action=supprimer&parent=" + parent;

}

</script>

<body>

<?php
	include_once("entete.php");
?>


<div id="contenu_int"> 
   <p class="titre_rubrique">R&eacute;sultats de la recherche</p>
    <p align="right" class="geneva11Reg_3B4B5B"><a href="accueil.php" class="lien04">Accueil </a><img src="gfx/suivant.gif" width="12" height="9" border="0" /><a href="#" class="lien04">R&eacute;sultats de la recherche </a>              
    </p>
    <table width="710" border="0" cellpadding="5" cellspacing="0">
      <tr>
        <td width="600" height="30" class="titre_cellule_tres_sombre">LISTE DES RESULTATS </td>
      </tr>
    </table>
    <table width="710" border="0" cellpadding="5" cellspacing="0">
     <tr>
       <td width="151" height="30" class="titre_cellule">N&deg; CLIENT </td>
       <td width="151" class="titre_cellule">NOM</td>
       <td width="130" class="titre_cellule">PRENOM</td>
       <td width="149" class="titre_cellule">E-MAIL</td>
       <td width="79" class="titre_cellule">&nbsp;</td>
      </tr>

    <?php
  	$client = new Client();
 	$search="and nom like '%$motcle%' or prenom like '%$motcle%' or ville like '%$motcle%' or email like '%$motcle%'";
  	
 	$query = "select * from $client->table where 1 $search";
  	$cliresul = mysql_query($query, $client->link);
  	$clilist="";
  	
  	while($row = mysql_fetch_object($cliresul)){
  		$clilist .= "'$row->id', ";
  		
  ?>
       <tr>
       <td height="30" class="<?php echo($fond); ?>"><a href="commande.php?client=<?php echo($row->id); ?>" class="txt_vert_11"><?php echo($row->ref); ?></a></td>
       <td class="<?php echo($fond); ?>"><span class="geneva11bol_3B4B5B"><?php echo($row->nom); ?></span></td>
       <td class="<?php echo($fond); ?>"><span class="geneva11bol_3B4B5B"><?php echo($row->prenom); ?></span></td>
       <td class="<?php echo($fond); ?>"><a href="mailto:<?php echo($row->email); ?>" class="txt_vert_11">
         <?php echo($row->email); ?>
       </a></td>
       <td class="<?php echo($fond); ?>"><a href="client_visualiser.php?ref=<?php echo($row->ref); ?>" class="txt_vert_11">Poursuivre</a> <a href="client_visualiser.php?ref=<?php echo($row->ref); ?>"><img src="gfx/suivant.gif" width="12" height="9" border="0" /></a></td>
      </tr>

 <?php

	}
	
	$clilist = substr($clilist, 0, strlen($clilist)-2);
	
 ?>
   </table> 
    <br />
   <table width="710" border="0" cellpadding="5" cellspacing="0">
     <tr>
       <td width="134" height="30" class="titre_cellule">N&deg; DE COMMANDE</td>
       <td width="134" class="titre_cellule"><a href="<?php echo($_SERVER['PHP_SELF']); ?>?page=<?php echo($page); ?>&classement=date" class="lien_titre_cellule">DATE</a></td>
       <td width="116" class="titre_cellule"><a href="<?php echo($_SERVER['PHP_SELF']); ?>?page=<?php echo($page); ?>&classement=client" class="lien_titre_cellule">NOM</a></td>
       <td width="116" class="titre_cellule">MONTANT</td>
       <td width="79" class="titre_cellule"><a href="<?php echo($_SERVER['PHP_SELF']); ?>?page=<?php echo($page); ?>&classement=statut" class="lien_titre_cellule">STATUT</a></td>
       <td width="71" class="titre_cellule">SUPPRESSION</td>
     </tr>

  
  <?php
  
  	$search="";
  	
  	if($clilist!="") $search .= "where client in ($clilist) or ";
  	else $search .= "where client and  ";
  	$commande = new Commande();
  	
  	
  	
  	$jour = substr($motcle, 0, 2);
  	$mois = substr($motcle, 3,2);
  	$annee = substr($motcle, 6);

	$ladate = "$annee-$mois-$jour";
	$ladate = ereg_replace("\?\?", "%", $ladate);
	
  	$search .= " ref like '%$motcle%' or date like '$ladate'";
  		
   	$query = "select * from $commande->table $search";
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
		if($total<0) $total = 0;
		
  		$jour = substr($row->date, 8, 2);
  		$mois = substr($row->date, 5, 2);
  		$annee = substr($row->date, 0, 4);
  		
  		$heure = substr($row->date, 11, 2);
  		$minute = substr($row->date, 14, 2);
  		$seconde = substr($row->date, 17, 2);
  		
  ?>
       <tr>
       <td height="30" class="<?php echo($fond); ?>"><a href="commande_details.php?ref=<?php echo($row->ref); ?>" class="txt_vert_11"><?php echo($row->ref); ?></a></td>
       <td class="<?php echo($fond); ?>"><span class="geneva11bol_3B4B5B">
         <?php echo($jour . "/" . $mois . "/" . $annee . " " . $heure . ":" . $minute . ":" . $seconde); ?></span>
       </td>
       <td class="<?php echo($fond); ?>"><a href="client_visualiser.php?ref=<?php echo($client->ref); ?>" class="txt_vert_11"><?php echo($client->nom . " " . $client->prenom); ?></a></td>
       <td class="<?php echo($fond); ?>"><span class="geneva11bol_3B4B5B"><?php echo($total); ?></span></td>
       <td class="<?php echo($fond); ?>"> <span class="geneva11bol_3B4B5B"><?php echo($statutdesc->titre); ?></span></td>
       <td class="<?php echo($fond); ?>">
         <div align="center"><a href="#" onClick="supprimer('<?php echo($row->id); ?>')"><img src="gfx/supprimer.gif" width="9" height="9" border="0" /></a></div>
       </td>
      </tr>

 <?php

	}
 ?>
   </table>
   <br />
   <table width="710" border="0" cellpadding="5" cellspacing="0">
     <tr>
       <td width="179" height="30" class="titre_cellule">REF </td>
       <td width="179" class="titre_cellule">TITRE</td>
       <td width="242" class="titre_cellule">PRIX</td>
       <td width="70" class="titre_cellule">SUPPRESSION</td>
     </tr>
  
  <?php
  
  	$search="";

  	$search .= "and ref like '%$motcle%'";
  
  
  	$produit = new Produit();
  	
  		
   	$query = "select * from $produit->table where 1 $search";
  	$resul = mysql_query($query, $produit->link);

  	$produitdesc = new Produitdesc();
 
 	$prodlist="";
 	
   	while($row = mysql_fetch_object($resul)){
		 $prodlist .= "'$row->id', ";	  	
 	 } 	
  	
  	$prodlist = substr($prodlist, 0, strlen($prodlist)-2);

  	$search="";

  	$search .= "and titre like '%$motcle%' or description like '%$motcle%'";
  
  
  	$produit = new Produit();
  	
  		
   	$query = "select * from $produitdesc->table where 1 $search";
  	$resul = mysql_query($query, $produitdesc->link);
	if(mysql_numrows($resul) && $prodlist!="") $prodlist .= ",";
	
  	$produitdesc = new Produitdesc();
 
 	$num = 0;
 
   	while($row = mysql_fetch_object($resul)){
   		$num++;
		 $prodlist .= "'$row->produit', ";	  	
 	 } 	

	if( substr($prodlist, strlen($prodlist)-2, 1) == ",")
 		$prodlist = substr($prodlist, 0, strlen($prodlist)-2);

	if($num == 1) $prodlist .= "'";
	
	if($prodlist == "") $search = "where 0";
	else $search = " where id in ($prodlist)";
	
   	$query = "select * from $produit->table $search";
  	$query = ereg_replace("'')", "')", $query);
	$resul = mysql_query($query, $produitdesc->link);	

  	while($row = mysql_fetch_object($resul)){
  	
  		$produitdesc->charger($row->id);

  ?>
       <tr>
       <td height="30" class="<?php echo($fond); ?>"><a href="produit_modifier.php?ref=<?php echo($row->ref); ?>&rubrique=<?php echo($row->rubrique); ?>" class="txt_vert_11"><?php echo($row->ref); ?></a></td>
       <td class="<?php echo($fond); ?>"><span class="geneva11bol_3B4B5B"><?php echo($produitdesc->titre); ?></span></td>
       <td class="<?php echo($fond); ?>"><span class="geneva11bol_3B4B5B"><?php echo($row->prix); ?></span></td>
       <td class="<?php echo($fond); ?>">
         <div align="center"><a href="javascript:supprimer_produit('<?php echo $row->ref ?>','<?php echo($row->rubrique); ?>')" class="txt_vert_11"><img src="gfx/supprimer.gif" width="9" height="9" border="0" /></a></div>
       </td>
      </tr>

 <?php
	}
 ?>
   </table>

</div> 

</body>
</html>
