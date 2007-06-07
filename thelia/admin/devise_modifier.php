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
	include_once("../classes/Devise.class.php");
	include_once("../classes/Cache.class.php");

	if($action == "modifier"){
	
		$devise = new Devise();

 		$devise->charger($id);
 		$devise->nom = $nom;	
 		$devise->taux = $taux;	
 		$devise->maj();
 
		$cache = new Cache();
		$cache->vider("PRODUIT", "%");		
					
		header("Location: devise.php");
	}		
	
	else if($action == "ajouter"){
		
		$devise = new Devise();

 		$devise->nom = $nnom;	
 		$devise->taux = $ntaux;	
 		$devise->add();		

		$cache = new Cache();
		$cache->vider("PRODUIT", "%");
				
		header("Location: devise.php");
	}

	else if($action == "supprimer"){
		
		$devise = new Devise();
 		$devise->charger($id);
 		$devise->delete();		

		$cache = new Cache();
		$cache->vider("PRODUIT", "%");
				
		header("Location: devise.php");
	}	
?>
