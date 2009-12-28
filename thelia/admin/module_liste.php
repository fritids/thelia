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
<?php if(! est_autorise("acces_modules")) exit; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include_once("title.php");?>
</head>

<body>
<div id="wrapper">
<div id="subwrapper">

<?php
	$menu="plugins";
	include_once("entete.php");
?>

<div id="contenu_int"> 
     <p align="left"><a href="accueil.php" class="lien04">Accueil </a> <img src="gfx/suivant.gif" width="12" height="9" border="0" /><a href="#" class="lien04">Liste des modules</a>              
    </p>
    
<div id="bloc_informations">
	<ul>
	<li class="entete_configuration">LISTE DES MODULES</li>
	
	<?php

	include_once("../classes/Modules.class.php");
	$modules = new Modules();	
	$query = "select * from $modules->table where actif='1' order by classement";
	$resul = mysql_query($query, $modules->link);
	
	$i=0;
	
	while($row = mysql_fetch_object($resul)){

		$verif = new Modules();
		$verif->charger_id($row->id);
		if(! $verif->est_autorise())
			continue;
				
		if(!($i%2)) $fond="fonce";
  		else $fond="claire";

		if(file_exists("../client/plugins/" .$row->nom . "/" . $row->nom. "_admin.php")){
				$i++;
				
				$nom_module = $row->nom;
				$nom_module[0] = strtoupper($nom_module[0]);
				
				$tmpmod = new Modules();
				$tmpmod->charger($row->nom);

				if($tmpmod->xml->nom != "")
					$titre = utf8_decode($tmpmod->xml->nom);
				else
					$titre = $tmpmod->nom;
					
?>     
   	<li class="<?php echo($fond); ?>" style="width:222px; background-color:#9eb0be;border-bottom: 1px dotted #FFF;"><?php echo $titre; ?></li>
	<li class="<?php echo($fond); ?>" style="width:72px;"><a href="module.php?nom=<?php echo $row->nom; ?>">&eacute;diter </a></li>     
<?php
	}
 }
?>  
</ul>
</div>
</div>
<?php include_once("pied.php");?>
</div>
</div>
</body>
</html>