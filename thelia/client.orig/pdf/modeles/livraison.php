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
	define('FPDF_FONTPATH','font/');
	require('../lib/fpdf/fpdf.php');
	require('../lib/fpdf/fpdi.php');



	include_once("../classes/Commande.class.php");
	include_once("../classes/Client.class.php");
	include_once("../classes/Venteprod.class.php");
	include_once("../classes/Produit.class.php");
	include_once("../classes/Venteadr.class.php");
	include_once("../classes/Modules.class.php");
	include_once("../classes/Zone.class.php");
	include_once("../classes/Pays.class.php");
	include_once("../classes/Paysdesc.class.php");

	class Livraison{
	
		function creer($ref){
	
			$commande = new Commande();
			$commande->charger_ref($ref);
	
  			$client = new Client();
  			$client->charger_id($commande->client);


  			$pays = new Pays();
  			$pays->charger($client->pays);	

  			$zone = new Zone();
  			$zone->charger($pays->zone);
	
			if($pays->lang)
				$lang=$pays->lang;
			else $lang="1";
	
			$pdf= new fpdi();
			$pdf->SetAutoPageBreak(false);
			$pagecount = $pdf->setSourceFile("../client/pdf/doc/" . "livraison" . $lang . ".pdf");
			$tplidx = $pdf->ImportPage(1);

		
			$pdf->addPage();
			$pdf->useTemplate($tplidx);
		
			$modules = new Modules();
			$modules->charger_id($commande->transport);
	

			$venteprod = new Venteprod();

  			$query = "select * from $venteprod->table where commande='$commande->id'";
  			$resul = mysql_query($query, $venteprod->link);
	
			$hauteur = 90;

  			while($row = mysql_fetch_object($resul)){
  		
  				$venteprod->charger($row->id);
  		 
  				$pdf->SetFont('Arial','',8);
				$pdf->SetXY(12,$hauteur);	
  	   		    $pdf->write(5,$venteprod->ref);
			
				$produit = new Produit();
				$produit->charger($venteprod->ref);
				
				$produitdesc = new Produitdesc();
				$produitdesc->charger($produit->id);

				$hauteursave = $hauteur;
		

        	    $chapo = $venteprod->titre;
		
				$chapo = ereg_replace("<br />", "\n", $chapo);
	     
	     	    $pdf->SetXY(42,$hauteursave);
				$pdf->MultiCell(60, 5, $chapo, 0, "L");
				$recy = $pdf->getY();
				$pdf->SetFont('Arial','',8);
				$pdf->SetXY(127,$hauteursave);		
				$pdf->SetFont('Arial','',8);
				$pdf->SetXY(192,$hauteursave);		
         	    $pdf->write(5,$venteprod->quantite); 
	
			$hauteur=$recy + 5;
			
       			
   			}
                  	
 
 
 			$venteprod = new Venteprod();
 
  	 
			$dateaff = substr($commande->date, 8, 2) . "/" . substr($commande->date, 5, 2) . "/" . substr($commande->date, 0, 4);

			$adrfact = new Venteadr();
			$adrfact->charger($commande->adrfact);
			
			$hauteur = 53;
	
			$pdf->SetFont('Arial','',8);
			$pdf->SetXY(122,$hauteur);	
  			$pdf->write(10, $adrfact->prenom . " " . $adrfact->nom);

			$hauteur+=3;
	
			$pdf->SetFont('Arial','',8);
			$pdf->SetXY(122,$hauteur);	
  			$pdf->write(10, $adrfact->adresse1);
	
			if($adrfact->adresse2) {
				$hauteur+=3;
			
				$pdf->SetFont('Arial','',8);
				$pdf->SetXY(122,$hauteur);	
 				$pdf->write(10, $adrfact->adresse2);
	
			}

			if($adrfact->adresse3) {
				$hauteur+=3;
	
				$pdf->SetFont('Arial','',8);
				$pdf->SetXY(122,$hauteur);	
  				$pdf->write(10, $adrfact->adresse3);
			}
	
			$hauteur+=3;
	
			$pdf->SetFont('Arial','',8);
			$pdf->SetXY(122,$hauteur);	
  			$pdf->write(10, $adrfact->cpostal . " " .  $adrfact->ville);

			$paysdesc = new Paysdesc();
			$paysdesc->charger($adrfact->pays);
		
			$hauteur += 3;
			$pdf->SetXY(122,$hauteur);	
 	 		$pdf->write(10, $paysdesc->titre);
 	 	
 	 	
			$adressecl = new Venteadr();
			$adressecl->charger($commande->adrlivr);
	
			$hauteur = 19;


			$pdf->SetFont('Arial','',8);
			$pdf->SetXY(122,$hauteur);	
  			$pdf->write(10, $adressecl->prenom . " " . $adressecl->nom);
	
			$hauteur+=3;
	
			$pdf->SetFont('Arial','',8);
			$pdf->SetXY(122,$hauteur);	
  			$pdf->write(10, $adressecl->adresse1);


			if($adressecl->adresse2) {
				$hauteur+=3;
	
				$pdf->SetFont('Arial','',8);
				$pdf->SetXY(122,$hauteur);	
 				$pdf->write(10, $adressecl->adresse2);
	
			}

			if($adressecl->adresse3) {
				$hauteur+=3;
	
				$pdf->SetFont('Arial','',8);
				$pdf->SetXY(122,$hauteur);	
  				$pdf->write(10, $adressecl->adresse3);
			}

			$paysdesc = new Paysdesc();
			$paysdesc->charger($adressecl->pays);
	
			$hauteur+=3;
	
			$pdf->SetFont('Arial','',8);
			$pdf->SetXY(122,$hauteur);	
 		 	$pdf->write(10, $adressecl->cpostal . " " .  $adressecl->ville);

			$hauteur+=3;
	
			$pdf->SetFont('Arial','',8);
			$pdf->SetXY(122,$hauteur);	
  			$pdf->write(10, $paysdesc->titre);
  		
			$pdf->SetFont('Arial','',8);
			$pdf->SetXY(74,72);	
  			$pdf->write(10,$commande->livraison);
  	
			$pdf->SetFont('Arial','',8);
			$pdf->SetXY(52,72);	
  			$pdf->write(10,$commande->facture);
  		
			$pdf->SetFont('Arial','',8);
			$pdf->SetXY(17,58);	
  			$pdf->write(10,$dateaff);
  	
  			$pdf->SetFont('Arial','',8);
			$pdf->SetXY(43,58);	
  			$pdf->write(10,$client->ref);	

  	    	$pdf->SetFont('Arial','',8);
	    	$pdf->SetXY(11,72);	  			
   		    $pdf->write(10,$commande->ref);

			$nom = $modules->nom;
			$nom[0] = strtoupper($nom[0]);

			include_once("../client/plugins/" . $modules->nom . "/$nom.class.php");
			$tmpobj = new $nom();
			
  			$pdf->SetFont('Arial','',8);
			$pdf->SetXY(47,242);	  			
   		    $pdf->write(10, $tmpobj->getChapo());

			$pdf->Output("livraison" . $commande->ref . ".pdf","I");	
			
			$pdf->closeParsers();

		
		}
	
	
	}
	
?>
