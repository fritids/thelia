/*****************************************************************************
 *
 * Auteur   : Bolo | wexpay.com (contact: infos_web@wexpay.com)
 * Version  : 0.1
 * Date     : 22/10/2007
 *
 * Copyright (C) 2007 Bolo Michelin
 * 
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 *****************************************************************************/



UTILISATION
============

Modifier la valeur de la variable $id_marchand dans le fichier config.php
Entrez tout simplement votre codeMarchand au format MD5

Pour le login et le pass, faites de m�me en fournissant les informations de WexPay.

Dans la section partenaires

https://parternaires.wexpay.com

Renseignez les champs suivant

Renommez confirmation.php en personnalisant le nom du fichier (s�curit�)

1) url de retour http://www.votreboutique.com/merci.php
2) url de retour cach� http://www.votreboutique.com/client/plugins/wexpay/confirmation_blabla.php
3) url de la page d'erreur http://www.votreboutique.com/regret.php

Information
============

Le retour de paiement n'est pas une information suffisante. V�rifiez toujours sur l'interface de votre banque qu'un paiement est bien pass� en paiement
avant de le consid�rer r�ellement comme "pay�"

V�rifier que les r�pertoires de votre site ne sont pas listable (ex http://www.votresite.com/client/plugins/).
Si tel est le cas veuillez ajouter un fichier htaccess afin de s�curiser le tout.
