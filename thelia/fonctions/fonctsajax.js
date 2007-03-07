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


var tabDiv = new Array();
var boucleMoment;
	
function do_analyse(boucle, param) {
	boucleMoment = "thelia" + boucle;
	
	document.getElementById(boucleMoment).innerHTML=sx_gosaj(boucle, param);

	if(! document.getElementById(boucleMoment).innerHTML) return "";
}

function do_ajout(ref, dec) {
	sx_ajoutsaj(ref, JSON.stringify(dec));
 }

function do_modpass(pass) {
	sx_modifpasssaj(pass);
 }

function do_modcoord(raison, nom, prenom, telfixe, telport, email, adresse1, adresse2, adresse3, cpostal, ville, pays) {
	sx_modifcoordsaj(raison, nom, prenom, telfixe, telport, email, adresse1, adresse2, adresse3, cpostal, ville, pays);
	
}
