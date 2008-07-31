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

if((($_SESSION['navig']->client->id != $commande->client) || ($commande->statut<2)) && !$_SESSION["util"]->id)   exit;
echo $_SESSION["util"]->id;

?>
<?php


	include_once("../../classes/Commande.class.php");
	include_once("../../classes/Client.class.php");
	include_once("../../classes/Venteprod.class.php");
	include_once("../../classes/Produit.class.php");
	include_once("../../classes/Adresse.class.php");
	include_once("../../classes/Zone.class.php");
	include_once("../../classes/Pays.class.php");

	$commande = new Commande();
	$commande->charger_ref($_GET['ref']);

    $client = new Client();
  	$client->charger_id($commande->client);
  	
  	$pays = new Pays();
  	$pays->charger($client->pays);	

  	$zone = new Zone();
  	$zone->charger($pays->zone);

	include_once("../pdf/modeles/facture.php");
	
	$facture = new Facture();
	$facture->creer($_GET['ref']);
	
?>
