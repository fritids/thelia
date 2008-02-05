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

	class Commande extends Baseobj{

		var $id;
		var $client;
		var $adresse;
		var $date;
		var $datefact;
		var $ref;
		var $transaction;
		var $livraison;
		var $facture;
		var $transport;
		var $port;
		var $affilie;
		var $datelivraison;
		var $remise;
		var $colis;
		var $paiement;
		var $statut;
		var $table="commande";
		var $bddvars = array("id", "client", "adresse", "date", "datefact", "ref", "transaction", "livraison", "facture", "transport", "port", "affilie", "datelivraison", "remise", "colis", "paiement", "statut");

		function Commande(){
			$this->Baseobj();
		}

		function charger($id){
		
			return $this->getVars("select * from $this->table where id=\"$id\"");

		}

		function charger_ref($ref){
		
			return $this->getVars("select * from $this->table where ref=\"$ref\"");

		}

		function charger_trans($transaction){
	        $hier = date("Y-m-d H:i:s", mktime()-86400);
			return $this->getVars("select * from $this->table where transaction=\"$transaction\" and date>\"$hier\"");

		}
		
		function supprimer(){
			$venteprod = new Venteprod();
			$query = "delete from $venteprod->table where commande='" . $this->id . "'";
			$resul = mysql_query($query, $this->link);
			$this->delete();
		
		}
		
		function genfact(){
			
			if($this->facture) return 0;
			
			$this->datefact = date("Y-m-d");
			
			$query = "select max(facture) as mfact from $this->table";
			$resul = mysql_query($query, $this->link);
			
			if(mysql_result($resul, 0, "mfact")>0) $this->facture = mysql_result($resul, 0, "mfact") + 1;
			else $this->facture = 1000;			
		
		}
		
		
	}

?>