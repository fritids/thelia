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

	include_once(realpath(dirname(__FILE__)) . "/../../../fonctions/divers.php");
    include_once(realpath(dirname(__FILE__)) . "/../../../classes/PluginsClassiques.class.php");
    include_once(realpath(dirname(__FILE__)) . "/../../../classes/Produit.class.php");
    include_once(realpath(dirname(__FILE__)) . "/../../../classes/Produitdesc.class.php");
    include_once(realpath(dirname(__FILE__)) . "/../../../classes/Caracteristique.class.php");
    include_once(realpath(dirname(__FILE__)) . "/../../../classes/Caracteristiquedesc.class.php");
    include_once(realpath(dirname(__FILE__)) . "/../../../classes/Caracdisp.class.php");
    include_once(realpath(dirname(__FILE__)) . "/../../../classes/Caracdispdesc.class.php");
    include_once(realpath(dirname(__FILE__)) . "/../../../classes/Caracval.class.php");
    include_once(realpath(dirname(__FILE__)) . "/../../../classes/Rubcaracteristique.class.php");
    include_once(realpath(dirname(__FILE__)) . "/../../../classes/Rubrique.class.php");
    include_once(realpath(dirname(__FILE__)) . "/../../../classes/Variable.class.php");


	class Prodprixmult extends PluginsClassiques{


		function Prodprixmult(){
			$this->PluginsClassiques();
		}


		function init(){
			
			$principal = new Variable();
			if($principal->charger("principal"))
				return;
				
				$caracteristique = new Caracteristique();
				$caracteristique->affiche = 0;
				
				$query_carac = "select max(classement) as maxclassement from $caracteristique->table";
				$resul_carac = mysql_query($query_carac, $caracteristique->link);

				$maxclassement = mysql_result($resul_carac, 0, "maxclassement");
				
				$caracteristique->classement =   $maxclassement + 1;
				$idcarac = $caracteristique->add();

				$principal = new Variable();
				$principal->nom = "principal";
				$principal->valeur = $idcarac;
				$principal->protege = "1";
				$principal->add();
					
				$caracteristiquedesc = new Caracteristiquedesc();
				$caracteristiquedesc->titre = "principal";
				$caracteristiquedesc->caracteristique = $idcarac;
				$caracteristiquedesc->lang = "1";
				$caracteristiquedesc->add();
				
				$caracdisp = new Caracdisp();
				$caracdisp->caracteristique = $idcarac;
				$iddisp = $caracdisp->add();
				
				$caracdispdesc = new Caracdispdesc();
				$caracdispdesc->caracdisp = $iddisp;
				$caracdispdesc->lang = "1";
				$caracdispdesc->titre = "OUI";
				$caracdispdesc->add();

				$caracdisp = new Caracdisp();
				$caracdisp->caracteristique = $idcarac;
				$iddisp = $caracdisp->add();
								
				$caracdispdesc = new Caracdispdesc();
				$caracdispdesc->caracdisp = $iddisp;
				$caracdispdesc->lang = "1";
				$caracdispdesc->titre = "NON";
				$caracdispdesc->add();
				
				$rubrique = new Rubrique();
				$query_rub = "select * from $rubrique->table";
				$resul_rub = mysql_query($query_rub, $rubrique->link);
				
				while($row = mysql_fetch_object($resul_rub)){
					$rubcar = new Rubcaracteristique();
					$rubcar->caracteristique = $idcarac;
					$rubcar->rubrique = $row->id;
					$rubcar->add();					
				}
				
				$caracteristique = new Caracteristique();
				$caracteristique->affiche = 0;
				$caracteristique->classement =  $maxclassement + 2;
				$idcarac = $caracteristique->add();

				$refsimple = new Variable();
				$refsimple->nom = "refsimple";
				$refsimple->valeur = $idcarac;
				$refsimple->protege = "1";		
				$refsimple->add();
				
				$caracteristiquedesc = new Caracteristiquedesc();
				$caracteristiquedesc->titre = "refsimple";
				$caracteristiquedesc->caracteristique = $idcarac;
				$caracteristiquedesc->lang = "1";
				$caracteristiquedesc->add();
				
				$rubrique = new Rubrique();
				$query_rub = "select * from $rubrique->table";
				$resul_rub = mysql_query($query_rub, $rubrique->link);
				
				while($row = mysql_fetch_object($resul_rub)){
					$rubcar = new Rubcaracteristique();
					$rubcar->caracteristique = $idcarac;
					$rubcar->rubrique = $row->id;
					$rubcar->add();					
				}	

		}


    }



?>
