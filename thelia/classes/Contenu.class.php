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
	include_once(realpath(dirname(__FILE__)) . "/Contenudesc.class.php");
	include_once(realpath(dirname(__FILE__)) . "/Image.class.php");
	include_once(realpath(dirname(__FILE__)) . "/Document.class.php");
		
	class Contenu extends Baseobj{

		var $id;
		var $datemodif;
		var $ligne;
		var $dossier; 
		var $classement;
	 
		var $table="contenu";
		var $bddvars=array("id", "datemodif", "ligne", "dossier", "classement");
		
		function Contenu(){
			$this->Baseobj();	
		}


		function charger($id){
		
			return $this->getVars("select * from $this->table where id=\"$id\"");

		}

		function changer_classement($id, $type){
			
			$this->charger($id);
			$remplace = new Contenu();
			
			if($type == "M")
				$res = $remplace->getVars("select * from $this->table where dossier=\"" . $this->dossier. "\" and classement<" . $this->classement . " order by classement desc limit 0,1");
			
			else if($type == "D")
				$res  = $remplace->getVars("select * from $this->table where dossier=\"" . $this->dossier. "\" and classement>" . $this->classement . " order by classement limit 0,1");
		
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

			$image = new Image();
			
			$query = "select * from $image->table where produit=\"" . $this->id . "\"";
			$resul = mysql_query($query, $image->link);
			while($row = mysql_fetch_object($resul)){
				$tmp = new Image();
				$tmp->charger($row->id);
				$tmp->supprimer();
				
			}

			$document = new Document();

			$query = "select * from $document->table where produit=\"" . $this->id . "\"";
			$resul = mysql_query($query, $document->link);
			while($row = mysql_fetch_object($resul)){
				$tmp = new Document();
				$tmp->charger($row->id);
				$tmp->supprimer();
				
			}
			
			$contenudesc =  new Contenudesc();
			
			
			$this->delete("delete from $this->table where id=\"$this->id\"");	
			$this->delete("delete from $contenudesc->table where contenu=\"$this->id\"");	

			
			return 1;
		
		}

		
	}

?>