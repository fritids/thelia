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
	include_once(realpath(dirname(__FILE__)) . "/PluginsClassiques.class.php");
	include_once(realpath(dirname(__FILE__)) . "/Variable.class.php");
	include_once(realpath(dirname(__FILE__)) . "/Paysdesc.class.php");
	include_once(realpath(dirname(__FILE__)) . "/Client.class.php");
	include_once(realpath(dirname(__FILE__)) . "/Pays.class.php");
	include_once(realpath(dirname(__FILE__)) . "/Paysdesc.class.php");
	include_once(realpath(dirname(__FILE__)) . "/Modules.class.php");
	include_once(realpath(dirname(__FILE__)) . "/Modulesdesc.class.php");
	include_once(realpath(dirname(__FILE__)) . "/Adresse.class.php");
	include_once(realpath(dirname(__FILE__)) . "/Produitdesc.class.php");
	
	class PluginsPaiements extends PluginsClassiques{

		
		function PluginsPaiements($nom=""){
			$this->PluginsClassiques($nom);			
		}
		

		function paiement($commande){
			
			
		}

		function getDevise(){
							
			if($_SESSION['navig']->lang == "")
				$lang="1";
			else $lang=$_SESSION['navig']->lang;
			
			$modulesdesc = new Modulesdesc();
			$modulesdesc->charger($this->nom_plugin);
			
			return $modulesdesc->devise;		
		}
		
 		function mail($commande){
	
			$sujet=""; 
			$corps="";

			/* Message client */
			$msg = new Message();
			$msg->charger("sujetcommande");
			$msgdesc = new Messagedesc();
			$msgdesc->charger($msg->id);
			$sujet = $msgdesc->description . " " . $commande->ref;
			$msg->charger("corpscommande1");
			$msgdesc = new Messagedesc();                
			$msgdesc->charger($msg->id);
			$corps = $msgdesc->description;
			$corps = $this->substitmail($corps, $commande);

			/* Message admin */
        	$msg->charger("corpscommande2");
        	$msgdesc = new Messagedesc();
        	$msgdesc->charger($msg->id);
			$corps2 = $msgdesc->description;
		
			$emailcontact = new Variable();
			$emailcontact->charger("emailcontact");	
			$corp2 = $this->substitmail($corps2, $commande);

			mail($_SESSION['navig']->client->email , "$sujet", "$corps", "From: $emailcontact->valeur");	
			mail($emailcontact->valeur , "$sujet", "$corps2", "From: $emailcontact->valeur");	
			
		}
		
		function substitmail($corps, $commande ){

		  	$jour = substr($commande->date, 8, 2);
  			$mois = substr($commande->date, 5, 2);
  			$annee = substr($commande->date, 0, 4);
  		
  			$heure = substr($commande->date, 11, 2);
  			$minute = substr($commande->date, 14, 2);
  			$seconde = substr($commande->date, 17, 2);

			$client = new Client();
			$client->charger_id($commande->client);
			
			$paiement = new Modules();
			$paiement->charger_id($commande->paiement);
			$paiementdesc = new Modulesdesc();
			$paiementdesc->charger($paiement->nom, $_SESSION['navig']->lang);

			$transport = new Modules();
			$transport->charger_id($commande->transport);
			$transportdesc = new Modulesdesc();
			$transportdesc->charger($transport->nom, $_SESSION['navig']->lang);


			$total = $_SESSION['navig']->panier->total();
			$totcmdport = $commande->port + $total;
			
			if($commande->adresse){
				$adresse = new Adresse();
				$adresse->charger($commande->adresse);
				if($adresse->raison == "1")
					$raison = "Madame";
				else if($adresse->raison == "2")
					$raison = "Mademoiselle";
				else $raison = "Monsieur";
				$nom = $adresse->nom;
				$prenom = $adresse->prenom;
				$adresse1 = $adresse->adresse1;
				$adresse2 = $adresse->adresse2;
				$adresse3 =  $adresse->adresse3;
				$cpostal =  $adresse->cpostal;
				$ville = $adresse->ville; 
				$pays = new Paysdesc();
				$pays->charger($adresse->pays);
			}
			
			else{
				if($client->raison == "1")
					$raison = "Madame";
				else if($client->raison == "2")
					$raison = "Mademoiselle";
				else $raison = "Monsieur";
				$nom = $client->nom;
				$prenom = $client->prenom;
				$adresse1 = $client->adresse1;
				$adresse2 = $client->adresse2;
				$adresse3 =  $client->adresse3;
				$cpostal =  $client->cpostal;
				$ville = $client->ville; 
				$pays = new Paysdesc();
				$pays->charger($client->pays);				
				
			}
			
			$urlsite = new Variable();
			$urlsite->charger("urlsite");
			
			$corps = str_replace("__COMMANDE_REF__", $commande->ref, $corps);
			$corps = str_replace("__COMMANDE_DATE__", $jour . "/" . $mois . "/" . $annee . " " . $heure . ":" . $minute, $corps);
			$corps = str_replace("__COMMANDE_TRANSACTION__", $commande->transaction, $corps);
			$corps = str_replace("__COMMANDE_PAIEMENT__", $paiementdesc->titre, $corps);
			$corps = str_replace("__COMMANDE_TOTALPORT__", $totcmdport, $corps);
			$corps = str_replace("__COMMANDE_TOTAL__", $total, $corps);
			$corps = str_replace("__COMMANDE_PORT__", $commande->port, $corps);
			$corps = str_replace("__COMMANDE_TRANSPORT__", $transportdesc->titre, $corps);
			$corps = str_replace("__COMMANDE_LIVRRAISON__", $raison, $corps);
			$corps = str_replace("__COMMANDE_LIVRNOM__",$nom, $corps);
			$corps = str_replace("__COMMANDE_LIVRPRENOM__", $prenom, $corps);
			$corps = str_replace("__COMMANDE_LIVRADRESSE1__", $adresse1, $corps);
			$corps = str_replace("__COMMANDE_LIVRADRESSE2__", $adresse2, $corps);
			$corps = str_replace("__COMMANDE_LIVRADRESSE3__", $adresse3, $corps);
			$corps = str_replace("__COMMANDE_LIVRCPOSTAL__", $cpostal, $corps);
			$corps = str_replace("__COMMANDE_LIVRVILLE__", $ville, $corps);
			$corps = str_replace("__COMMANDE_LIVRPAYS__", $pays->titre, $corps);

			$corps = str_replace("__URLSITE__", $urlsite->valeur, $corps);

			
			if($client->raison == "1")
				$raison = "Madame";
			else if($client->raison == "2")
				$raison = "Mademoiselle";
			else $raison = "Monsieur";
			$pays = new Paysdesc();
			$pays->charger($client->pays);
									
			$corps = str_replace("__CLIENT_REF__", $client->ref, $corps);
			$corps = str_replace("__CLIENT_RAISON__",$raison, $corps);
			$corps = str_replace("__CLIENT_ENTREPRISE__", $client->entreprise, $corps);
			$corps = str_replace("__CLIENT_SIRET__", $client->siret, $corps);
			$corps = str_replace("__CLIENT_FACTNOM__", $client->nom, $corps);
			$corps = str_replace("__CLIENT_FACTPRENOM__", $client->prenom, $corps);
			$corps = str_replace("__CLIENT_ADRESSE1__", $client->adresse1, $corps);
			$corps = str_replace("__CLIENT_ADRESSE2__", $client->adresse2, $corps);
			$corps = str_replace("__CLIENT_ADRESSE3__", $client->adresse3, $corps);
			$corps = str_replace("__CLIENT_CPOSTAL__", $client->cpostal, $corps);
			$corps = str_replace("__CLIENT_VILLE__", $client->ville, $corps);
			$corps = str_replace("__CLIENT_PAYS__", $pays->titre, $corps);
			$corps = str_replace("__CLIENT_EMAIL__", $client->email, $corps);
			
			preg_match("`<VENTEPROD>([^<]+)</VENTEPROD>`", $corps, $cut);
			$corps = str_replace("<VENTEPROD>", "", $corps);
			$corps = str_replace("</VENTEPROD>", "", $corps);
			
			$res="";

			
			for($i=0; $i<$_SESSION['navig']->panier->nbart; $i++){

				$produitdesc = new Produitdesc();
				$produitdesc->charger($_SESSION['navig']->panier->tabarticle[$i]->produit->id);

				if( ! $_SESSION['navig']->panier->tabarticle[$i]->produit->promo)
					$prix = $_SESSION['navig']->panier->tabarticle[$i]->produit->prix - ($_SESSION['navig']->panier->tabarticle[$i]->produit->prix * $_SESSION['navig']->client->pourcentage / 100);
				else $prix = $_SESSION['navig']->panier->tabarticle[$i]->produit->prix2 - ($_SESSION['navig']->panier->tabarticle[$i]->produit->prix2 * $_SESSION['navig']->client->pourcentage / 100);	

				$temp = str_replace("__VENTEPROD_TITRE__", $produitdesc->titre, $cut[1]);
				$temp =  str_replace("__VENTEPROD_QUANTITE__", $_SESSION['navig']->panier->tabarticle[$i]->quantite, $temp);
				$temp =  str_replace("__VENTEPROD_PRIXU__", $prix, $temp);
				$res .= $temp;
			
			}
			$corps = str_replace($cut[1], $res, $corps);
			return $corps;
			
		}
		
	}	
?>