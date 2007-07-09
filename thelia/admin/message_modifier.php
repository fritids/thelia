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
		$messagedesc->titre = $_POST['titre'];
		$messagedesc->chapo = $_POST['chapo'];
		$messagedesc->description = $_POST['description'];
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
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>THELIA / BACK OFFICE</title>
<link href="styles.css" rel="stylesheet" type="text/css" />
</head>

<body>

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
   <p class="titre_rubrique">Gestion des messages</p>
   <p align="right" class="geneva11Reg_3B4B5B"><a href="accueil.php" class="lien04">Accueil</a> <img src="gfx/suivant.gif" width="12" height="9" border="0" /> <a href="configuration.php" class="lien04">Configuration</a> &nbsp;<img src="gfx/suivant.gif" width="12" height="9" border="0" /> <a href="message.php" class="lien04">Gestion des messages</a> &nbsp;<img src="gfx/suivant.gif" width="12" height="9" border="0" /> <a href="#" class="lien04">Modifier</a>   </p>
 
 <table width="710" border="0" cellpadding="5" cellspacing="0">
    <tr>
      <td width="600" height="30" class="titre_cellule_tres_sombre">
					Modifier le message 
							<?php
								$langl = new Lang();
								$query = "select * from $langl->table";
								$resul = mysql_query($query);
								while($row = mysql_fetch_object($resul)){
									$langl->charger($row->id);
						    ?>

						  		 &nbsp; <a href="<?php echo($_SERVER['PHP_SELF']); ?>?nom=<?php echo($nom); ?>&lang=<?php echo($langl->id); ?>"  class="lien06"><?php echo($langl->description); ?></a>
						  		&nbsp; 
						  <?php } ?> 
		</td>
    </tr>
  </table>


	<form action="<?php echo $_SERVER['PHP_SELF'] ?>" id="formulaire" method="POST">
	
   <table width="710" border="0" cellpadding="5" cellspacing="0">

   <tr>
     <td width="400" height="30"  class="cellule_sombre">NOM</td>
     <td width="130"  class="cellule_sombre"><?php echo $message->nom; ?></td>
     <td width="130"  class="cellule_sombre">&nbsp;</td>
     <td width="10"  class="cellule_sombre">&nbsp;</td>
   </tr>


     <tr>
       <td width="400" height="30" class="cellule_claire">TITRE</td>
       <td width="130"  class="cellule_claire"><input type="text" class="form" name="titre" size="50" value="<?php echo $messagedesc->titre; ?>" /></td>
       <td width="130"  class="cellule_claire">&nbsp;</td>
       <td width="10"  class="cellule_claire">&nbsp;</td>
     </tr>


     <tr>
       <td height="30" class="cellule_sombre">CHAPO</td>
       <td class="cellule_sombre">
         <textarea name="chapo" class="form" cols="30" rows="5"><?php echo $messagedesc->chapo; ?></textarea>
       </td>
       <td class="cellule_sombre">&nbsp;</td>
       <td align="center" valign="middle" class="cellule_sombre">&nbsp;</td>
     </tr>

     <tr>
       <td height="30" class="cellule_claire">DESCRIPTION</td>
       <td class="cellule_claire">
       <textarea name="description" class="form" cols="50" rows="10"><?php echo $messagedesc->description; ?></textarea>
       </td>
       <td class="cellule_claire">&nbsp;</td>
       <td align="center" valign="middle" class="cellule_claire">&nbsp;</td>
     </tr>

     <tr>
       <td height="30" class="cellule_sombre">&nbsp;</td>
       <td class="cellule_sombre"><a href="#" class="txt_vert_11" onClick="document.getElementById('formulaire').submit();">Modifier</a> <a href="#"><img src="gfx/suivant.gif" onClick="document.getElementById('formulaire').submit();" width="12" height="9" border="0" /></a></span></span></td>
       <td class="cellule_sombre">&nbsp;</td>
       <td align="center" valign="middle" class="cellule_sombre">&nbsp;</td>
     </tr>
   
   <input type="hidden" name="action" value="modifier" />
   <input type="hidden" name="lang" value="<?php echo $lang ?>" />
   <input type="hidden" name="nom" value="<?php echo($message->nom); ?>" />

	 </table> 

	</form>
	
</div>
</body>
</html>
