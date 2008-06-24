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

Ce plugin vous permettra de d�cliner facilement une liste de produit suivant une caract�ristique.
Il est utile dans le cas o� vous vendez un produit d�clinable avec des tarifs diff�rents.

Veuillez simplement glisser le r�pertoire prodprixmult dans le dossier client/plugins de votre Thelia.

Ex : vous vendez une table qui existe en 3 tailles. Chaque taille dispose d'un prix diff�rent.

Un post sur le blog de Thelia d�taille la mise en oeuvre d'un tel syst�me : 
http://blog.thelia.fr/index.php?2008/01/16/197-declinaison-avec-modification-de-prix

Le plugin prodprixmult va cr�er automatiquement les caract�ristiques refsimple et principal.

Un module d'administration vous permettra ensuite de g�n�rer toutes les fiches produits n�cessaires pour toutes les valeurs d'une caract�ristique.

Dans notre exemple nous avons pr�alablement cr�e une caract�ristique taille avec 3 valeurs.

Le plugin permettra de cr�er en une seule fois, 3 fiches produits.
La 1�re sera le produit principal. Il suffira simplement de modifier les prix de chacun des produits.

Utilisation des boucles
------------------------

Dans l'exemple ci-dessous :
A = id de la caract�ristique principal (voir sur la page de la caract�ristique principal)
B = id de la valeur oui (voir sur la page de la caract�ristique principal)

C = id de la caract�ristique refsimple (voir sur la page de la caract�ristique refsimple)

D = id de la caract�ristique taille (voir sur la page de la caract�ristique taille)


Affichage des produits sur la page rubrique

<THELIA_PROD type="PRODUIT" rubrique="#RUBRIQUE_ID" classement="inverse" num="12" caracteristique="A-" caracdisp="B-">
...
</THELIA_PROD>

Au niveau de la fiche produit 

<THELIA_caracref type="CARACVAL" produit="#PRODUIT_ID" caracteristique="C">

Choisir la taille :

<select onChange="location=this.value">
	<option value="">Votre Choix ...</option>
	<THELIA_listprod type="PRODUIT" caracteristique="C" caracval="#VALEUR">
		<option value="#REWRITEURL">
			<THELIA_taille type="CARACVAL" produit="#ID" caracteristique="D">
				#VALEUR
			</THELIA_taille>
		</option>
</THELIA_listprod>
</select> 

</THELIA_caracref>