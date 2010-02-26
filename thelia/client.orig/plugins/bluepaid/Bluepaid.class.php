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

	include_once(realpath(dirname(__FILE__)) . "/../../../classes/PluginsPaiements.class.php");
	
	class Bluepaid extends PluginsPaiements{

		var $id;
		var $pays;
		var $alpha3;
		var $alpha2;
		var $code;

		var $defalqcmd = 0;
		
		var $table = "bluepaid_pays";
		
		var $bddvars=array("id", "pays", "alpha3", "alpha2", "code");
        
		function init(){
			$this->ajout_desc("CB", "CB", "", 1);
			
			$cnx = new Cnx();
			$sql = file_get_contents(realpath(dirname(__FILE__)) . "/bluepaid.sql");
			$sql = str_replace(";',", "-CODE-", $sql);

			$tab = explode(";", $sql);

			for($i=0; $i<count($tab); $i++)
				mysql_query(str_replace("-CODE-", ";',", $tab[$i]), $cnx->link);	
		}

		function Bluepaid(){
			$this->PluginsPaiements("bluepaid");
		}
		
		function charger($pays){
			return $this->getVars("select * from $this->table where pays=\"$pays\"");	
		}
		
		function paiement($commande){

			header("Location: " . "client/plugins/bluepaid/paiement.php");			
		}
	
	}

?>