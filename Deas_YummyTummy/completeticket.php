<?php
	require_once "header.php";
	require_once "connect.php";
	
	$formfield['ffticketid'] = $_POST['ticketid'];
	
	$sqlselecto = "SELECT ticketItems.*, ourproducts.dbourproductname
			FROM ticketItems, ourproducts
			WHERE ourproducts.dbourproductid = ticketItems.ticketprodid
			AND ticketItems.ticketid = :bvticketid";
	$resulto = $db->prepare($sqlselecto);
	$resulto->bindValue(':bvticketid', $formfield['ffticketid']);
	$resulto->execute();
	
	$sqlinsert = 'update tickets set ticketComplete = :bvticketcomplete
								  where ticketNum = :bvticketid';
	$stmtinsert = $db->prepare($sqlinsert);
	$stmtinsert->bindValue(':bvticketcomplete', 1);
	$stmtinsert->bindValue(':bvticketid', $formfield['ffticketid']);
	$stmtinsert->execute();
	
	$tickettotal = 0;
?>
<h2>Your ticket has been submitted.  Thank you!</h2>
<table border>
		<tr>
			<th>Item</th>
			<th>Price</th>
			<th>Notes</th>
		</tr>
		<?php
		while ($rowo = $resulto->fetch() )
			{
			$tickettotal = $tickettotal + $rowo['ticketitemprice'];
			
			echo '<tr><td>' . $rowo['dbourproductname'] . '</td><td>' . $rowo['ticketitemprice'] . '</td>';
			echo '<td>' . $rowo['ticketcomments'] . '</td></tr>';
			}
		echo '<tr><th>Total</th>';
		echo '<th>' . $tickettotal . '</th><td></td></tr>';
		?>
</table>
	
<?php			
	include_once 'footer.php';
?>