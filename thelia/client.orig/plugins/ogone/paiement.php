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
  Paiement Ogone
</title>
</head>
	
	
<body onload="document.getElementById('formogone').submit();">

<form action="<?php echo $serveur; ?>" id="formogone" action="post">
        <INPUT type="hidden" NAME="PSPID" value="<?php echo $pspid; ?>">
        <INPUT type="hidden" NAME="orderID" VALUE="<?php echo $_SESSION['navig']->commande->transaction; ?>">
        <INPUT type="hidden" NAME="amount" VALUE="<?php echo $total; ?>">
        <INPUT type="hidden" NAME="currency" VALUE="<?php echo $devise; ?>">
        <INPUT type="hidden" NAME="language" VALUE="<?php echo $langue; ?>">

        <INPUT type="hidden" NAME="TITLE" VALUE="<?php echo $nomsite->valeur; ?>">

        <INPUT type="hidden" NAME="LOGO" VALUE="logo.jpg">

        <INPUT type="hidden" NAME="accepturl" VALUE="<?php echo $retourok;?>">
        <INPUT type="hidden" NAME="declineurl" VALUE="<?php echo $retourko;?>">
        <INPUT type="hidden" NAME="exceptionurl" VALUE="<?php echo $retourko;?>">
        <INPUT type="hidden" NAME="cancelurl" VALUE="<?php echo $retourok;?>">

<input type="submit" value="Acces au Paiement" id="envoyer" name="Envoyer">
        </form>

	
</body>
</html>
