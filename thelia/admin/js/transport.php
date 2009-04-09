<script type="text/javascript">

	function ajouter(zone){
		if(zone)
			$.ajax({type:'GET', url:'ajax/transport.php', data:'action=ajouter&zone=' + zone + '&id=<?php echo $_GET['id']; ?>',success:function(html){$('#listezone').html(html)}})
	
	}
	
	function supprimer(zone){
		if(zone)
			$.ajax({type:'GET', url:'ajax/transport.php', data:'action=supprimer&zone=' + zone + '&id=<?php echo $_GET['id']; ?>',success:function(html){$('#listezone').html(html)}})
	}	
	

	
</script>