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
foreach ($_POST as $key => $value) $$key = $value;
foreach ($_GET as $key => $value) $$key = $value;
?>
<?php

include_once("../../classes/Navigation.class.php");
include("../../classes/Administrateur.class.php");

session_start();

?>
<?php

if((($_SESSION['navig']->client->id != $commande->client) || ($commande->statut<2)) && !$_SESSION["util"]->id)   exit;

?>
<?php
	define('FPDF_FONTPATH','font/');
	require('../../lib/fpdf/fpdf.php');
	require('../../lib/fpdf/fpdi.php');


	include_once("../../classes/Commande.class.php");
	include_once("../../classes/Client.class.php");

	$commande = new Commande();
	$commande->charger_ref($ref);


		$pdf= new fpdi();
		$pdf->SetAutoPageBreak(false);
		$pagecount = $pdf->setSourceFile("../../commande/" . $ref . ".pdf");
		
		for($i = 0; $i<$pagecount; $i++){
			$tplidx = $pdf->ImportPage($i+1);
			$pdf->addPage();
			$pdf->useTemplate($tplidx);

		}		
	
		$pdf->Output("../../commande/fichier.pdf","I");

		$pdf->closeParsers();

	
?>