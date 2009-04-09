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
	
?>
<?php
	include_once("../classes/Administrateur.class.php");

	if($action == "modifier"){

		$administrateur = new Administrateur();

 		$administrateur->charger_id($id);
 		$administrateur->valeur = $valeur;	
		$administrateur->identifiant = $identifiant;
		$motdepasse1 = trim($motdepasse1);
		$motdepasse2 = trim($motdepasse2);
		
		if($motdepasse1 != ""){
			$administrateur->motdepasse = $motdepasse1;
			$administrateur->crypter();
		}	
		$administrateur->nom = $nom;
		$administrateur->prenom = $prenom;
		$administrateur->niveau = "1";
		$administrateur->maj();
		
		if(trim($motdepasse1) != ""){
?>
<script type="text/javascript">
	alert("Mot de passe change avec succes");
	location = "gestadm.php";
</script>
<?php
	} else {
		header("Location: gestadm.php");
		
	}
?>
<?php
	
	}
	
	if($action == "ajouter"){
		$admin = new Administrateur();
		
		$admin->valeur = $valeur;
		$admin->nom = $nom;
		$admin->prenom = $prenom;
		$admin->identifiant = $identifiant;
		$admin->niveau = "1";
		$motdepasse1 = trim($motdepasse1);
		$admin->motdepasse = $motdepasse1;
		$admin->crypter();
		$admin->add();
		header("location: gestadm.php");
	}
	
	if($action == "supprimer"){
		
		$admin = new Administrateur();
		$admin->charger_id($id);
		$admin->delete();
		header("Location: gestadm.php");
	}		

?>
