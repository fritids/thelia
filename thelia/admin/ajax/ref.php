<?php
include_once(realpath(dirname(__FILE__)) . "/../../classes/Administrateur.class.php");
include_once(realpath(dirname(__FILE__)) . "/../../classes/Navigation.class.php");
session_start();
if( ! isset($_SESSION["util"]->id) ) {header("Location: ../index.php");exit;}
include_once(realpath(dirname(__FILE__)) . "/../../fonctions/divers.php");
?>
<?php if(! est_autorise("acces_catalogue")) exit; ?>
<?php
include_once(realpath(dirname(__FILE__)) . "/../../classes/Produit.class.php");
$ref = $_GET["ref_c"];

$produit = new Produit();
if($produit->charger($ref)){
	?>
		<div class="confirmation_ref"><img src="gfx/interdiction.png" /></div>
	<?php
}
else{
	?>
		<div class="confirmation_ref"><img src="gfx/validation.png" /></div>
	<?php
}

?>