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

// Remplacement des parametres 

function gosaj($boucle, $param){	
	$boucle = $_SESSION['navig']->tabDiv[$boucle];
	$boucle = stripslashes($boucle);
	$boucle = ereg_replace("sthelia", "STHELIA", $boucle);	
	$boucle = ereg_replace("STHELIA", "THELIA", $boucle);	
	$boucle = ereg_replace("=#REMPLACER", "=\"#REMPLACER\"", $boucle);	

	$boucle = ereg_replace("<THELIA([^>]*)>", "<THELIA\\1>\n", $boucle);
	$boucle = ereg_replace("</THELIA", "\n</THELIA", $boucle);	
	
	$decLParam = explode("&", $param);

	for($i=0; $i<count($decLParam); $i++) 
		$decNParam[$i] = explode("=", $decLParam[$i]);
	
	for($i=0; $i<count($decNParam); $i++)
		if(isset($decNParam[$i][1])) $boucle = ereg_replace($decNParam[$i][0] . "=\"#REMPLACER\"", $decNParam[$i][0] . "=\"" . $decNParam[$i][1] ."\"", $boucle);		

	$boucle = analyse($boucle);	
	return $boucle; 

}

function ajoutsaj($ref){

	ajouter($ref);

}

function modifpasssaj($pass){
		$client = New Client();
		$client->charger_id($_SESSION['navig']->client->id);
		$client->motdepasse = $pass;
		$client->crypter();
		$client->maj();
}

function modifcoordsaj($raison, $nom, $prenom, $telfixe, $telport, $email, $adresse1, $adresse2, $adresse3, $cpostal, $ville, $pays){
			$client = New Client();
			$client->charger_id($_SESSION['navig']->client->id);

			$client->raison = $raison;
			$client->nom = $nom;
			$client->prenom = $prenom;
			$client->telfixe = $telfixe;
			$client->telport =$telport; 
			if( preg_match("/^[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]{2,}[.][a-zA-Z]{2,3}$/","$email1") 
				&& $email1==$email2 ) $client->email = $email1;
			$client->adresse1 = $adresse1;
			$client->adresse2 = $adresse2;
			$client->adresse3 = $adresse3;
			$client->cpostal = $cpostal;
			$client->ville = $ville;
			$client->pays = $pays;
			
			$client->maj();


			$_SESSION['navig']->client = $client;	

		
}
?>
