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
	include_once("Baseobj.class.php");
	include_once("Imagedesc.class.php");
		
	class Image extends Baseobj{

		var $id;
		var $produit;
		var $rubrique;
		var $contenu;
		var $dossier;
		var $fichier;
		var $classement;
		
		var $table="image";
		var $bddvars = array("id", "produit", "rubrique", "contenu", "dossier", "fichier", "classement");

		function Image(){
			$this->Baseobj();	
		}
		
		function charger($id){
		
			return $this->getVars("select * from $this->table where id=\"$id\"");


		}

		function delete($requete){
			
				$resul = mysql_query($requete);	
		}
		
		function supprimer(){

			$imagedesc =  new Imagedesc();
			
			
			$this->delete("delete from $this->table where id=\"$this->id\"");	
			$this->delete("delete from $imagedesc->table where image=\"$this->id\"");	

			
			return 1;
		
		}
		
	}


?>
