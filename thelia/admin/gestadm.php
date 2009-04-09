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
	include_once("../classes/Administrateur.class.php");
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include_once("title.php");?>

<script type="text/javascript" src="../lib/jquery/jquery.js"></script>

<script type="text/javascript">
	function valid(admin){

		if(document.getElementById('motdepasse1' + admin ).value == document.getElementById('motdepasse2' + admin ).value)
			document.getElementById('formadmin' + admin).submit();
		else{
			alert("Veuillez verifier votre mot de passe");
			return false;
		}
	}
	
	function ajout(){

		if(document.getElementById('motdepasse1').value == document.getElementById('motdepasse2').value && document.getElementById('motdepasse1').value != "")
			document.getElementById('formadmin').submit();
		else{
			alert("Veuillez verifier votre mot de passe");
			return false;
		}
	}
	
	function supp(admin){
		if(confirm("confirmez-vous la suppression de cet administrateur?")){
			window.location="gestadm_modifier.php?action=supprimer&id="+admin;
		}
	}
</script>

</head>

<body>
<div id="wrapper">
<div id="subwrapper">

<?php
	$menu="configuration";
	include_once("entete.php");
?>

<div id="contenu_int"> 
   <p align="left"><a href="accueil.php" class="lien04">Accueil</a>  <img src="gfx/suivant.gif" width="12" height="9" border="0" /> <a href="configuration.php" class="lien04">Configuration</a> <img src="gfx/suivant.gif" width="12" height="9" border="0" / <a href="variable.php" class="lien04">Gestion des administrateurs</a></p>

<!-- bloc dŽclinaisons / colonne gauche -->  
<div id="bloc_description">
<div class="entete_liste_config">
	<div class="titre">LISTE DES ADMINISTRATEURS</div>
	<div class="fonction_valider"><a href="#" onclick="$('#ajout_admin').show()">AJOUTER UN ADMINISTRATEUR</a></div>
</div>
<ul class="Nav_bloc_description">
		<li style="height:25px; width:94px;">Nom</li>
		<li style="height:25px; width:92px; border-left:1px solid #96A8B5;">Pr&eacute;nom</li>
		<li style="height:25px; width:92px; border-left:1px solid #96A8B5;">Identifiant</li>
		<li style="height:25px; width:80px; border-left:1px solid #96A8B5;">Mot de passe</li>
		<li style="height:25px; width:80px; border-left:1px solid #96A8B5;">Confirmation</li>
</ul>
<div class="bordure_bottom">
 	<?php
  	
	$administrateur = new Administrateur();

 	$query = "select * from $administrateur->table";
  	$resul = mysql_query($query, $administrateur->link);
  	$i=0;
  	while($row = mysql_fetch_object($resul)){
			if(!($i%2)) $fond="claire";
  			else $fond="fonce";
  			$i++;
 	 ?>
    <form action="gestadm_modifier.php" id="formadmin<?php echo($row->id); ?>" method="post" onsubmit="valid('<?php echo $row->id; ?>');return false;">
		<ul class="<?php echo $fond; ?>">
			<li style="width:95px;"><input name="nom" type="text" class="form" value="<?php echo($row->nom); ?>" size="11" /></li>
			<li style="width:95px;"><input name="prenom" type="text" class="form" value="<?php echo($row->prenom); ?>" size="11" /></li>
			<li style="width:95px;"><input name="identifiant" type="text" class="form" value="<?php echo($row->identifiant); ?>" size="11" /></li>
			<li style="width:85px;"><input name="motdepasse1" id="motdepasse1<?php echo($row->id); ?>" type="password" value="<?php echo $pass; ?>" class="form" size="6" onclick="this.value='';" /></li>
			<li style="width:80px;"><input name="motdepasse2" id="motdepasse2<?php echo($row->id); ?>" type="password" value="<?php echo $pass; ?>" class="form" size="6" onclick="this.value='';" /></li>
			<li style="width:80px;"><a href="#" onclick="document.getElementById('formvariable<?php echo($row->id); ?>').submit();">modifier</a></li>
			<li style="width:20px;"><a href="#" onclick="supp('<?php echo $row->id; ?>')"><img src="gfx/supprimer.gif" width="9" height="9" border="0" /></a></li>
		</ul>
 	<input type="hidden" name="action" value="modifier" />
   	<input type="hidden" name="id" value="<?php echo($row->id); ?>" />
   	</form>   
	 <?php } ?>
</div>


<div class="bordure_bottom" id="ajout_admin" style="display: none;">
<form action="gestadm_modifier.php" id="formadmin" method="post" onsubmit="valid('<?php echo $row->id; ?>');return false;">
   <input type="hidden" name="action" value="ajouter" />

		<div class="entete_liste_config" style="margin-top:10px;">
			<div class="titre">AJOUT D'UN ADMINISTRATEUR</div>
		</div>
		<ul class="Nav_bloc_description">
			<li style="height:25px; width:94px;">Nom</li>
			<li style="height:25px; width:92px; border-left:1px solid #96A8B5;">Pr&eacute;nom</li>
			<li style="height:25px; width:92px; border-left:1px solid #96A8B5;">Identifiant</li>
			<li style="height:25px; width:80px; border-left:1px solid #96A8B5;">Mot de passe</li>
			<li style="height:25px; width:80px; border-left:1px solid #96A8B5;">Confirmation</li>
		</ul>
		<ul class="claire">
			<li style="width:93px;"><input name="nom" type="text" class="form" size="11" /></li>
			<li style="width:93px;"><input name="prenom" type="text" class="form" size="11" /></li>
			<li style="width:95px;"><input name="identifiant" type="text" class="form" size="11" /></li>
			<li style="width:85px;"><input name="motdepasse1" id="motdepasse1" type="password" class="form" size="6" /></li>
			<li style="width:85px;"><input name="motdepasse2" id="motdepasse2" type="password" class="form" size="6" onclick="this.value='';" /></li>
			<li style="width:80px;"><a href="#" onclick="valid('<?php echo $row->id; ?>');">ajouter</a></li>
		</ul>
</form>       
</div>


</div>
<!-- fin du bloc de description / colonne de gauche -->

	  
</div>
<?php include_once("pied.php");?>
</div>
</div>
</body>
</html>
