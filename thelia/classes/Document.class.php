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
	include_once(realpath(dirname(__FILE__)) . "/Baseobj.class.php");
	include_once(realpath(dirname(__FILE__)) . "/Documentdesc.class.php");
		
	class Document extends Baseobj{

		var $id;
		var $produit;
		var $rubrique;
		var $contenu;
		var $dossier;
		var $fichier;
		var $classement;
		
		var $table="document";
		var $bddvars = array("id", "produit", "rubrique", "contenu", "dossier", "fichier", "classement");

		function Document(){
			$this->Baseobj();	
		}
		
		function charger($id){
		
			return $this->getVars("select * from $this->table where id=\"$id\"");


		}

		function changer_classement($id, $type){
			
			$this->charger($id);
			$remplace = new Document();
			
			if($type == "M")
				$res = $remplace->getVars("select * from $this->table where produit=\"" . $this->produit . "\" and rubrique=\"" . $this->rubrique .  "\" and contenu=\"" . $this->contenu . "\" and dossier=\"" . $this->dossier . "\" and classement<" . $this->classement . " order by classement desc limit 0,1");
			
			else if($type == "D")
				$res  = $remplace->getVars("select * from $this->table where produit=\"" . $this->produit . "\" and rubrique=\"" . $this->rubrique .  "\" and contenu=\"" . $this->contenu . "\" and dossier=\"" . $this->dossier . "\" and classement>" . $this->classement . " order by classement limit 0,1");
		
			if(! $res)
				return "";
				
			$sauv = $remplace->classement;
			
			$remplace->classement = $this->classement;
			$this->classement = $sauv;

            $remplace->maj();
            $this->maj();

		}
		
		function delete($requete){
			
				$resul = mysql_query($requete);	
		}
		
		function supprimer(){

			$documentdesc =  new Documentdesc();
			
			
			$this->delete("delete from $this->table where id=\"$this->id\"");	
			$this->delete("delete from $documentdesc->table where document=\"$this->id\"");	
			
			return 1;
		
		}

	}

?>