<script type="text/javascript">
	function caracteristique_ajouter(caracteristique){
		$.ajax({type:"GET",url:"ajax/caracteristique.php",data:"rubrique=<?php echo $_REQUEST['id']; ?>&caracteristique="+caracteristique+"&action=ajouter",success:function html(html){$("#caracteristique_liste").html(html); charger_liste(<?php echo $_REQUEST["id"]; ?>);}})
	}
	
	function charger_liste(rubrique){
		$.ajax({type:"GET",url:"ajax/caracteristique.php",data:"action=liste&id="+rubrique,success:function html(html){$("#liste_prod_caracteristique").html(html);}})
	}
	
	function caracteristique_supprimer(caracteristique){
		$.ajax({type:"GET",url:"ajax/caracteristique.php",data:"action=supprimer&caracteristique="+caracteristique+"&rubrique=<?php echo $_REQUEST["id"]; ?>",success:function html(html){$("#caracteristique_liste").html(html); charger_liste(<?php echo $_REQUEST["id"]; ?>);}})
	}


</script>