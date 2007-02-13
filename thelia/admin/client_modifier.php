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
	include("auth.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>THELIA / BACK OFFICE</title>
<link href="styles.css" rel="stylesheet" type="text/css" />
<?php
	include("../classes/Rubrique.class.php");
	include("../fonctions/divers.php");
	include("../classes/Client.class.php");
	include("../classes/Pays.class.php");
	include("../classes/Paysdesc.class.php");
	
	
?>



<?php
	$client = new Client();
	
	$client->charger_ref($ref);
	
	if($client->raison == "1") $raison1 = "checked=\"checked\""; else $raison1="";
	if($client->raison == "2") $raison2 = "checked=\"checked\""; else $raison2="";
	if($client->raison == "3") $raison3 = "checked=\"checked\""; else $raison3="";
	
	


?>
</head>

<body>

<?php
	$menu="client";
	include("entete.php");
?>

<div id="contenu_int"> 
   <p class="titre_rubrique">Gestion des clients / Modification du compte client n&deg;  <?php echo($ref); ?></p>
<p align="right" class="geneva11Reg_3B4B5B"><a href="accueil.php" class="lien04">Accueil </a><img src="gfx/suivant.gif" width="12" height="9" border="0" /><a href="client.php" class="lien04">Gestion des clients</a> <img src="gfx/suivant.gif" width="12" height="9" border="0" /><a href="#" class="lien04">Modifier        
    </p>     
   <table width="710" border="0" cellpadding="5" cellspacing="0">
     <tr>
       <td width="600" height="30" class="titre_cellule_tres_sombre">INFORMATIONS SUR LE CLIENT</td>
     </tr>
   </table>
   
 <form action="client_visualiser.php" id="formulaire" method="post">

<input type="hidden" name="action" value="modifier" />
<input type="hidden" name="ref" value="<?php echo($ref); ?>" />
    
   <table width="710" border="0" cellpadding="5" cellspacing="0">
     <tr>
       <td height="30" class="titre_cellule">SOCI&Eacute;T&Eacute;</td>
       <td class="cellule_sombre">
         <input name="entreprise" type="text" class="form" value="<?php echo($client->entreprise); ?>" size="40" />
      </td>
     </tr>
     <tr>
       <td height="30" class="titre_cellule">CIVILIT&Eacute;</td>
       <td class="cellule_claire">
         <input name="raison" type="radio" class="form" value="1" <?php echo($raison1); ?>/>
Madame
<input name="raison" type="radio" class="form" value="2" <?php echo($raison2); ?> />
Mademoiselle
<input name="raison" type="radio" class="form" value="3" <?php echo($raison3); ?> />
Monsieur</td>
     </tr>
     <tr>
       <td height="30" class="titre_cellule">NOM </td>
       <td class="cellule_sombre">
       <input name="nom" type="text" class="form" value="<?php echo($client->nom); ?>" size="40" />       </td>
     </tr>
     <tr>
       <td height="30" class="titre_cellule">PR&Eacute;NOM</td>
       <td class="cellule_claire">
         <input name="prenom" type="text" class="form" value="<?php echo($client->prenom); ?>" size="40" />
       </td>
     </tr>
     <tr>
       <td width="250" height="30" class="titre_cellule">ADRESSE</td>
       <td width="440" class="cellule_sombre">
         <input name="adresse1" type="text" class="form" value="<?php echo($client->adresse1); ?>" size="40" />
      </td>
     </tr>
     <tr>
       <td width="250" height="30" class="titre_cellule">ADRESSE SUITE</td>
       <td width="440" class="cellule_sombre">
         <input name="adresse2" type="text" class="form" value="<?php echo($client->adresse2); ?>" size="40" />
      </td>
     </tr>
     <tr>
       <td width="250" height="30" class="titre_cellule">ADRESSE SUITE 2</td>
       <td width="440" class="cellule_sombre">
         <input name="adresse3" type="text" class="form" value="<?php echo($client->adresse3); ?>" size="40" />
      </td>
     </tr>     
     <tr>
       <td height="30" class="titre_cellule">CODE POSTAL </td>
       <td class="cellule_claire">
         <input name="cpostal" type="text" class="form" value="<?php echo($client->cpostal); ?>" size="40" />
      </td>
     </tr>
     <tr>
       <td height="30" class="titre_cellule">VILLE</td>
       <td class="cellule_sombre">
         <input name="ville" type="text" class="form" value="<?php echo($client->ville); ?>" size="40" />
       </td>
     </tr>
     <tr>
       <td height="30" class="titre_cellule">PAYS</td>
       <td class="cellule_claire">
    <select name="pays">
     <?php
      	$pays = new Pays();
      	$query ="select * from $pays->table";
      	
      	$resul = mysql_query($query, $pays->link);
      	while($row = mysql_fetch_object($resul)){
			$paysdesc = new Paysdesc();
			$paysdesc->charger($row->id);
			if($row->id == $client->pays) $selected="selected=\"selected\""; else $selected="";
      
      ?>
      
      <option value="<?php echo($row->id); ?>" <?php echo($selected); ?>><?php echo($paysdesc->titre); ?></option>
      
      <?php } ?>
      
      </select>

       </td>
     </tr>
     <tr>
       <td height="30" class="titre_cellule">T&Eacute;L&Eacute;PHONE FIXE</td>
       <td class="cellule_sombre">
         <input name="telfixe" type="text" class="form" value="<?php echo($client->telfixe); ?>" size="40" />
      </td>
     </tr>
     <tr>
       <td height="30" class="titre_cellule">T&Eacute;L&Eacute;PHONE PORTABLE </td>
       <td class="cellule_claire">
         <input name="telport" type="text" class="form" value="<?php echo($client->telport); ?>" size="40" />
      </td>
     </tr>
     <tr>
       <td height="30" class="titre_cellule">E-MAIL</td>
       <td class="cellule_sombre">
         <input name="email" type="text" class="form" value="<?php echo($client->email); ?>" size="40" />
      </td>
     </tr>
     <tr>
       <td height="30" class="titre_cellule">Remise </td>
       <td class="cellule_claire">
         <input name="pourcentage" type="text" class="form" value="<?php echo($client->pourcentage); ?>" size="40" />
      </td>
     </tr>     
     <tr>
       <td height="30" class="titre_cellule">Revendeur </td>
       <td class="cellule_claire">
         <input type="checkbox" name="type" <?php if($client->type) { ?> checked="checked" <?php } ?> class="form"/> 
    </td>
     </tr> 
   </table>
</form>
   
   <br />
   <table width="710" border="0" cellpadding="5" cellspacing="0">
     <tr>
       <td height="30" class="cellule_sombre2"><span class="sous_titre_rubrique"><span class="geneva11Reg_3B4B5B"><a href="#" class="txt_vert_11" onClick="document.getElementById('formulaire').submit()">Valider les modifications </a></span> <a href="gestion_des_clients02.htm"><img src="gfx/suivant.gif" width="12" height="9" border="0" /></a></span></td>
     </tr>
   </table>
   </div>
</body>
</html>
