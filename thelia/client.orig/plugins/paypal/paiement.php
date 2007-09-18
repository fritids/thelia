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
	include_once(realpath(dirname(__FILE__)) . "/config.php");
		
	session_start();

	$total = 0;

	$total = $_SESSION['navig']->panier->total() + $_SESSION['navig']->commande->port;
	$total -= $_SESSION['navig']->commande->remise;
	$total = round($total, 2);

	if($total<$_SESSION['navig']->commande->port)
		$total = $_SESSION['navig']->commande->port;
?>

<html>
<head>
<meta http-equiv="cache-control" content="no-cache">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="-1">
<title>
  Paiement PayPal
</title>
</head>
<body onload="document.getElementById('formpaypal').submit();">
<?php

// Référence
$Reference_Cde = urlencode($_SESSION['navig']->commande->transaction);

// Montant
$Montant          = $total;

?>

	<br />
	
<table align="center">

  <tr>

    <td>
	
	<form action="<?php echo $serveur; ?>" id="formpaypal" method="post">
		<input type="hidden" name="cmd" value="_xclick">
		<input type="hidden" name="business" value="<?php echo $compte_paypal; ?>">
		<input type="hidden" name="item_name" value="<?php echo $Reference_Cde; ?>">
		<input type="hidden" name="amount" value="<?php echo $Montant; ?>">
		<input type="hidden" name="no_shipping" value="1">
		<input type="hidden" name="return" value="<?php echo $retourok; ?>">
		<input type="hidden" name="cancel_return" value="<?php echo $retournok; ?>">
		<input type="hidden" name="no_note" value="1">
		<input type="hidden" name="currency_code" value="<?php echo $Devise; ?>">
		<input type="hidden" name="lc" value="<?php echo $Code_Langue; ?>">
		<input type="hidden" name="bn" value="PP-BuyNowBF">
		<input type="hidden" name="notify_url" value="<?php echo $confirm; ?>">
		<input type="image" src="https://www.paypal.com/fr_FR/i/btn/x-click-but02.gif" border="0" name="submit" alt="Effectuez vos paiements via PayPal : une solution rapide, gratuite et sécurisée">
		<img alt="" border="0" src="https://www.paypal.com/fr_FR/i/scr/pixel.gif" width="1" height="1">
	</form>
	
	</td>
  </tr>
</table>
	
</body>
</html>
