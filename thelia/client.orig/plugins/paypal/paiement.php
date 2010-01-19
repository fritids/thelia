<?php
/*****************************************************************************
 *
 * Auteur   : Bruno | atnos.com (contact: contact@atnos.com)
 * Version  : 0.1
 * Date     : 29/07/2007
 *
 * Copyright (C) 2007 Bruno PERLES
 * 
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 *****************************************************************************/

	include_once(realpath(dirname(__FILE__)) . "/../../../classes/Navigation.class.php");
	include_once(realpath(dirname(__FILE__)) . "/../../../classes/Venteprod.class.php");
	include_once(realpath(dirname(__FILE__)) . "/config.php");
		
	session_start();

	$total = 0;

    $total = $_SESSION['navig']->commande->total;
    


?>

<html>
<head>
</head>
<body onload="document.getElementById('formpaypal').submit()">
<?php
//"
// Référence
$Reference_Cde = urlencode($_SESSION['navig']->commande->transaction);

// Montant
$Montant          = $total;
$Montant		  -= $_SESSION["navig"]->commande->remise;
//echo $Montant; exit;

?>

	<br />
	

	
	<form action="<?php echo $serveur; ?>" id="formpaypal" method="post">
		
		<input type="hidden" name="upload" value="1">
		<input type="hidden" name="first_name" value="<?php echo $_SESSION["navig"]->client->prenom; ?>" />
		<input type="hidden" name="last_name" value="<?php echo $_SESSION["navig"]->client->nom; ?>" />
		<input type="hidden" name="address1" value="<?php echo $_SESSION["navig"]->client->adresse1; ?>" />
		<?php
		if($_SESSION["navig"]->client->adresse2 != ""){
		?>
		<input type="hidden" name="address2" value="<?php echo $_SESSION["navig"]->client->adresse2; ?>" />
		<?php
		}
		?>
		<input type="hidden" name="city" value="<?php echo $_SESSION["navig"]->client->ville; ?>" />
		<input type="hidden" name="zip" value="<?php echo $_SESSION["navig"]->client->cpostal; ?>" />
		<input type="hidden" name="amount" value="<?php echo $Montant; ?>" />
		<input type="hidden" name="email" value="<?php echo $_SESSION["navig"]->client->email; ?>">
		<input type="hidden" name="shipping_1" value="<?php echo $_SESSION["navig"]->commande->port; ?>" />
		<?php
		if($_SESSION["navig"]->commande->remise == 0){
			$venteprod = new Venteprod();
			$query = "select * from $venteprod->table where commande=".$_SESSION["navig"]->commande->id;
			$resul = mysql_query($query);
			$i=0;
			while($row = mysql_fetch_object($resul)){ 
				$i++;
				?>
				<input type="hidden" name="item_name_<?php echo($i); ?>" value="<?php echo trim($row->titre); ?>" />
				<input type="hidden" name="amount_<?php echo($i); ?>" value="<?php echo $row->prixu; ?>" />
				<input type="hidden" name="quantity_<?php echo($i); ?>" value="<?php echo $row->quantite; ?>" />
		
			<?php
			}
		}
		else{
		?>
			<input type="hidden" name="item_name_1" value="Mon panier" />
			<input type="hidden" name="amount_1" value="<?php echo $Montant; ?>" />
			<input type="hidden" name="quantity_1" value="1" />
		<?php
		}
		?>
		
		<input type="hidden" name="business" value="<?php echo $compte_paypal; ?>" />
		<input type="hidden" name="receiver_email" value="<?php echo $compte_paypal; ?>" />
		<input type="hidden" name="cmd" value="_cart" />
		<input type="hidden" name="currency_code" value="<?php echo $Devise; ?>" />
		<input type="hidden" name="payer_id" value="<?php echo $_SESSION["navig"]->client->id; ?>" />
		<input type="hidden" name="payer_email" value="<?php echo $_SESSION["navig"]->client->email; ?>" />
		<input type="hidden" name="return" value="<?php echo $retourok; ?>" />
		<input type="hidden" name="notify_url" value="<?php echo $confirm; ?>" />
		<input type="hidden" name="cancel_return" value="<?php echo $retournok; ?>" />
		<input type="hidden" name="invoice" value="<?php echo $Reference_Cde; ?>" />
		
	</form>
	

	
</body>
</html>
