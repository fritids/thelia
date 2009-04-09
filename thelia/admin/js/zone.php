<script type="text/javascript">

	function forfait(valeur){
		$.ajax({type:'GET', url:'ajax/zone.php', data:'action=forfait&valeur=' + valeur + '&id=<?php echo $id; ?>'})
	}
	
	function supprimer(pays){
		if(pays)
			$.ajax({type:'GET', url:'ajax/zone.php', data:'action=supprimer&pays=' + pays + '&id=<?php echo $id; ?>',success:function(html){$('#listepays').html(html)}})
	}	
	
	function ajouter(pays){
		if(pays)
			$.ajax({type:'GET', url:'ajax/zone.php', data:'action=ajouter&pays=' + pays + '&id=<?php echo $id; ?>',success:function(html){$('#listepays').html(html)}})
	}	
	
</script>