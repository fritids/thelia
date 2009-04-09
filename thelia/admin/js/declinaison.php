<script type="text/javascript">
	function declinaison_ajouter(declinaison){
		$.ajax({type:"GET",url:"ajax/declinaison.php",data:"rubrique=<?php echo $_REQUEST['id']; ?>&declinaison="+declinaison+"&action=ajouter",success:function html(html){$("#declinaison_liste").html(html); charger_liste_decli(<?php echo $_REQUEST["id"]; ?>);}})
	}
	
	function charger_liste_decli(rubrique){
		$.ajax({type:"GET",url:"ajax/declinaison.php",data:"action=liste&id="+rubrique,success:function html(html){$("#liste_prod_decli").html(html);}})
	}
	
	function declinaison_supprimer(declinaison){
		$.ajax({type:"GET",url:"ajax/declinaison.php",data:"action=supprimer&declinaison="+declinaison+"&rubrique=<?php echo $_REQUEST["id"]; ?>",success:function html(html){$("#declinaison_liste").html(html); charger_liste_decli(<?php echo $_REQUEST["id"]; ?>);}})
	}


</script>