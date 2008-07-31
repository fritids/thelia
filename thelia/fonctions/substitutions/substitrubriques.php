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
	include_once("classes/Rubrique.class.php");
	include_once("classes/Rubriquedesc.class.php");
	
	/* Substitutions de type rubrique */
		
	function substitrubriques($texte){
		global $id_rubrique;
		
		$trubrique = new Rubrique();
		$trubriquedesc = new Rubriquedesc();
		
		if($id_rubrique){
			$trubrique->charger($id_rubrique);
			$trubriquedesc->charger($trubrique->id, $_SESSION['navig']->lang);
		}

		$racine = new Rubrique();
		$racine->charger($trubrique->id);
		
		while($racine->parent)
			$racine->charger($racine->parent);
		
		$texte = str_replace("#RUBRIQUE_CHAPO", "$trubriquedesc->chapo", $texte);
		$texte = str_replace("#RUBRIQUE_DESCRIPTION", "$trubriquedesc->description", $texte);
		$texte = str_replace("#RUBRIQUE_POSTSCRIPTUM", "$trubriquedesc->postscriptum", $texte);
		$texte = str_replace("#RUBRIQUE_ID", "$trubrique->id", $texte);
		$texte = str_replace("#RUBRIQUE_LIEN", "$trubrique->lien", $texte);
		$texte = str_replace("#RUBRIQUE_NOM", "$trubriquedesc->titre", $texte);
		$texte = str_replace("#RUBRIQUE_PARENT", "$trubrique->parent", $texte);
		$texte = str_replace("#RUBRIQUE_RACINE", "$racine->id", $texte);
		
		if($id_rubrique)
			$texte = str_replace("#RUBRIQUE_REWRITEURL", rewrite_rub("$trubrique->id"), $texte);	
		else 
			$texte = str_replace("#RUBRIQUE_REWRITEURL", "", $texte);	
		
		return $texte;
	}

?>