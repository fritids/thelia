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
	
	/* Substitutions de type panier */
		
	function substitclient($texte){

		$raison[1] = "Mme";
		$raison[2] = "Mlle";
		$raison[3] = "M";
 
        if($_SESSION['navig']->client->raison == 1) $raison1f="selected";
        else $raison1f="";
  
        if($_SESSION['navig']->client->raison == 2) $raison2f="selected";
        else $raison2f="";

        if($_SESSION['navig']->client->raison == 3) $raison3f="selected";
        else $raison3f="";


		$paysdesc = new Paysdesc();
		$paysdesc->charger($_SESSION['navig']->client->pays, $_SESSION["navig"]->lang);
		
		$texte = str_replace("#CLIENT_RAISON1F", $raison1f, $texte);
        $texte = str_replace("#CLIENT_RAISON2F", $raison2f, $texte);
        $texte = str_replace("#CLIENT_RAISON3F", $raison3f, $texte);
		
		if($_SESSION['navig']->client->id != "") $idclient = $_SESSION['navig']->client->id;
		else $idclient="0";

		$texte = str_replace("#CLIENT_IDPAYS", $_SESSION['navig']->client->pays, $texte);		
		$texte = str_replace("#CLIENT_ID", $idclient, $texte);
		$texte = str_replace("#CLIENT_REF", $_SESSION['navig']->client->ref, $texte);
		if(isset($raison[$_SESSION['navig']->client->raison])) $texte = str_replace("#CLIENT_RAISON", $raison[$_SESSION['navig']->client->raison], $texte);
		$texte = str_replace("#CLIENT_ENTREPRISE", $_SESSION['navig']->client->entreprise, $texte);
		$texte = str_replace("#CLIENT_SIRET", $_SESSION['navig']->client->siret, $texte);
		$texte = str_replace("#CLIENT_INTRACOM", $_SESSION['navig']->client->intracom, $texte);
		$texte = str_replace("#CLIENT_NOM", $_SESSION['navig']->client->nom, $texte);
		$texte = str_replace("#CLIENT_PRENOM", $_SESSION['navig']->client->prenom, $texte);
		$texte = str_replace("#CLIENT_ADRESSE1", $_SESSION['navig']->client->adresse1, $texte);
		$texte = str_replace("#CLIENT_ADRESSE2", $_SESSION['navig']->client->adresse2, $texte);
		$texte = str_replace("#CLIENT_ADRESSE3", $_SESSION['navig']->client->adresse3, $texte);
		$texte = str_replace("#CLIENT_CPOSTAL", $_SESSION['navig']->client->cpostal, $texte);
		$texte = str_replace("#CLIENT_VILLE", strtoupper($_SESSION['navig']->client->ville), $texte);
		$texte = str_replace("#CLIENT_PAYS", $paysdesc->titre, $texte);
		$texte = str_replace("#CLIENT_EMAIL", $_SESSION['navig']->client->email, $texte);
		$texte = str_replace("#CLIENT_TELFIXE", $_SESSION['navig']->client->telfixe, $texte);
		$texte = str_replace("#CLIENT_TELPORT", $_SESSION['navig']->client->telport, $texte);
		$texte = str_replace("#CLIENT_TYPE", $_SESSION['navig']->client->type, $texte);

		return $texte;
	
	}
	
?>