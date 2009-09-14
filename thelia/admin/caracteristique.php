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
	
	if(!isset($liste)) $liste="";
	if(!isset($i)) $i=0;
	if(!isset($id)) $id="";
	
?>
<?php if(! est_autorise("acces_configuration")) exit; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include_once("title.php"); ?>
<script src="../lib/jquery/jquery.js" type="text/javascript"></script>
<script src="../lib/jquery/jeditable.js" type="text/javascript"></script>
<script src="../lib/jquery/menu.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function() {
   edit();
});

function edit(){
	$(".texte_edit").editable("ajax/produit.php", { 
	      select : true,
	      onblur: "submit",
	      cssclass : "ajaxedit"
	  }); 
		$(".classement_edit").editable("ajax/classement.php", { 
		      select : true,
		      onblur: "submit",
		      cssclass : "ajaxedit",
			  callback : function(value, settings){
							$("#resul").html(value);
							edit();
						}
		  });
	
}
</script>
</head>

<body>
<div id="wrapper">
<div id="subwrapper">

<?php
	include_once("../classes/Caracteristique.class.php");
	include_once("../fonctions/divers.php");?>
<?php
	$menu="configuration";
	include_once("entete.php");
?>

<div id="contenu_int"> 
   <p align="left"><span class="lien04"><a href="accueil.php" class="lien04">Accueil</a></span> <img src="gfx/suivant.gif" width="12" height="9" border="0" /><a href="configuration.php" class="lien04"> Configuration</a> <img src="gfx/suivant.gif" width="12" height="9" border="0" /><a href="caracteristique.php" class="lien04"> Gestion des caract&eacute;ristiques</a></p>

<div class="entete_liste_config">
	<div class="titre">LISTE DES CARACTERISTIQUES</div>
	<div class="fonction_ajout">
	<form action="caracteristique_modifier.php" id="form_ajout" method="post">
	 	<input type="hidden" name="parent" value="<?php echo($parent); ?>" />
		<input type="hidden" name="id" value="<?php echo($id); ?>" />
	  	<a href="#" onclick="document.getElementById('form_ajout').submit()">AJOUTER UNE NOUVELLE CARACTERISTIQUE</a>
	</form>
	</div>
</div>


<ul id="Nav">
		<li style="height:25px; width:119px; border-left:1px solid #96A8B5;"></li>
		<li style="height:25px; width:655px; border-left:1px solid #96A8B5;">Titre de la caract&eacute;ristique</li>
		<li style="height:25px; width:39px; border-left:1px solid #96A8B5;"></li>	
		<li style="height:25px; width:78px; border-left:1px solid #96A8B5; background-image: url(gfx/picto_menu_deroulant.gif); background-position:right bottom; background-repeat: no-repeat;">
			Classement
			<ul class="Menu">
				<li><a href="javascript:tri('ASC','','1','classement','')">Tri croissant</a></li>
				<li><a href="javascript:tri('DESC','','1','classement','')">Tri d&eacute;croissant</a></li>
			</ul>
		</li>
		<li style="height:25px; width:44px; border-left:1px solid #96A8B5;">Suppr.</li>	
</ul>

 <div class="bordure_bottom" id="resul">

<?php
	
	$caracteristique = new Caracteristique();
	$caracteristiquedesc = new Caracteristiquedesc();
	
	$query = "select * from $caracteristique->table";
	$resul = mysql_query($query, $caracteristique->link);		


	while($row = mysql_fetch_object($resul)){
		$liste .= $row->id . ",";
	
	}

	
	$liste = substr($liste, 0, strlen($liste)-1);
	
	if($liste != "") {

		$query = "select * from $caracteristiquedesc->table,$caracteristique->table where $caracteristiquedesc->table.caracteristique=$caracteristique->table.id and $caracteristiquedesc->table.caracteristique in ($liste) and $caracteristiquedesc->table.lang='1' order by classement";
		$resul = mysql_query($query, $caracteristiquedesc->link);
	
		while($row = mysql_fetch_object($resul)){	
			$caracteristiquedesc->charger($row->caracteristique);
			
			if(!($i%2)) $fond="ligne_claire_rub";
  			else $fond="ligne_fonce_rub";
  			$i++;
		

?>     
<ul class="<?php echo($fond); ?>">
	<li style="width:112px;"></li>
	<li style="width:648px;"><span id="titrerub_<?php echo $row->id; ?>" class="texte_edit"><?php echo($caracteristiquedesc->titre); ?></span></li>
	<li style="width:32px;"><a href="<?php echo "caracteristique_modifier.php?id=$caracteristiquedesc->caracteristique"; ?>">&eacute;diter</a></li>
	<li style="width:71px;">
	 <div class="bloc_classement">  
	    <div class="classement"><a href="caracteristique_modifier.php?id=<?php echo($caracteristiquedesc->caracteristique); ?>&action=modclassement&type=M"><img src="gfx/up.gif" border="0" /></a></div>
	    <div class="classement"><span id="classementcarac_<?php echo $row->id ?>" class="classement_edit"><?php echo $row->classement; ?></span></div>
	    <div class="classement"><a href="caracteristique_modifier.php?id=<?php echo($caracteristiquedesc->caracteristique); ?>&action=modclassement&type=D"><img src="gfx/dn.gif" border="0" /></a></div>
	 </div>
	</li>
	<li style="width:37px; text-align:center;"><a href="<?php echo "caracteristique_modifier.php?id=$caracteristiquedesc->caracteristique&action=supprimer"; ?>" ><img src="gfx/supprimer.gif" width="9" height="9" border="0" /></a></li>
</ul>

       
<?php
		}

		if(!($i%2)) $fond="ligne_claire_rub";
  		else $fond="ligne_fonce_rub";
  		$i++;
}
?> 
</div>
</div>
<?php include_once("pied.php");?>
</div>
</div>
</body>
</html>
