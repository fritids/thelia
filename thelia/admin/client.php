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
<?php
	if(!isset($action)) $action="";
	if(!isset($page)) $page=0;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>THELIA / BACK OFFICE</title>
<link href="styles.css" rel="stylesheet" type="text/css" />

<script type="text/javascript">
	function confirmSupp(ref){
		if(confirm("Voulez-vous vraiment supprimer ce client ?")) location="<?php echo($_SERVER['PHP_SELF']); ?>?action=supprimer&ref=" + ref;

	}
</script>

</head>

<?php
	include_once("../classes/Client.class.php");
	
?>

<?php
	if($action == "supprimer"){
	
		$tempcli = new Client();
		$tempcli->charger_ref($ref);
		
		$tempcli->delete();
	}
	
?>

<?php
	$client = new Client();
  	
  	
	if($page=="") $page=1;
  		 
	$query = "select * from $client->table";
  	$resul = mysql_query($query, $client->link);
  	$num = mysql_num_rows($resul);
  	
  	$nbpage = ceil($num/20);
  	
  	$debut = ($page-1) * 20;
  	
  	if($page>1) $pageprec=$page-1;
  	else $pageprec=$page;

  	if($page<$nbpage) $pagesuiv=$page+1;
  	else $pagesuiv=$page;
  	 
  	$ordclassement = "order by ref desc";

?>

<body>

<?php
	$menu="client";
	include_once("entete.php");
?>

<div id="contenu_int"> 
   <p class="titre_rubrique">Gestion des clients </p>
      <p align="right" class="geneva11Reg_3B4B5B"><a href="accueil.php" class="lien04">Accueil </a><img src="gfx/suivant.gif" width="12" height="9" border="0" /><a href="#" class="lien04">Gestion des clients</a>              
    </p>

      <table width="710" border="0" cellpadding="5" cellspacing="0">
     <tr>
       <td width="600" height="30" class="titre_cellule_tres_sombre">LISTE DES CLIENTS </td>
     </tr>
   </table>
   <table width="710" border="0" cellpadding="0" cellspacing="0" >
  <tr>
    <td height="30" class="titre_cellule">N&deg; DU CLIENT</td>
    <td height="30" class="titre_cellule">SOCI&Eacute;T&Eacute;</td>
    <td height="30" class="titre_cellule">PR&Eacute;NOM &amp; NOM</td>
    <td height="30" class="titre_cellule">E-MAIL</td>
    <td height="30" class="titre_cellule">&nbsp;</td>
    <td height="30" class="titre_cellule">&nbsp;</td>
    
  </tr>
  
  <?php
  	$i=0;
  	
  	$client = new Client();
  	
 	$query = "select * from $client->table $ordclassement limit $debut,20";
  	$resul = mysql_query($query, $client->link);
  	
  	while($row = mysql_fetch_object($resul)){
  		if(!($i%2)) $fond="cellule_sombre";
  		else $fond="cellule_claire";
  		$i++;
  ?>
    
  <tr class="<?php echo($fond); ?>">
    <td height="30"><?php echo($row->ref); ?></td>
    <td height="30"><?php echo($row->entreprise); ?></td>
    <td height="30"><?php echo($row->prenom); ?> <?php echo($row->nom); ?></td>
    <td height="30"><a href="mailto:<?php echo($row->email); ?>" class="txt_vert_11"><?php echo($row->email); ?></a></td>
    <td height="30"><a href="client_visualiser.php?ref=<?php echo($row->ref); ?>" class="txt_vert_11">Poursuivre</a> <a href="client_visualiser.php?ref=<?php echo($row->ref); ?>"><img src="gfx/suivant.gif" width="12" height="9" border="0" /></a></td>
    <td height="30"><a href="#" onclick="confirmSupp('<?php echo($row->ref); ?>')"><img src="gfx/supprimer.gif" width="9" height="9" border="0" /></a></td>
  </tr>
 
<?php } ?>  
   
  
  
</table>

   <p align="center" class="geneva11Reg_3B4B5B"><a href="<?php echo($_SERVER['PHP_SELF']); ?>?page=<?php echo($pageprec); ?>" class="txt_vert_11">Page pr&eacute;c&eacute;dente</a> | 
   
     <?php for($i=0; $i<$nbpage; $i++){ ?>
    	 <?php if($page != $i+1){ ?>
  	  		 <a href="<?php echo($_SERVER['PHP_SELF']); ?>?page=<?php echo($i+1); ?>" class="txt_vert_11"><?php echo($i+1); ?></a> |
    	 <?php } else {?>
    		 <?php echo($i+1); ?>
    		 <span class="txt_vert_11">|</span>
   		  <?php } ?>
     <?php } ?>
     
   
    <a href="<?php echo($_SERVER['PHP_SELF']); ?>?page=<?php echo($pagesuiv); ?>" class="txt_vert_11">Page suivante</a></p>
</div> 

</body>
</html>
