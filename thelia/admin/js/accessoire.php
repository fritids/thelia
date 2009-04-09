<script type="text/javascript">

	function charger_listacc(rubrique){
		$.ajax({type:'GET', url:'ajax/accessoire.php', data:'action=produit&ref=<?php echo $_GET['ref']; ?>&id_rubrique=' + rubrique,success:function(html){$('#select_prodacc').html(html)}})
	}	
	
	function accessoire_ajouter(id){
		if(id)
			$.ajax({type:'GET', url:'ajax/accessoire.php', data:'action=ajouter&ref=<?php echo $_GET['ref']; ?>&id='+ id,success:function(html){$('#accessoire_liste').html(html);charger_listacc(document.getElementById('accessoire_rubrique').value);}})
		
	}
	
	function accessoire_supprimer(id){
			$.ajax({type:'GET', url:'ajax/accessoire.php', data:'action=supprimer&ref=<?php echo $_GET['ref']; ?>&id='+ id,success:function(html){$('#accessoire_liste').html(html);charger_listacc(document.getElementById('accessoire_rubrique').value);}})
	
	}
	
</script>