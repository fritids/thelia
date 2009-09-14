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
<?php echo"<?php xml version=\"1.0\" encoding=\"iso-8859-1\"?>";?> 
<?php
        function calculport($poids){
 			return 0;
    	}

?>
<?php 
	$ladate = date("Y-m-d H:i");
?>
<catalog lang="FR" date="<?php echo($ladate); ?>" GMT="+1" version="1.4">
<?php

	include_once("../../classes/Produit.class.php");
	include_once("../../classes/Image.class.php");
	include_once("../../classes/Rubrique.class.php");
	include_once("../../classes/Variable.class.php");
	
	$variable = new Variable();
	$variable->charger("urlsite");
	
	$i=0;
	
	$produit = new Produit();	
	$produitdesc = new Produitdesc();
	$rubrique = new Rubrique();
	$rubriquedesc = new Rubriquedesc();

	$image = new Image();
	 
	$query = "select * from $produit->table where ligne='1'";
	$resul = mysql_query($query, $produit->link);

	while($row = mysql_fetch_object($resul)){

		$produitdesc->charger($row->id);
		
		$query2 = "select * from $image->table where produit='$row->id'";
		$resul2 = mysql_query($query2, $image->link);
		$row2 = mysql_fetch_object($resul2);
		$i++;

		$description = str_replace("&nbsp;", "", strip_tags($produitdesc->description));
		$description = str_replace("\r\n", " ", $description);
		$description = trim($description);

		$rubriquedesc->charger($row->rubrique);		
		
		if($row->promo)
			$prix = $row->prix2;
		else
			$prix = $row->prix;
?>

	<product place="<?php echo($i); ?>">
		<merchant_category><![CDATA[  <?php echo(htmlentities($rubriquedesc->titre)); ?>  ]]></merchant_category>
		<offer_id><![CDATA[ <?php echo($row->ref); ?>  ]]></offer_id>
		<name><![CDATA[ <?php echo(htmlentities($produitdesc->titre)); ?> ]]></name>
		<description>
			<![CDATA[ <?php echo(htmlentities($description)); ?>  ]]>
		</description>
		<regular_price currency="EUR" tax_included="1" tax_value="19.6"><?php echo $prix; ?></regular_price>
		<product_url><![CDATA[ <?php echo $variable->valeur; ?>/produit.php?ref=<?php echo($row->ref); ?> ]]></product_url>
		<image_url><![CDATA[ <?php echo $variable->valeur; ?>/fonctions/redimlive.php?nomorig=../client/gfx/photos/produit/<?php echo($row2->fichier); ?>&width=70&height=70 ]]></image_url>
		<delivery currency="EUR">
			FR;<?php echo(calculport($row->poids)); ?>;0
		</delivery>
		<model_number><![CDATA[ <?php echo($row->ref); ?> ]]></model_number>
		<used>0</used>
		<update_date><![CDATA[ <?php echo($row->datemodif); ?> ]]></update_date>
		<weight unit="kg"><?php echo($row->poids); ?></weight>

	</product>

<?php	}	?>

</catalog>
