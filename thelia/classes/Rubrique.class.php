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
	include_once(realpath(dirname(__FILE__)) . "/BaseobjCacheable.class.php");
	include_once(realpath(dirname(__FILE__)) . "/Rubriquedesc.class.php");
	include_once(realpath(dirname(__FILE__)) . "/Produit.class.php");
	include_once(realpath(dirname(__FILE__)) . "/Image.class.php");
	include_once(realpath(dirname(__FILE__)) . "/Document.class.php");
		
	class Rubrique extends BaseobjCacheable{

		var $id;
		var $parent;
		var $lien;
		var $ligne;
		var $classement;
		
		var $table="rubrique";
		var $bddvars = array("id", "parent", "lien", "ligne", "classement");
		
		function Rubrique(){
			$this->Baseobj();	
		}

		function charger(){
			$id = func_get_arg(0);
			
			return $this->getVars("select * from $this->table where id=\"$id\"");

		}

		function changer_classement($id, $type){
			
			$this->charger($id);
			$remplace = new Rubrique();
			
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

			if ($this->id == 0 || $this->id == "") return;

			$rubriquedesc =  new Rubriquedesc();
			$produit =  new Produit();
			
			$query = "select * from $this->table where parent=\"$this->id\"";
			$resul = mysql_query($query, $this->link);
			
			$query = "select * from $produit->table where rubrique=\"$this->id\"";
			$resul2 = mysql_query($query, $this->link);
			
			
			
			if( ! mysql_num_rows($resul) && ! mysql_num_rows($resul2)){
				
				$image = new Image();

				$query = "select * from $image->table where rubrique=\"" . $this->id . "\"";
				$resul = mysql_query($query, $image->link);
				while($row = mysql_fetch_object($resul)){
					$tmp = new Image();
					$tmp->charger($row->id);
					$tmp->supprimer();

				}

				$document = new Document();

				$query = "select * from $document->table where rubrique=\"" . $this->id . "\"";
				$resul = mysql_query($query, $document->link);
				while($row = mysql_fetch_object($resul)){
					$tmp = new Document();
					$tmp->charger($row->id);
					$tmp->supprimer();

				}
				
				$this->delete("delete from $this->table where id=\"$this->id\"");	
				$this->delete("delete from $rubriquedesc->table where rubrique=\"$this->id\"");
				
				$queryclass = "select * from $this->table where parent=$this->parent order by classement";
				$resclass = mysql_query($queryclass);
				
				if(mysql_num_rows($resclass)>0){
					$i = 1;
					while($rowclass = mysql_fetch_object($resclass)){
						$rub = new Rubrique();
						$rub->charger($rowclass->id);
						$rub->classement = $i;
						$rub->maj();
						$i++;
					}
				}	

			}
			
			else {
				return 0;
		
			}
			
		
			return 1;
		
		}

		function nbprod(){
			$prod = new Produit();
			$query = "select count(*) as nb from $prod->table where rubrique=\"" . $this->id . "\"";
			$resul = mysql_query($query, $this->link);
			
			return mysql_result($resul, 0, "nb");
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