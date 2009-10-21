<?php
/*****************************************************************************
 *
 * Auteur   : Bolo | wexpay.com (contact: infos_web@wexpay.com)
 * Version  : 0.1
 * Date     : 22/10/2007
 *
 * Copyright (C) 2007 Bolo Michelin
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

	$total = $_SESSION['navig']->commande->total;

	$total *= 100;
?>

<html>
<head>
<meta http-equiv="cache-control" content="no-cache">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="-1">
<title>
  Paiement weXpay
</title>
</head>
<body onload="document.getElementById('formwexpay').submit();">
	
<table align="center">

  <tr>

    <td>
	
	<form action="<?php echo $serveur; ?>" id="formwexpay" method="post">
		<input type="hidden" name="merchant_id" value="<?php echo $id_marchand; ?>">
		<input type="hidden" name="ref_order" value="<?php echo $_SESSION['navig']->commande->transaction; ?>">
		<input type="hidden" name="amount" value="<?php echo $total; ?>">
		<input type="image" src="logo.png" border="0" name="submit" alt="Effectuez vos paiements via weXpay : une solution rapide, gratuite et sécurisée">
		<img alt="" border="0" src="logo.png" width="1" height="1">
	</form>
	
	</td>
  </tr>
</table>
	
</body>
</html>
