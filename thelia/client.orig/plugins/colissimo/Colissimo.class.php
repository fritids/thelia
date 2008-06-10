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

	include_once(realpath(dirname(__FILE__)) . "/../../../classes/PluginsTransports.class.php");
	include_once(realpath(dirname(__FILE__)) . "/../../../classes/Message.class.php");
	include_once(realpath(dirname(__FILE__)) . "/../../../classes/Messagedesc.class.php");
	include_once(realpath(dirname(__FILE__)) . "/../../../classes/Variable.class.php");

	class Colissimo extends PluginsTransports{


		function Colissimo(){
			$this->PluginsTransports("colissimo");
		}
		
		function init(){
			$this->ajout_desc("Colissimo", "Colissimo", "", 1);
			$test = new Message();
			if(! $test->charger("colissimo")){
				$message = new Message();
				$message->nom = "colissimo";
				$lastid = $message->add();

				$messagedesc = new Messagedesc();
				$messagedesc->message = $lastid;
				$messagedesc->lang = 1;
				$messagedesc->titre = "Colissimo";
				$messagedesc->description = "__RAISON__ __NOM__ __PRENOM__,\n\nNous vous remercions de votre commande sur notre site __URLSITE__\n\nUn colis concernant votre commande __COMMANDE__ du __DATE__ __HEURE__ a quitté nos entrepôts pour être pris en charge par La Poste le __DATEDJ__.\n\nSon numéro de suivi est le suivant : __COLIS__\nIl vous permet de suivre votre colis en ligne sur le site de La Poste : www.coliposte.net\nIl vous sera, par ailleurs, très utile si vous étiez absent au moment de la livraison de votre colis : en fournissant ce numéro de Colissimo Suivi, vous pourrez retirer votre colis dans le bureau de Poste le plus proche.\n\nATTENTION ! Si vous ne trouvez pas l'avis de passage normalement déposé dans votre boîte aux lettres au bout de 48 Heures jours ouvrables, n'hésitez pas à aller le réclamer à votre bureau de Poste, muni de votre numéro de Colissimo Suivi.\n\nNous restons à votre disposition pour toute information complémentaire.\nCordialement";
				$messagedesc->add();

			}
		}
		
		function calcule(){
			if($this->poids<=0.5) return 6;
			else if($this->poids>0.5 && $this->poids<=1) return 6.50;
			else if($this->poids>1 && $this->poids<=2) return 7;
			else if($this->poids>2 && $this->poids<=3) return 8;
			else if($this->poids>3 && $this->poids<=5) return 9;
			else if($this->poids>5 && $this->poids<=7) return 10;
			else if($this->poids>7 && $this->poids<=10) return 12;
			else if($this->poids>10 && $this->poids<=15) return 14;
			else if($this->poids>15 && $this->poids<=30) return 20;
			else if($this->poids>30) return 20;
		
		}
			
		function statut($commande){

			if($commande->statut == "4"){

				if(! $commande->colis)
					return;

				$message = new Message();
				$message->charger("colissimo");

				$messagedesc = new Messagedesc();
				$messagedesc->charger($message->id);

				$client = new Client();
				$client->charger_id($commande->client);

				if($client->raison == "1")
					$raison = "Madame";
				else
					if($client->raison == "2")
						$raison = "Mademoiselle";
				else
					if($client->raison == "3")
						$raison = "Monsieur";

				$urlsite = new Variable();
				$urlsite->charger("urlsite");

				$emailcontact = new Variable();
				$emailcontact->charger("emailcontact");

          		$jour = substr($commande->date, 8, 2);
		        $mois = substr($commande->date, 5, 2);
		        $annee = substr($commande->date, 0, 4);
                $heure = substr($commande->date, 11, 2);
                $minute = substr($commande->date, 14, 2);
                $seconde = substr($commande->date, 17, 2);

				$messagedesc->description = str_replace("__RAISON__", "$raison", $messagedesc->description);
				$messagedesc->description = str_replace("__NOM__", $client->nom, $messagedesc->description);
				$messagedesc->description = str_replace("__PRENOM__", $client->prenom, $messagedesc->description);
				$messagedesc->description = str_replace("__URLSITE__", $urlsite->valeur, $messagedesc->description);
				$messagedesc->description = str_replace("__COMMANDE__", $commande->ref, $messagedesc->description);
				$messagedesc->description = str_replace("__DATE__", $jour . "/" . $mois . "/" . $annee, $messagedesc->description);
				$messagedesc->description = str_replace("__HEURE__", $heure . ":" . $minute . ":" . $seconde, $messagedesc->description);
				$messagedesc->description = str_replace("__DATEDJ__", date("d") . "/" . date("m") . "/" . date("Y"), $messagedesc->description);
				$messagedesc->description = str_replace("__COLIS__", $commande->colis, $messagedesc->description);
				mail($client->email, $messagedesc->titre, $messagedesc->description, "From: " . $emailcontact->valeur);

			}

		}

	}

?>
