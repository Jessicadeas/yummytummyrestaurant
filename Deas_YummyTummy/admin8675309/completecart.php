<?php
	require_once "header.php";
	require_once "connect.php";
	
	$formfield['ffcartid'] = $_POST['cartid'];
	
	$sqlselecto = "SELECT cartitems.*, ourproducts.dbourproductname
			FROM cartitems, ourproducts
			WHERE ourproducts.dbourproductid = cartitems.dbourproductid
			AND cartitems.dbcartid = :bvcartid";
	$resulto = $db->prepare($sqlselecto);
	$resulto->bindValue(':bvcartid', $formfield['ffcartid']);
	$resulto->execute();
	
	$sqlinsert = 'update cartinfo set dbcartcomplet = :bvcartcomplete
								  where dbcartid = :bvcartid';
	$stmtinsert = $db->prepare($sqlinsert);
	$stmtinsert->bindValue(':bvcartcomplete', 1);
	$stmtinsert->bindValue(':bvcartid', $formfield['ffcartid']);
	$stmtinsert->execute();
	
	$carttotal = 0;
?>
<h2>Your cart has been submitted.  Thank you!</h2>
<table border>
		<tr>
			<th>Item</th>
			<th>Price</th>
			<th>Notes</th>
		</tr>
		<?php
		while ($rowo = $resulto->fetch() )
			{
			$carttotal = $carttotal + $rowo['dbcartitemprice'];
			
			echo '<tr><td>' . $rowo['dbourproductname'] . '</td><td>' . $rowo['dbcartitemprice'] . '</td>';
			echo '<td>' . $rowo['dbcartitemnotes'] . '</td></tr>';
			}
		echo '<tr><th>Total</th>';
		echo '<th>' . $carttotal . '</th><td></td></tr>';
		?>
</table>
	
<?php			
	include_once 'footer.php';
?>