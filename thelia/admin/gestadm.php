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
	include_once("../classes/Administrateur.class.php");
	include_once("../classes/Profildesc.class.php");
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
   <p align="left"><a href="accueil.php" class="lien04">Accueil</a>  <img src="gfx/suivant.gif" width="12" height="9" border="0" /> <a href="configuration.php" class="lien04">Configuration</a> <img src="gfx/suivant.gif" width="12" height="9" border="0" / <a href="gestadm.php" class="lien04">Gestion des administrateurs</a></p>

<!-- bloc dŽclinaisons / colonne gauche -->  

<div class="entete_liste_config">
	<div class="titre">LISTE DES ADMINISTRATEURS</div>
	<div class="fonction_ajout"><a href="#" onclick="$('#ajout_admin').show()">AJOUTER UN ADMINISTRATEUR</a></div>
</div>
<ul class="Nav_bloc_description">
		<li style="height:25px; width:158px;">Nom</li>
		<li style="height:25px; width:157px; border-left:1px solid #96A8B5;">Pr&eacute;nom</li>
		<li style="height:25px; width:157px; border-left:1px solid #96A8B5;">Identifiant</li>
		<li style="height:25px; width:157px; border-left:1px solid #96A8B5;">Mot de passe</li>
		<li style="height:25px; width:157px; border-left:1px solid #96A8B5;">Confirmation</li>
		<li style="height:25px; width:77px; border-left:1px solid #96A8B5;">&nbsp;</li>
		<li style="height:25px; width:30px; border-left:1px solid #96A8B5;">&nbsp;</li>
</ul>
<div class="bordure_bottom">
 	<?php
  	
	$administrateur = new Administrateur();

 	$query = "select * from $administrateur->table";
  	$resul = mysql_query($query, $administrateur->link);
  	$i=0;
  	while($row = mysql_fetch_object($resul)){
			if(!($i%2)) $fond="ligne_claire_rub";
  			else $fond="ligne_fonce_rub";
  			$i++;
 	 ?>
    <form action="gestadm_modifier.php" id="formadmin<?php echo($row->id); ?>" method="post" onsubmit="valid('<?php echo $row->id; ?>');return false;">
		<ul class="<?php echo $fond; ?>">
			<li style="width:150px;"><input name="nom" type="text" class="form" value="<?php echo($row->nom); ?>" size="20" /></li>
			<li style="width:150px; border-left:1px solid #96A8B5;"><input name="prenom" type="text" class="form" value="<?php echo($row->prenom); ?>" size="20" /></li>
			<li style="width:150px; border-left:1px solid #96A8B5;"><input name="identifiant" type="text" class="form" value="<?php echo($row->identifiant); ?>" size="20" /></li>
			<li style="width:150px; border-left:1px solid #96A8B5;"><input name="motdepasse1" id="motdepasse1<?php echo($row->id); ?>" type="password" value="<?php echo $pass; ?>" class="form" size="20" onclick="this.value='';" /></li>
			<li style="width:150px; border-left:1px solid #96A8B5;"><input name="motdepasse2" id="motdepasse2<?php echo($row->id); ?>" type="password" value="<?php echo $pass; ?>" class="form" size="20" onclick="this.value='';" /></li>
			<li style="width:70px; border-left:1px solid #96A8B5;"><a href="#" onclick="valid('<?php echo $row->id; ?>');return false;">modifier</a></li>
			<li style="width:30px; border-left:1px solid #96A8B5; text-align:right;"><?php if($_SESSION['util']->id != $row->id) { ?><a href="#" onclick="supp('<?php echo $row->id; ?>')"><img src="gfx/supprimer.gif" width="9" height="9" border="0" /></a><?php } ?></li>
		</ul>
 	<input type="hidden" name="action" value="modifier" />
   	<input type="hidden" name="id" value="<?php echo($row->id); ?>" />
   	</form>   
	 <?php } ?>
</div>


<div class="bordure_bottom" id="ajout_admin" style="display: none;">
<form action="gestadm_modifier.php" id="formadmin" method="post" onsubmit="ajout();return false;">
   <input type="hidden" name="action" value="ajouter" />

		<div class="entete_liste_config" style="margin-top:10px;">
			<div class="titre">AJOUT D'UN ADMINISTRATEUR</div>
		</div>
		<ul class="Nav_bloc_description">
			<li style="height:25px; width:128px;">Nom</li>
			<li style="height:25px; width:127px; border-left:1px solid #96A8B5;">Pr&eacute;nom</li>
			<li style="height:25px; width:127px; border-left:1px solid #96A8B5;">Identifiant</li>
			<li style="height:25px; width:127px; border-left:1px solid #96A8B5;">Mot de passe</li>
			<li style="height:25px; width:127px; border-left:1px solid #96A8B5;">Confirmation</li>
			<li style="height:25px; width:217px; border-left:1px solid #96A8B5;">Profil</li>
			<li style="height:25px; width:30px; border-left:1px solid #96A8B5;">&nbsp;</li>
		</ul>
		<ul class="ligne_claire_rub">
			<li style="width:120px;"><input name="nom" type="text" class="form" size="15" /></li>
			<li style="width:120px; border-left:1px solid #96A8B5;"><input name="prenom" type="text" class="form" size="15" /></li>
			<li style="width:120px; border-left:1px solid #96A8B5;"><input name="identifiant" type="text" class="form" size="15" /></li>
			<li style="width:120px; border-left:1px solid #96A8B5;"><input name="motdepasse1" id="motdepasse1" type="password" class="form" size="15" /></li>
			<li style="width:120px; border-left:1px solid #96A8B5;"><input name="motdepasse2" id="motdepasse2" type="password" class="form" size="15" onclick="this.value='';" /></li>
			<li style="width:210px; border-left:1px solid #96A8B5;">
			<select name="profil">
			<?php
				$profildesc = new Profildesc();
				$query = "select * from $profildesc->table where lang=\"1\"";
				$resul = mysql_query($query, $profildesc->link);
				while($row = mysql_fetch_object($resul)){
			?>	
				<option value="<?php echo $row->profil; ?>"><?php echo $row->titre; ?></option>
			<?php
				}
			?>
			</select>
			</li>
			<li style="width:30px; border-left:1px solid #96A8B5;"><a href="#" onclick="ajout();return false;">ajouter</a></li>
		</ul>
</form>       
</div>


</div>
<!-- fin du bloc de description / colonne de gauche -->

	  
<?php include_once("pied.php");?>
</div>
</div>
</body>
</html>
