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
<?php if(! est_autorise("acces_configuration")) exit; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include_once("title.php");?>
</head>

<body>
<div id="wrapper">
<div id="subwrapper">

<?php
	include_once("../classes/Message.class.php");
	include_once("../classes/Messagedesc.class.php");
?>
<?php
	$menu="configuration";
	include_once("entete.php");
?>

<div id="contenu_int"> 
     <p align="left"><a href="accueil.php" class="lien04">Accueil </a> <img src="gfx/suivant.gif" width="12" height="9" border="0" /><a href="configuration.php" class="lien04">Configuration</a> <img src="gfx/suivant.gif" width="12" height="9" border="0" /> <a href="#" class="lien04">Gestion des messages</a>           
    </p>
<!-- bloc gestion des messages / colonne gauche -->  
<div id="bloc_description">

<div class="entete_liste_config">
	<div class="titre">LISTE DES MESSAGES</div>
</div>
<div class="bordure_bottom">
<?php
	$i=0;
	
	$message = new Message();
	$query = "select * from $message->table";
	$resul = mysql_query($query, $message->link);
	
	while($row = mysql_fetch_object($resul)){
		
		 $i++;
		
		if(!($i%2)) $fond="ligne_fonce_BlocDescription";
  		else $fond="ligne_claire_BlocDescription";

		$messagedesc = new Messagedesc();
		$messagedesc->charger($row->id);
?>


  <ul class="<?php echo $fond; ?>">
    <li style="width:530px"><?php if($messagedesc->intitule != "") echo $messagedesc->intitule; else echo $row->nom; ?></li>
    <li style="border-left:1px solid #C4CACE;"><a href="message_modifier.php?nom=<?php echo $row->nom ?>">&eacute;diter</a></li>
  </ul>


<?php 

	}
?>
</div>
</div>
<!-- bloc du bloc de gestion des messages / colonne gauche -->  

</div>

<?php include_once("pied.php");?>
</div>
</div>
</body>
</html>
