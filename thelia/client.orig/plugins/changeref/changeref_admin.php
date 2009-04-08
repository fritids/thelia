<?php
	include_once(realpath(dirname(__FILE__)) . "/Changeref.class.php");
	
	if($_REQUEST['action'] == "modifier"){
		$produit = new Produit();
	  $temp = new Produit();
	  if(! $temp->charger($_REQUEST['nouvelle_ref'])){
		
		if($produit->charger($_REQUEST['ancienne_ref'])){
			$produit->ref = $_REQUEST['nouvelle_ref'];
			$produit->maj();
			
			$venteprod = new Venteprod();
			$query = "update $venteprod->table set ref='" . $_REQUEST['nouvelle_ref'] . "' where ref='" . $_REQUEST['ancienne_ref'] . "'";
			$resul = mysql_query($query, $venteprod->link);
?>
	<script type="text/javascript">
		alert("Ref modifiee correctement");
	</script>
<?php
 			
		}
		
		else {
			
?>	
	<script type="text/javascript">
		alert("Cette ref n'existe pas");
	</script>
<?php		
		}
	 } else {
		
?>
<script type="text/javascript">
	alert("La ref n'est pas disponible");
</script>
<?php		
		
	}	
  }
?>

	<div id="contenu_int"> 
	   <p class="titre_rubrique">Changement de references</p>
	     <p align="right" class="geneva11Reg_3B4B5B"><a href="accueil.php" class="lien04">Accueil </a> <img src="gfx/suivant.gif" width="12" height="9" border="0" /><a href="#" class="lien04">Changement de references</a>              
	    </p>
	     <table width="710" border="0" cellpadding="5" cellspacing="0">
	     <tr>
	       <td width="600" height="30" class="titre_cellule_tres_sombre">Changer une référence</td>
	   </tr>
	   </table>

<form action="module.php" method="post">
<input type="hidden" name="nom" value="changeref" />
<input type="hidden" name="action" value="modifier" />

<table width="100%"  border="0" cellspacing="0" cellpadding="0">

  <tr class="cellule_claire">
    <td width="20%" height="30">Ancienne ref <input type="texte" name="ancienne_ref" /></td>
 	<td width="20%" height="30">Nouvelle ref <input type="texte" name="nouvelle_ref" /></td>
 	<td width="20%" height="30"><input type="submit" value="changer" /></td>

  </tr>

  </table>
</form>
</div>
</body>
</html>
