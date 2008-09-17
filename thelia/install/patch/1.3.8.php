<?php
	include_once("../classes/Cnx.class.php");
	include_once("../classes/Rubrique.class.php");
	include_once("../classes/Produit.class.php");
	include_once("../classes/Variable.class.php");

	$rub = new Rubrique();
	$query_cnx = "select * from $rub->table";
	$resul_cnx = mysql_query($query_cnx, $rub->link);

	$prod = new Produit();
	
	while($row_cnx = mysql_fetch_object($resul_cnx)){
		$query_prod = "select * from $prod->table where rubrique=\"" . $row_cnx->id . "\" order by classement";
		$resul_prod = mysql_query($query_prod, $prod->link);
		$i = 0;
		while($row_prod = mysql_fetch_object($resul_prod)){
			$tmpprod = new Produit();
			$tmpprod->charger($row_prod->ref);
			$tmpprod->classement = ++$i;
			$tmpprod->maj();
			
		}
	}

	$version = new Variable();
	$version->charger("version");
	$version->valeur = "137";
	$version->maj();
	
?>