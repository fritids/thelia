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
<?php if(! est_autorise("acces_clients")) exit; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include_once("title.php"); ?>
<?php
	include_once("../classes/Rubrique.class.php");
	include_once("../fonctions/divers.php");
	include_once("../classes/Client.class.php");
	include_once("../classes/Pays.class.php");
	include_once("../classes/Paysdesc.class.php");
	
	
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

<div id="wrapper">
<div id="subwrapper">

<?php
	$menu="client";
	include_once("entete.php");
?>

<div id="contenu_int"> 
<p><a href="accueil.php" class="lien04">Accueil </a><img src="gfx/suivant.gif" width="12" height="9" border="0" /><a href="client.php" class="lien04">Gestion des clients</a> <img src="gfx/suivant.gif" width="12" height="9" border="0" /><a href="#" class="lien04">Modifier</a></p>

<!-- Début de la colonne de gauche -->  
<div id="bloc_description">
<form action="client_visualiser.php" id="formulaire" method="post">
<input type="hidden" name="action" value="modifier" />
<input type="hidden" name="ref" value="<?php echo($ref); ?>" />
<!-- bloc de modification client --> 
	<div class="entete_liste_client">
			<div class="titre">INFORMATIONS SUR LE CLIENT</div>
			<div class="fonction_valider"><a href="#" onclick="document.getElementById('formulaire').submit()">VALIDER LES MODIFICATIONS</a></div>
			
</div>
     
<table width="100%" cellpadding="5" cellspacing="0" style="clear: both;">
    <tr class="claire">
        	<th class="designation" width="290">Soci&eacute;t&eacute;</td>
       		<th><input name="entreprise" type="text" class="form" value="<?php echo($client->entreprise); ?>" size="40" /></td>
     </tr>
      <tr class="fonce">
       		<td class="designation">Siret</td>
       		<td><input name="siret" type="text" class="form" value="<?php echo($client->siret); ?>" size="40" /></td>
     </tr>
      <tr class="claire">
       <td class="designation">N° Intracommunautaire</td>
       <td><input name="intracom" type="text" class="form" value="<?php echo($client->intracom); ?>" size="40" /></td>
     </tr>
     <tr class="fonce">
       <td class="designation">Civilit&eacute;</td>
       <td>
         <input name="raison" type="radio" class="form" value="1" <?php echo($raison1); ?>/>
Madame
<input name="raison" type="radio" class="form" value="2" <?php echo($raison2); ?> />
Mademoiselle
<input name="raison" type="radio" class="form" value="3" <?php echo($raison3); ?> />
Monsieur</td>
     </tr>
     <tr class="claire">
       <td class="designation">Nom</td>
       <td><input name="nom" type="text" class="form" value="<?php echo($client->nom); ?>" size="40" /></td>
     </tr>
     <tr class="fonce">
       <td class="designation">Pr&eacute;nom</td>
       <td class="cellule_claire">
         <input name="prenom" type="text" class="form" value="<?php echo($client->prenom); ?>" size="40" /></td>
     </tr>
     <tr class="claire">
       <td class="designation">Adresse</td>
       <td><input name="adresse1" type="text" class="form" value="<?php echo($client->adresse1); ?>" size="40" /></td>
     </tr>
     <tr class="fonce">
       <td class="designation">Adresse suite</td>
       <td><input name="adresse2" type="text" class="form" value="<?php echo($client->adresse2); ?>" size="40" /></td>
     </tr>
     <tr class="claire">
       <td class="designation">Adresse suite 2</td>
       <td><input name="adresse3" type="text" class="form" value="<?php echo($client->adresse3); ?>" size="40" /></td>
     </tr>     
     <tr class="fonce">
       <td class="designation">Code postal</td>
       <td><input name="cpostal" type="text" class="form" value="<?php echo($client->cpostal); ?>" size="40" /></td>
     </tr>
     <tr class="claire">
       <td class="designation">Ville</td>
       <td><input name="ville" type="text" class="form" value="<?php echo($client->ville); ?>" size="40" /></td>
     </tr>
     <tr class="fonce">
       <td class="designation">Pays</td>
       <td>
    <select name="pays" class="form_client">
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
     <tr class="claire">
       <td class="designation">T&eacute;l&eacute;phone fixe</td>
       <td><input name="telfixe" type="text" class="form" value="<?php echo($client->telfixe); ?>" size="40" /></td>
     </tr>
     <tr class="fonce">
       <td class="designation">T&eacute;l&eacute;phone portable </td>
       <td><input name="telport" type="text" class="form" value="<?php echo($client->telport); ?>" size="40" /></td>
     </tr>
     <tr class="claire">
       <td class="designation">E-mail</td>
       <td><input name="email" type="text" class="form" value="<?php echo($client->email); ?>" size="40" /></td>
     </tr>
     <tr class="fonce">
       <td class="designation">Remise</td>
       <td><input name="pourcentage" type="text" class="form" value="<?php echo($client->pourcentage); ?>" size="40" /></td>
     </tr>     
     <tr class="clairebottom">
       <td class="designation">Revendeur </td>
       <td><input type="checkbox" name="type" <?php if($client->type) { ?> checked="checked" <?php } ?> class="form"/></td>
     </tr> 
   </table>
			
</div>
<!-- fin du bloc description -->
</form>
   </div>
   <?php include_once("pied.php");?>
</div>
</div>
</body>
</html>
