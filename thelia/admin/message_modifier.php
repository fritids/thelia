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
	if(!isset($action)) $action="";
	
	if(! isset($lang)) $lang="1";

?>
<?php if(! est_autorise("acces_configuration")) exit; ?>
<?php
	include_once("../classes/Lang.class.php");
	include_once("../classes/Message.class.php");
	include_once("../classes/Messagedesc.class.php");
	
?>


<?php

	if($action == "modifier"){

		$message = new Message();
		$messagedesc = new Messagedesc();

 		$message->charger($nom);
		$messagedesc->charger($message->id, $lang);
			
		$messagedesc->message = $message->id;
		$messagedesc->intitule = $_POST['intitule'];
		$messagedesc->titre = $_POST['titre'];
		$messagedesc->chapo = $_POST['chapo'];
		$messagedesc->description = $_POST['description'];
		$messagedesc->descriptiontext = $_POST['descriptiontext'];
		$messagedesc->lang = $lang;

		if($messagedesc->id)
 			$messagedesc->maj();

		else
			$messagedesc->add();
 		
	}		
	


?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include_once("title.php");?>
</head>

<body>
<div id="wrapper">
<div id="subwrapper">

<?php
	$menu="configuration";
	include_once("entete.php");
?>

<?php
	
	$message = new Message();
	if(isset($_GET['nom']))
		$message->charger($_GET['nom']);
	else 
		$message->charger($_POST['nom']);
	$messagedesc = new Messagedesc();
	$messagedesc->charger($message->id, $lang);

?>

<div id="contenu_int"> 
   <p align="left"><a href="accueil.php" class="lien04">Accueil</a> <img src="gfx/suivant.gif" width="12" height="9" border="0" /> <a href="configuration.php" class="lien04">Configuration</a> &nbsp;<img src="gfx/suivant.gif" width="12" height="9" border="0" /> <a href="message.php" class="lien04">Gestion des messages</a> &nbsp;<img src="gfx/suivant.gif" width="12" height="9" border="0" /> <a href="#" class="lien04">Modifier</a></p>
 
<!-- bloc dŽclinaisons / colonne gauche -->  
<div id="bloc_description">
 <form action="<?php echo $_SERVER['PHP_SELF'] ?>" id="formulaire" method="POST">
    <input type="hidden" name="action" value="modifier" />
   <input type="hidden" name="lang" value="<?php echo $lang ?>" />
   <input type="hidden" name="nom" value="<?php echo($message->nom); ?>" />	
   
<div class="entete_liste_config">
	<div class="titre">MODIFICATION DU MESSAGE</div>
	<div class="fonction_valider"><a href="#" onclick="document.getElementById('formulaire').submit();">VALIDER LES MODIFICATIONS</a></div>
</div>
 <!-- bloc descriptif de la dŽclinaison --> 
		
<table width="100%" cellpadding="5" cellspacing="0">
    <tr class="claire">
        <th class="designation">Changer la langue</th>
        <th>				<?php
								$langl = new Lang();
								$query = "select * from $langl->table";
								$resul = mysql_query($query);
								while($row = mysql_fetch_object($resul)){
									$langl->charger($row->id);
						    ?>
							<div class="flag"><a href="<?php echo($_SERVER['PHP_SELF']); ?>?nom=<?php echo($nom); ?>&lang=<?php echo($langl->id); ?>"><img src="gfx/lang<?php echo($langl->id); ?>.gif" /></a></div>
						  <?php } ?> 
      						
		</th>
   	</tr>
   	<tr class="fonce">
        <td class="designation">Nom du message</td>
        <td><?php echo $message->nom; ?></td>
   	</tr>
   	<tr class="claire">
        <td class="designation">Intitul&eacute; du message</td>
        <td><input type="text" class="form_long" name="intitule" value="<?php echo $messagedesc->intitule; ?>" /></td>
   	</tr>
   	<tr class="fonce">
        <td class="designation">Titre du message</td>
        <td><input type="text" class="form_long" name="titre" value="<?php echo $messagedesc->titre; ?>" /></td>
   	</tr>
   	<tr class="claire">
        <td class="designation">Chapo<br /><span class="note">(courte description d'introduction format texte)</span></td>
        <td><textarea name="chapo" class="form_long" cols="40" rows="2"><?php echo $messagedesc->chapo; ?></textarea></td>
   	</tr>
   	<tr class="fonce">
        <td class="designation">Description<br /><span class="note">(au format html)</span></td>
        <td><textarea name="description" class="form" cols="53" rows="15"><?php echo $messagedesc->description; ?></textarea></td>
   	</tr>
   	<tr class="claire">
        <td class="designation">Description<br /><span class="note">(au format texte)</span></td>
        <td><textarea name="descriptiontext" class="form" cols="53" rows="15"><?php echo $messagedesc->descriptiontext; ?></textarea></td>
   	</tr>
</table>
</form>
</div>

</div>
<?php include_once("pied.php");?>
</div>	
</div>
</body>
</html>
