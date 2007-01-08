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

	include_once("Panier.class.php");
	include_once("Client.class.php");
	include_once("Commande.class.php");
	include_once("Promo.class.php");
	
	// Définition de la navigation
	
	class Navigation {

		var $client;
		var $formcli;
		var $panier;
		var $urlprec;
		var $urlpageret;
		var $connecte=0;
		var $nouveau=0;
		var $paiement=0;
		var $adresse=0;
		var $commande;
		var $promo;
		var $pagecur;
		var $affilie;
		var $lang;
		
		var $tabDiv;
		
		function Navigation(){
			$this->panier = new Panier();
			$this->client = new Client();
			$this->formcli = new Client();
			$this->commande = new Commande();
			$this->promo = new Promo();
			$this->pagecur = 0;
		}


		
	}


?>
