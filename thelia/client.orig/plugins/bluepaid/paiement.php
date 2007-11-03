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
	include_once(realpath(dirname(__FILE__)) . "/../../../classes/Navigation.class.php");
	include_once(realpath(dirname(__FILE__)) . "/config.php");
		
	session_start();

	$total = 0;

	$total = $_SESSION['navig']->panier->total() + $_SESSION['navig']->commande->port;
	$total -= $_SESSION['navig']->commande->remise;
	$total = round($total, 2);

	$transaction = urlencode($_SESSION['navig']->commande->transaction);

?>

<html>
<head>
<meta http-equiv="cache-control" content="no-cache">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="-1">
<title>
  Paiement Bluepaid
</title>
</head>
<body onload="document.getElementById('formbluepaid').submit();">


<table align="center">

  <tr>

    <td>
	
	<form action="<?php echo $serveur; ?>" id="formbluepaid" method="post">
 		<input type="hidden" name="id_boutique" value="<?php echo $id_boutique; ?>"> 
 		<input type="hidden" name="id_client" value="<?php echo $transaction; ?>"> 
 		<input type="hidden" name="montant" value="<?php echo $total; ?>"> 
 		<input type="hidden" name="devise" value="<?php echo $Devise; ?>">
 		<input type="hidden" name="langue" value="<?php echo $Code_Langue; ?>"> 
		<input type="hidden" name="email_client" value="<?php echo $_SESSION['navig']->client->email; ?>"> 
		<input type="image" src="<?php echo $urlsite->valeur . "/client/plugins/bluepaid/logo.jpg" ?>" />
	</form>
	
	</td>
  </tr>
</table>
	
</body>
</html>
