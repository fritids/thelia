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

	$total = $_SESSION['navig']->commande->total;

	$total *= 100;

	$transaction = urlencode($_SESSION['navig']->commande->transaction);

?>

<html>
<head>
<meta http-equiv="cache-control" content="no-cache">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="-1">
<title>
  Paiement Paybox
</title>
</head>
<body onload="document.getElementById('formpaybox').submit();">


<table align="center">

  <tr>

    <td>
	
	<form action="<?php echo $serveur; ?>" id="formpaybox" method="post">
 		<input type="hidden" name="PBX_MODE" value="<?php echo $mode; ?>"> 
 		<input type="hidden" name="PBX_SITE" value="<?php echo $site; ?>"> 
 		<input type="hidden" name="PBX_RANG" value="<?php echo $rang; ?>"> 
 		<input type="hidden" name="PBX_IDENTIFIANT" value="<?php echo $id; ?>"> 
 		<input type="hidden" name="PBX_TOTAL" value="<?php echo $total; ?>">
 		<input type="hidden" name="PBX_DEVISE" value="<?php echo $devise; ?>"> 
 		<input type="hidden" name="PBX_PORTEUR" value="<?php echo $_SESSION['navig']->client->email; ?>"> 
 		<input type="hidden" name="PBX_REFUSE" value="<?php echo $retourko; ?>"> 
 		<input type="hidden" name="PBX_ANNULE" value="<?php echo $retourko; ?>"> 
 		<input type="hidden" name="PBX_CMD" value="<?php echo $transaction; ?>"> 
 		<input type="hidden" name="PBX_RETOUR" value="montant:M;ref:R;auto:A;trans:T;erreur:E"> 
 		<input type="hidden" name="PBX_EFFECTUE" value="<?php echo $retourok; ?>"> 
 		
 		
 		
		<input type="image" src="<?php echo $urlsite->valeur . "/client/plugins/paybox/logo.jpg" ?>" />
	</form>
	
	</td>
  </tr>
</table>
	
</body>
</html>
