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
	include_once("../classes/Modules.class.php");
?>
<?php
	
	if($actif != ""){

		$modules = new Modules();
		$modules->charger($nom);
		$modules->actif = $actif;
		$modules->nom = $nom;		
		
		$modules->maj();
		
	}		

	$nomclass = $nom;
	$nomclass[0] = strtoupper($nomclass[0]);
	
	if($actif == 1){
		include_once("../client/plugins/$nom/$nomclass". ".class.php");
		$tmpobj = new $nomclass();
		$tmpobj->init();

		
	}

	else if($actif == 0){
		include_once("../client/plugins/$nom/$nomclass". ".class.php");
		$tmpobj = new $nomclass();
		$tmpobj->destroy();
	}

	header("Location: plugins.php");


?>