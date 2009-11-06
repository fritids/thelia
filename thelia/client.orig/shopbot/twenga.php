<?header("Content-type: text/plain; charset=ISO-8815-1");?>
<?echo"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";?> 
<?
	function calculport($poids){
		return 0;

    }

?>
<? 
	$ladate = date("Y-m-d H:i");
?>
<catalog lang="FR" date="<?= $ladate ?>" GMT="+1" version="1.4">
<?

	include_once("../../classes/Produit.class.php");
	include_once("../../classes/Image.class.php");
	include_once("../../classes/Variable.class.php");

	$i=0;

	$urlsite = new Variable();
	$urlsite->charger("urlsite");

	$nomsite = new Variable();
	$nomsite->charger("nomsite");
			
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
		$i++;

		$chapo = str_replace("&nbsp;", "", strip_tags($produitdesc->chapo));
		$chapo = str_replace("\r", " - ", $chapo);	
		
		
?>

	<product place="<?= $i ?>">
		<brand><![CDATA[ <?php echo $nomsite->valeur; ?> ]]></brand>
		<name><![CDATA[ <?= $produitdesc->titre ?> ]]></name>
		<price currency="EUR" tax_included="1" tax_value="19.6"><?= $row->prix2 ?></price>
		<product_url><![CDATA[ <?php echo $urlsite->valeur; ?>/produit.php?ref=<?= $row->ref ?> ]]></product_url>
		<description><![CDATA[ <?= $chapo ?>  ]]></description>
		<image_url><![CDATA[ <?php echo $urlsite->valeur; ?>/fonctions/redimlive.php?nomorig=../client/gfx/photos/produit/<?= $row2->fichier ?>&width=70&height=70 ]]></image_url>
		<category><![CDATA[ CATEGORIE  ]]></category>
		<merchant_id><![CDATA[ <?= $row->ref ?>  ]]></merchant_id>
		<shipping_cost currency="EUR">
			FR;<?= calculport($row->poids) ?>;0
		</shipping_cost>
		<in_stock>Y</in_stock>
		<update_date><![CDATA[ <?= $row->datemodif ?> ]]></update_date>
		<weight unit="kg"><?= $row->poids ?></weight>
	</product>

<?	}	?>

</catalog>
