<?php
/*************************************************************************************/
/*                                                                                   */
/*      Thelia	                                                            		 */
/*                                                                                   */
/*      Copyright (c) Octolys Development		                                     */
/*		email : thelia@octolys.fr		        	                             	 */
/*      web : www.octolys.fr						   							 */
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
<?php header("Content-type: text/plain; charset=iso-8859-1");?>
Référence;Fabricant;CUP;Nom du produit;Description du produit;Prix du produit (doit s'entendre TTC);URL produit;URL image;Catégorie;Stock;Description du stock;Frais de port  ;Poids
<?php
        function calculport($poids){
 			return 0;
    	}

?>
<?php

	include_once("../../classes/Produit.class.php");
	include_once("../../classes/Image.class.php");
	include_once("../../classes/Variable.class.php");
	
	$variable = new Variable();
	$variable->charger("urlsite");
	
	$produit = new Produit();	
	$produitdesc = new Produitdesc();

	$image = new Image();
	
	$query = "select * from $produit->table where ligne='1'";
	$resul = mysql_query($query, $produit->link);

	while($row = mysql_fetch_object($resul)){

		$produitdesc->charger($row->id);
		
		$query2 = "select * from $image->table where produit='$row->id'";
		$resul2 = mysql_query($query2, $image->link);
		$row2 = mysql_fetch_object($resul2);

        $description = str_replace("\r\n", " ", $produitdesc->description);	
		$description = str_replace("\n", " ", $description);	
   		$description = str_replace("<br>", "<br />", $description);	
   		$description = str_replace("<BR>", "<br />", $description);	
   		$description = str_replace("\n", " ", $description);	
		$description = str_replace("<br />", " ", $description);	
        $description = strip_tags($description);
	
		$description = trim($description);
?>
<?php echo($row->ref); ?>;;;<?php echo($produitdesc->titre); ?>;<?php echo($description); ?>;<?php echo($row->prix2); ?>;<?php echo $variable->valeur; ?>/produit.php?ref=<?php echo($row->ref); ?>;<?php echo $variable->valeur; ?>/client/gfx/photos/<?php echo($row2->fichier); ?>;Jouets;En Stock;;<?php echo(calculport($row->poids)); ?>

<?php	}	?>
