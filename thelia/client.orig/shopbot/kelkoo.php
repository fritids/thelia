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
<?php header("Content-type: text/plain; charset=iso-8859-1");?>
#type=basic
#country=fr
#currency=eur
#update=no
#quoted=yes
url	title	description	price	offerid	image	category	availability	deliverycost	expiration
<?php
        function calculport($poids){

        if($poids==0) return 0;
     else if($poids<10) return 25;
         else if($poids>=10 && $poids<20) return 30;
         else if($poids>=20 && $poids<30) return 35;
         else if($poids>=30 && $poids<40) return 40;
         else if($poids>=40 && $poids<50) return 45;
         else if($poids>=50 && $poids<60) return 50;
         else if($poids>=60 && $poids<70) return 55;
         else if($poids>=70 && $poids<80) return 60;
         else if($poids>=80 && $poids<90) return 65;
         else if($poids>=90 && $poids<100) return 70;
         else if($poids>=100) return ceil($poids/100)* 70;


    }

?>
<?php

	include("../../classes/Produit.class.php");
	include("../../classes/Image.class.php");
	include("../../classes/Caracval.class.php");
	
	$produit = new Produit();	
	$produitdesc = new Produitdesc();

	$image = new Image();
	
	$query = "select * from $produit->table where boutique='1' and ligne='1' and reappro='0'";
	$resul = mysql_query($query, $produit->link);

	while($row = mysql_fetch_object($resul)){

		$produitdesc->charger($row->id);
		
		$query2 = "select * from $image->table where produit='$row->id'";
		$resul2 = mysql_query($query2, $image->link);
		$row2 = mysql_fetch_object($resul2);

                $description = ereg_replace("&nbsp;", "", strip_tags($produitdesc->description));
                $description = ereg_replace("\r\n", " ", $description);		
		$description = ereg_replace("Caractéristiques :", "", $description);
		$description = trim($description);
?>
http://www.site.com/produit.php?ref=<?php echo($row->ref); ?>&rt75=53&wx=108	<?php echo($produitdesc->titre); ?>	<?php echo($description); ?>	<?php echo($row->prix2); ?>	<?php echo($row->ref); ?>	http://www.site.com/client/gfx/photos/petite/<?php echo($row2->fichier); ?>	 Formule 1 / Sports mecaniques	En Stock	<?php echo(calculport($row->poids)); ?>	20070101
<?php	}	?>
<?php

	
	$produit = new Produit();	
	$produitdesc = new Produitdesc();

	$image = new Image();
	
	$query = "select * from $produit->table where boutique='1' and ligne='1' and reappro='1'";
	$resul = mysql_query($query, $produit->link);

	while($row = mysql_fetch_object($resul)){

		$caracval = new Caracval();
		$caracval->charger($row->id, "8");
		
		$produitdesc->charger($row->id);
		
		$query2 = "select * from $image->table where produit='$row->id'";
		$resul2 = mysql_query($query2, $image->link);
		$row2 = mysql_fetch_object($resul2);

                $description = ereg_replace("&nbsp;", "", strip_tags($produitdesc->description));
                $description = ereg_replace("\r\n", " ", $description);		
				$description = ereg_replace("Caractéristiques :", "", $description);
		$description = trim($description);
?>
http://www.site.com/produit.php?ref=<?php echo($row->ref); ?>&rt75=53&wx=108	<?php echo($produitdesc->titre); ?>	<?php echo($description); ?>	<?php echo($row->prix2); ?>	<?php echo($row->ref); ?>	http://www.site.com/client/gfx/photos/petite/<?php echo($row2->fichier); ?>	 Formule 1 / Sports mecaniques	Dispo le <?php echo($caracval->valeur); ?>	<?php echo(calculport($row->poids)); ?>	20070101
<?php	}	?>
