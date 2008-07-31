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
	include_once("classes/Dossier.class.php");
	include_once("classes/Dossierdesc.class.php");
	
	/* Substitutions de type dossier */
		
	function substitdossier($texte){
		global $id_dossier;
		
		$tdossier = new Dossier();
		$tdossierdesc = new Dossierdesc();
		
		if($id_dossier){
			$tdossier->charger($id_dossier);
			$tdossierdesc->charger($tdossier->id, $_SESSION['navig']->lang);
		}

		$texte = str_replace("#DOSSIER_CHAPO", "$tdossierdesc->chapo", $texte);
		$texte = str_replace("#DOSSIER_DESCRIPTION", "$tdossierdesc->description", $texte);
		$texte = str_replace("#DOSSIER_POSTSCRIPTUM", "$tdossierdesc->postscriptum", $texte);
		$texte = str_replace("#DOSSIER_ID", "$tdossier->id", $texte);
		$texte = str_replace("#DOSSIER_NOM", "$tdossierdesc->titre", $texte);
		$texte = str_replace("#DOSSIER_PARENT", "$tdossier->parent", $texte);

		return $texte;
	}
	
?>