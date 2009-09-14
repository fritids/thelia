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
<?php
        include_once("../fonctions/divers.php");
        include_once("../classes/Zone.class.php"); 
        include_once("../classes/Modules.class.php");
        include_once("../classes/Transzone.class.php");
		
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include_once("title.php");?>
<script src="../lib/jquery/jquery.js" type="text/javascript"></script>
<?php include_once("js/transport.php"); ?>
</head>

<body>
<div id="wrapper">
<div id="subwrapper">

<?php
	$menu="configuration";
	include_once("entete.php");
?>

<div id="contenu_int"> 
    <p><a href="accueil.php" class="lien04">Accueil </a><img src="gfx/suivant.gif" width="12" height="9" border="0" /><a href="configuration.php" class="lien04"> Configuration</a> <img src="gfx/suivant.gif" width="12" height="9" border="0" /> <a href="#" class="lien04">Gestion des transports</a></p>
    
<!-- Début de la colonne de gauche -->  
<div id="bloc_description">
	<div class="bordure_bottom">
<!-- bloc des listes de transports -->  	
		<div class="entete_liste_config">
			<div class="titre">LISTE DES TRANSPORTS</div>
		</div>
	
<?php
	$module = new Modules();
	$query = "select * from $module->table where type=\"2\" and actif=\"1\"";
	$resul = mysql_query($query, $module->link);
	
	$i = 0;

	while($row = mysql_fetch_object($resul)){ 

		if($i%2)
			$fond ="ligne_fonce_BlocDescription";
		else
			$fond ="ligne_claire_BlocDescription";
?>
		
		<ul class="<?php echo $fond; ?>">
			<li style="width:534px;"><?php echo $row->nom; ?></li>
			<li style="width:32px;"><a href="transport.php?id=<?php echo $row->id; ?>#lzone">&eacute;diter</a></li>
		</ul>


<?php
		$i++;
	}
?>
</div>
	<!-- fin du bloc des listes de transports -->	
	
<?php
	if($_GET['id']){
		$transzone = new Transzone();
		$zone = new Zone();
		$tr = new Modules();
		$tr->charger_id($_GET['id']);

?>	
<a name="lzone">&nbsp;</a>
	<div class="bordure_bottom" id="listezone">
		<div class="entete_liste_config" style="margin-top:15px;">
			<div class="titre">MODIFICATION DU TRANSPORT <?php echo strtoupper($tr->nom); ?></div>
		</div>
		<ul class="ligne1">
				<li style="width:250px;">
					<select class="form_select" id="zone">
					<?php
						$query = "select * from $zone->table";
						$resul = mysql_query($query, $transzone->link);
						while($row = mysql_fetch_object($resul)){	
							$test = new Transzone();
							if(! $test->charger($_GET['id'], $row->id)){
					?>				
			     	<option value="<?php echo $row->id; ?>"><?php echo $row->nom; ?></option>
			     	<?php
			     			}
			     		}
			     	?>
					</select>
				</li>
				<li><a href="javascript:ajouter($('#zone').val())">AJOUTER UNE ZONE</a></li>
		</ul>
		
<?php 
			$query = "select * from $transzone->table where transport=\"" . $_GET['id']. "\"";
			$resul = mysql_query($query, $transzone->link);
			
			$i = 0;
			
			while($row = mysql_fetch_object($resul)){
				$zone = new Zone();
				$zone->charger($row->zone);
				
				
				if($i%2)
					$fond = "ligne_fonce_BlocDescription";
				else
					$fond = "ligne_claire_BlocDescription";
						
?>		
		<ul class="<?php echo $fond; ?>">
				<li style="width:505px;"><?php echo $zone->nom; ?></li>
				<li style="width:32px;"><a href="javascript:supprimer(<?php echo $row->id; ?>)">Supprimer</a></li>
		</ul>
<?php

			$i++;
	}
?>
</div>		
	
<?php
	}
?>	
	
</div>
<!-- fin du bloc description -->	
</div>
<?php include_once("pied.php");?>
</div>
</div>
</body>
</html>
