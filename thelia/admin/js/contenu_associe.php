<script type="text/javascript">

	function charger_listcont(dossier, type,objet){
		$.ajax({type:'GET', url:'ajax/contenu_associe.php', data:'action=contenu_assoc&type=' + type + '&objet='+objet+'&id_dossier=' + dossier,success:function(html){$('#select_prodcont').html(html)}})
	}	
	
	function contenu_ajouter(id, type,objet){
		if(id)
			$.ajax({type:'GET', url:'ajax/contenu_associe.php', data:'action=ajouter&type=' + type + '&objet='+objet+'&id='+ id,success:function(html){$('#contenuassoc_liste').html(html);charger_listcont(document.getElementById('contenuassoc_dossier').value, type,objet);}})
		
	}
	
	function contenuassoc_supprimer(id, type,objet){
			$.ajax({type:'GET', url:'ajax/contenu_associe.php', data:'action=supprimer&type=+ ' + type + '&objet='+objet+'&id='+ id,success:function(html){$('#contenuassoc_liste').html(html);charger_listcont(document.getElementById('contenuassoc_dossier').value, type,objet);}})
	
	}
	
</script>