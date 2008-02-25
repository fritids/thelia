<?php
	$i=0;
	$res = "";

	foreach ($_POST as $key => $value)
		$res[$i++] = $value;	

?>

<orderid="<?php echo $res[0]; ?>"
currency="<?php echo $res[1]; ?>"
amount="<?php echo $res[2]; ?>">
