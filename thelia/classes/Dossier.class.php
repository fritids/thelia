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
	include_once(realpath(dirname(__FILE__)) . "/Image.class.php");
	include_once(realpath(dirname(__FILE__)) . "/Document.class.php");
	
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

            if($this->id == "")
                    return 0;

			if ($this->id == 0 || $this->id == "") return;

			$dossierdesc =  new Dossierdesc();
			$contenu =  new Contenu();
			
			$query = "select * from $this->table where parent=\"$this->id\"";
			$resul = mysql_query($query, $this->link);
			
			$query = "select * from $contenu->table where dossier=\"$this->id\"";
			$resul2 = mysql_query($query, $this->link);
			
			
			
			if( ! mysql_num_rows($resul) && ! mysql_num_rows($resul2)){
				
				$image = new Image();

				$query = "select * from $image->table where dossier=\"" . $this->id . "\"";
				$resul = mysql_query($query, $image->link);
				while($row = mysql_fetch_object($resul)){
					$tmp = new Image();
					$tmp->charger($row->id);
					$tmp->supprimer();

				}

				$document = new Document();

				$query = "select * from $document->table where dossier=\"" . $this->id . "\"";
				$resul = mysql_query($query, $document->link);
				while($row = mysql_fetch_object($resul)){
					$tmp = new Document();
					$tmp->charger($row->id);
					$tmp->supprimer();

				}				
				$this->delete("delete from $this->table where id=\"$this->id\"");	
				$this->delete("delete from $dossierdesc->table where dossier=\"$this->id\"");	
				
					$queryclass = "select * from $this->table where parent=$this->parent order by classement";
					$resclass = mysql_query($queryclass);

					if(mysql_num_rows($resclass) > 0){
						$i=1;
						while($rowclass = mysql_fetch_object($resclass)){
							$dos = new Dossier();
							$dos->charger($rowclass->id);
							$dos->classement = $i;
							$dos->maj();
							$i++;
						}

					}

			}
			
			else {
				return 0;
			
			}
			
			return 1;
		
		}
		
		function nb_dos(){
			$contenu = new Contenu();
			$query = "select count(*) as nbdos from $contenu->table where dossier=\"" . $this->id . "\"";
			$res = mysql_query($query);
			
			return mysql_result($res,0,"nbdos");
		}
		
		function aenfant(){
			
			$query = "select count(*) as nb from $this->table where parent=\"" . $this->id . "\"";
			$resul = mysql_query($query, $this->link);
			if(mysql_result($resul, 0, "nb"))
				return 1;
			else
				return 0;		
		}

		
	}
?>