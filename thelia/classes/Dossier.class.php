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
	include_once(realpath(dirname(__FILE__)) . "/Dossierdesc.class.php");
	include_once(realpath(dirname(__FILE__)) . "/Contenu.class.php");
	
	class Dossier extends Baseobj{

		var $id;
		var $parent;
		var $lien;
		var $ligne;
		var $classement;
		
		var $table="dossier";
		var $bddvars = array("id", "parent", "lien", "ligne", "classement");
		
		function Dossier(){
			$this->Baseobj();	
		}

		function charger($id, $lang=1){
		
			return $this->getVars("select * from $this->table where id=\"$id\"");

		}

		function changer_classement($id, $type){
			
			$this->charger($id);
			$remplace = new Dossier();
			
			if($type == "M")
				$res = $remplace->getVars("select * from $this->table where parent=\"" . $this->parent. "\" and classement<" . $this->classement . " order by classement desc limit 0,1");
			
			else if($type == "D")
				$res = $remplace->getVars("select * from $this->table where parent=\"" . $this->parent. "\" and classement>" . $this->classement . " order by classement limit 0,1");
		
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
			$dossierdesc =  new Dossierdesc();
			$contenu =  new Contenu();
			
			$query = "select * from $this->table where parent=\"$this->id\"";
			$resul = mysql_query($query, $this->link);
			
			$query = "select * from $contenu->table where dossier=\"$this->id\"";
			$resul2 = mysql_query($query, $this->link);
			
			
			
			if( ! mysql_num_rows($resul) && ! mysql_num_rows($resul2)){
				$this->delete("delete from $this->table where id=\"$this->id\"");	
				$this->delete("delete from $dossierdesc->table where dossier=\"$this->id\"");	

			}
			
			else {
				return 0;
			
			}
			
			return 1;
		
		}

		
	}

?>