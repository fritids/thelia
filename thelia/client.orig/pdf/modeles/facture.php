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
	
	define('FPDF_FONTPATH', realpath(dirname(__FILE__)) . '/../../../lib/fpdf/font/');
	require('../../lib/fpdf/fpdf.php');
	require('../../lib/fpdf/fpdi.php');


	include_once("../../classes/Commande.class.php");
	include_once("../../classes/Client.class.php");
	include_once("../../classes/Venteprod.class.php");
	include_once("../../classes/Produit.class.php");
	include_once("../../classes/Modules.class.php");
	include_once("../../classes/Venteadr.class.php");
	include_once("../../classes/Modules.class.php");
	include_once("../../classes/Zone.class.php");
	include_once("../../classes/Pays.class.php");
	include_once("../../classes/Paysdesc.class.php");
	include_once("../../classes/Devise.class.php");
	
	class Facture{
	
		
	function creer($ref){	
	
		global $pdf, $client, $commande;
	
		if(!isset($mht)) $mht="";
		
		$commande = new Commande();
		$commande->charger_ref($ref);

 	 	$client = new Client();
  		$client->charger_id($commande->client);
  	
		$pdf= new fpdi();
		$pdf->SetAutoPageBreak(false);
		

		if($commande->lang)
			$lang=$commande->lang;
		else $lang="1";

		
		$pagecount = $pdf->setSourceFile("../pdf/doc/fpagesimple" . $lang . ".pdf");
		$pagesimple = $pdf->ImportPage(1);
		
		$pagecount = $pdf->setSourceFile("../pdf/doc/fpagecomplete" . $lang . ".pdf");
		$tplidx = $pdf->ImportPage(1);

		$nbpage = $this->comptPage();
			
		$pdf->addPage();
		
		if($nbpage == 1)
			$pdf->useTemplate($tplidx);
		else $pdf->useTemplate($pagesimple);		


		$istva = $this->entete();
	
		$venteprod = new Venteprod();
	
  		$query = "select * from $venteprod->table where commande='$commande->id' order by tva desc";
  		$resul = mysql_query($query, $venteprod->link);
	
		$hauteur = 85;
		
		$page = 1;
		
  		while($row = mysql_fetch_object($resul)){
  			
  			$venteprod->charger($row->id);
  		 
  			$pdf->SetFont('Arial','',8);
			$pdf->SetXY(12,$hauteur);	
  	 	    $pdf->write(5,$venteprod->ref);
		
			$produit = new Produit();
			$produit->charger($venteprod->ref);
				
			$produitdesc = new Produitdesc();
			
			$hauteursave = $hauteur;
	        
	        $pdf->SetFont('Arial','',8);
   	        $pdf->SetXY(42,$hauteursave);

			$chapo = $venteprod->titre;
		
			$chapo = ereg_replace("<br/>", "\n", $chapo);
	     
	        $pdf->SetXY(42,$hauteursave);
			$pdf->MultiCell(60, 5, $chapo, 0, "L");
			$recy = $pdf->getY();
			$pdf->SetFont('Arial','',8);

            $pdf->SetXY(167,$hauteursave);
            if($istva)
				$pdf->write(5,round($venteprod->tva, 2));		
			else 
				$pdf->write(5,"N/A");
			
			$pdf->SetXY(125,$hauteursave);
			if($istva)	
	 	    	$pdf->write(5,round($venteprod->prixu/($venteprod->tva/100+1), 2)); 
				
			else
				$pdf->write(5,round($venteprod->prixu, 2)); 
				
			$pdf->SetXY(148,$hauteursave);	
			if($istva)	
		   		$pdf->write(5,round($venteprod->prixu/($venteprod->tva/100+1), 2) * $venteprod->quantite); 
			else
				$pdf->write(5,round($venteprod->prixu * $venteprod->quantite, 2)); 
			$pdf->SetFont('Arial','',8);
			$pdf->SetXY(110,$hauteursave);		
            $pdf->write(5,$venteprod->quantite); 
			$pdf->SetFont('Arial','',8);
			$pdf->SetXY(184,$hauteursave);	
			
    	    $pdf->write(5, round($venteprod->quantite*$venteprod->prixu, 2)); 

			
			$hauteur=$recy + 5;

			if($hauteur > 220){
				$hauteur = 85;
				$hauteursave=$hauteur;
				$pdf->addpage();	
				$page++;
				if($nbpage != $page)
  					$pdf->useTemplate($pagesimple);
  				else $pdf->useTemplate($tplidx);
				$this->entete();
			}
 	  	}
   	
   		$hauteur+=5;
   		
   		   		
 		$venteprod = new Venteprod();
 
   		$query2 = "SELECT sum(prixu*quantite) as total FROM $venteprod->table where commande='$commande->id'";
  		$resul2 = mysql_query($query2, $venteprod->link);
  		$total = mysql_result($resul2, 0, "total");

		$modules = new Modules();
		$modules->charger_id($commande->paiement);

		$pourcremise = $commande->remise / $total * 100;
		
		$total += $commande->port;
	
		$total = round($total, 2);
	

		
		/* 19, 6 % */
                $query2 = "SELECT sum(prixu*quantite) as total FROM $venteprod->table where commande='$commande->id' and tva like '19.6'";
                $resul2 = mysql_query($query2, $venteprod->link);
                $total19 = mysql_result($resul2, 0, "total")/1.196;
				$tva19=$total19*19.6/100;
		
                $pdf->SetFont('Arial','',8);
                $pdf->SetXY(179,237.5);
                if($istva)
                	$pdf->write(10, round($total19 - $total19 * $pourcremise / 100,2));
 	  			else
					$pdf->write(10, "N/A");
					
                $pdf->SetFont('Arial','',8);
                $pdf->SetXY(179,242.5);
                if($istva)
					$pdf->write(10, round($tva19 - $tva19 * $pourcremise / 100, 2));
				else
					$pdf->write(10, "N/A");
					
                /* 5, 5 % */
                $query2 = "SELECT sum(prixu*quantite) as total FROM $venteprod->table where commande='$commande->id' and tva like '5.5'";
                $resul2 = mysql_query($query2, $venteprod->link);
                $total5 = mysql_result($resul2, 0, "total")/1.055;
                $tva5=$total5*5.5/100;

                $pdf->SetFont('Arial','',8);
                $pdf->SetXY(179,247.5);
                
 				if($istva)
					$pdf->write(10, round($total5 - $total5 * $pourcremise / 100,2));
				else
					$pdf->write(10, "N/A");
					
                $pdf->SetFont('Arial','',8);
                $pdf->SetXY(179,252.5);
                if($istva)
					$pdf->write(10, round($tva5 - $tva5 * $pourcremise / 100, 2));
				else
					$pdf->write(10, "N/A");
					
                /* 2, 1 % */
                $query2 = "SELECT sum(prixu*quantite) as total FROM $venteprod->table where commande='$commande->id' and tva like '2.1'";
                $resul2 = mysql_query($query2, $venteprod->link);
                $total2 = mysql_result($resul2, 0, "total")/1.021;
                $tva2=$total2*2.1/100;

                $pdf->SetFont('Arial','',8);
                $pdf->SetXY(179,257.5);
                if($istva)
                	$pdf->write(10, round($total2 - $total2 * $pourcremise / 100,2));
				else
					$pdf->write(10, "N/A");
					
                $pdf->SetFont('Arial','',8);
                $pdf->SetXY(179,262.5);
                if($istva)
                	$pdf->write(10, round($tva2 - $tva2 * $pourcremise/100, 2));
				else
					$pdf->write(10, "N/A");


		$pdf->SetFont('Arial','',8);
		$pdf->SetXY(179,232.5);	  			
		$pdf->write(10, round($pourcremise, 2) . " %");

		$mht = round($mht, 2);

		$pdf->SetXY(179,270);
   		$pdf->write(5,round($commande->port, 2));
        $pdf->SetFont('Arial','',8);
   
   		$devise = new Devise();
   		$devise->charger($commande->devise);

  		$pdf->SetFont('Arial','',8);
		$pdf->SetXY(179,272.5);	  			
    	$pdf->write(10,$total-$commande->remise . " " . $devise->symbole);

        $nom = $modules->nom;
        $nom[0] = strtoupper($nom[0]);

        include_once("../../client/plugins/" . $modules->nom . "/$nom.class.php");
        $tmpobj = new $nom();

  		$pdf->SetFont('Arial','',8);
		$pdf->SetXY(47,241);	  			
    	$pdf->write(10, $tmpobj->getTitre());

		
		$modules = new Modules();
		$modules->charger_id($commande->transport);

		$nom = $modules->nom;
		$nom[0] = strtoupper($nom[0]);

		include_once("../../client/plugins/" . $modules->nom . "/$nom.class.php");
		$tmpobj = new $nom();
		
  		$pdf->SetFont('Arial','',8);
		$pdf->SetXY(47,248);	  			
  	  	$pdf->write(10, $tmpobj->getTitre());

		$pdf->Output("facture" . $commande->facture . ".pdf","I");
		
		$pdf->closeParsers();

	}
	
	
	function entete(){
	
		global $pdf, $client, $commande;
		
		$hauteur = 48;
	
		$dateaff = substr($commande->datefact, 8, 2) . "/" . substr($commande->datefact, 5, 2) . "/" . substr($commande->datefact, 0, 4);

		$adrfact = new Venteadr();
		$adrfact->charger($commande->adrfact);
		
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
	
		$paysdesc = new Paysdesc();
		$paysdesc->charger($adrfact->pays);
		
		$pdf->SetFont('Arial','',8);
		$pdf->SetXY(122,$hauteur);	
 	 	$pdf->write(10, $adrfact->cpostal . " " .  $adrfact->ville);

		$hauteur += 3;
		$pdf->SetXY(122,$hauteur);	
 	 	$pdf->write(10, $paysdesc->titre);
		
        $hauteur+=3;

        $pdf->SetFont('Arial','',8);
        $pdf->SetXY(122,$hauteur);
        $pdf->write(10, $adrfact->tel);
	
		$adressecl = new Venteadr();
		$adressecl->charger($commande->adrlivr);
		
		$paysdesc = new Paysdesc();
		$paysdesc->charger($adressecl->pays);
	
		$hauteur = 14;


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
	
		$hauteur+=3;
	
		$pdf->SetFont('Arial','',8);
		$pdf->SetXY(122,$hauteur);	
  		$pdf->write(10, $adressecl->cpostal . " " .  $adressecl->ville);

		$hauteur+=3;
		
		$pdf->SetFont('Arial','',8);
		$pdf->SetXY(122,$hauteur);	
  		$pdf->write(10, $paysdesc->titre);

        $hauteur+=3;

        $pdf->SetFont('Arial','',8);
        $pdf->SetXY(122,$hauteur);
        $pdf->write(10, $adressecl->tel);

		$pdf->SetFont('Arial','',8);
		$pdf->SetXY(52,52);	
  		$pdf->write(10,$commande->facture);
  	
		$pdf->SetFont('Arial','',8);
		$pdf->SetXY(49,67);	
  		$pdf->write(10,$dateaff);
  	
  		$pdf->SetFont('Arial','',8);
		$pdf->SetXY(75,67);	
  		$pdf->write(10,$client->ref);	

 	 	$pdf->SetFont('Arial','',8);
		$pdf->SetXY(12,67);	  			
    	$pdf->write(10,$commande->ref);
	
	$pays  = new Pays();
	$pays->charger($adressecl->pays);
	return $pays->tva; 

	}


function comptPage(){
	
		global $commande;
		
		$page = 1;
	
		$pdf= new fpdi();
		$pdf->SetAutoPageBreak(false);

		$venteprod = new Venteprod();
	
  		$query = "select * from $venteprod->table where commande='$commande->id'";
  		$resul = mysql_query($query, $venteprod->link);
	
		$hauteur = 85;

  		while($row = mysql_fetch_object($resul)){
  		
  			$venteprod->charger($row->id);
  		 
  			$pdf->SetFont('Arial','',8);
			$pdf->SetXY(12,$hauteur);	
  	 	    $pdf->write(5,$venteprod->ref);
		
			$produit = new Produit();
			$produit->charger($venteprod->ref);
				
			$produitdesc = new Produitdesc();
			
			$hauteursave = $hauteur;
	        
	        $pdf->SetFont('Arial','',8);
   	        $pdf->SetXY(42,$hauteursave);

            $chapo = $venteprod->titre;
		
			$chapo = ereg_replace("<br/>", "\n", $chapo);
	     
	        $pdf->SetXY(42,$hauteursave);
			$pdf->MultiCell(60, 5, $chapo, 0, "L");
			$recy = $pdf->getY();
			$pdf->SetFont('Arial','',8);
			$pdf->SetXY(127,$hauteursave);		
	 	    $pdf->write(5,round($venteprod->prixu/1.196, 2)); 
			$pdf->SetXY(149,$hauteursave);		
		    $pdf->write(5,$venteprod->prixu); 
			$pdf->SetFont('Arial','',8);
			$pdf->SetXY(170,$hauteursave);		
            $pdf->write(5,$venteprod->quantite); 
			$pdf->SetFont('Arial','',8);
			$pdf->SetXY(183,$hauteursave);		
    	    $pdf->write(5, round(($venteprod->quantite*$venteprod->prixu) / 1.196, 2)); 

			$hauteur=$recy + 5;

			if($hauteur > 220){
				$hauteur = 85;
				$hauteursave=$hauteur;
				$pdf->addpage();	
		
				$page++;
			}
 	  	}

	return $page;

	}

}
?>