<?php

	include_once(realpath(dirname(__FILE__)) . "/../../../fonctions/authplugins.php");

	autorisation("expeditor");

	include_once(realpath(dirname(__FILE__)) . "/../../../classes/Commande.class.php");
	include_once(realpath(dirname(__FILE__)) . "/../../../classes/Client.class.php");
	include_once(realpath(dirname(__FILE__)) . "/../../../classes/Venteadr.class.php");
	include_once(realpath(dirname(__FILE__)) . "/../../../classes/Venteprod.class.php");
	include_once(realpath(dirname(__FILE__)) . "/../../../classes/Produit.class.php");
?>

<script type="text/javascript">

	var type = 1;
	
	function check(){

		var checkall=document.getElementsByTagName('input');
	 	for (i=0;i<checkall.length;i++){ 
			if(checkall[i].type == "checkbox")
			  if(type == 0) 
				checkall[i].checked=false;
			  else
				checkall[i].checked=true;
	 	}
	
	if(type == 0)
		type = 1;
	else type = 0;
	}
</script>

	<div id="contenu_int"> 
	   <p class="titre_rubrique">Gestion des commandes</p>
	     <p align="right" class="geneva11Reg_3B4B5B"><a href="accueil.php" class="lien04">Accueil </a> <img src="gfx/suivant.gif" width="12" height="9" border="0" /><a href="#" class="lien04">Gestion des commandes</a>              
	    </p>
	     <table width="710" border="0" cellpadding="5" cellspacing="0">
	     <tr>
	       <td width="600" height="30" class="titre_cellule_tres_sombre">Liste des commandes</td>
	   </tr>
	   </table>

<form action="../client/plugins/expeditor/export.php" id="formexport" method="post">
<input type="hidden" name="nom" value="expeditor" />
<input type="hidden" name="action" value="export" />

<table width="100%"  border="0" cellspacing="0" cellpadding="0">


<tr class="cellule_sombre">
  <td width="20%" height="30">&nbsp;</td>
	<td width="20%" height="30">&nbsp;</td>
	<td width="20%" height="30">&nbsp;</td>
  <td width="16%" height="30">
    <div align="left"><a href="javascript:check()" class="lien04">+</a></div>
  </td>
</tr>


<?php
	$commande = new Commande();
	
	$query_commandes = "select * from $commande->table where statut='2' order by date desc";
	$resul_commandes = mysql_query($query_commandes, $commande->link);
	$i = 0;
	
	while($row = mysql_fetch_object($resul_commandes)){
		if($i%2) $fond="sombre";
		else $fond="claire";
		
	$client = new Client();
	$client->charger_id($row->client);	
?>

  <tr class="cellule_<?php echo $fond; ?>">
    <td width="20%" height="30"><?php echo $row->date; ?></td>
 	<td width="20%" height="30"><a href="commande_details.php?ref=<?php echo $row->ref; ?>" class="lien04"><?php echo $row->ref; ?></a></td>
 	<td width="20%" height="30"><a href="client_visualiser.php?ref=<?php echo $client->ref; ?>" class="lien04"><?php echo $client->prenom . " " . $client->nom; ?></a></td>
    <td width="16%" height="30">
      <div align="left"><input type="checkbox" name="cmd[]" value="<?php echo $row->ref; ?>" /></div>
    </td>
  </tr>
 

<?php 

		$i++;
	}
	
?>
  </table>

<br />


<br /><br />

<a href="javascript:document.getElementById('formexport').submit()" class="lien04">Expédier</a>

</form>


</div>
</body>
</html>
