<?php
	require_once "header.php";
	require_once "connect.php";

	$formfield['ffticketid'] = $_POST['ticketid'];
	$formfield['ffticketitemid'] = $_POST['ticketitemid'];
	$formfield['ffourproductid'] = $_POST['ourproductid'];
	$formfield['ffticketitemprice'] = $_POST['ticketitemprice'];
	
	$sqlselectc = "SELECT * from prodcats";
	$resultc = $db->prepare($sqlselectc);
	$resultc->execute();
	
	if (isset($_POST['OIEnter']))
	{
		$sqlinsert = 'INSERT INTO ticketItems (ticketid,ticketprodid,
				ticketitemprice) VALUES (:bvticketid, :bvourproductid, :bvticketitemprice)';
			$stmtinsert = $db->prepare($sqlinsert);
			$stmtinsert->bindvalue(':bvticketid', $formfield['ffticketid']);
			$stmtinsert->bindvalue(':bvourproductid', $formfield['ffourproductid']);
			$stmtinsert->bindvalue(':bvticketitemprice', $formfield['ffticketitemprice']);
			$stmtinsert->execute();
	}

	if (isset($_POST['DeleteItem']))
	{
		$sqldelete = 'DELETE FROM ticketItems 
					WHERE ticketitemid = :bvticketitemid';
		$stmtdelete = $db->prepare($sqldelete);
		$stmtdelete->bindvalue(':bvticketitemid', $formfield['ffticketitemid']);
		$stmtdelete->execute();
	}
	
	if (isset($_POST['UpdateItem']))
	{
		$formfield['ffticketitemprice'] = $_POST['newprice'];
		$formfield['ffticketitemnotes'] = trim($_POST['newnote']);
		$sqlupdateoi = 'Update ticketItems 
					set ticketitemprice = :bvticketitemprice,
						ticketcomments = :bvticketitemnotes
					WHERE ticketitemid = :bvticketitemid';
		$stmtupdateoi = $db->prepare($sqlupdateoi);
		$stmtupdateoi->bindvalue(':bvticketitemid', $formfield['ffticketitemid']);
		$stmtupdateoi->bindvalue(':bvticketitemprice', $formfield['ffticketitemprice']);
		$stmtupdateoi->bindvalue(':bvticketitemnotes', $formfield['ffticketitemnotes']);
		$stmtupdateoi->execute();
	}
	
	$sqlselecto = "SELECT ticketItems.*, ourproducts.dbourproductname
			FROM ticketItems, ourproducts
			WHERE ourproducts.dbourproductid = ticketItems.ticketprodid
			AND ticketItems.ticketid = :bvticketid";
	$resulto = $db->prepare($sqlselecto);
	$resulto->bindValue(':bvticketid', $formfield['ffticketid']);
	$resulto->execute();
	
	if ($visible == 1 && $_SESSION['loginpermit'] == 1)
	{
?>

<fieldset><legend>Enter Items for ticket Number 
		<?php echo $formfield['ffticketid'] ;?> </legend>
		
		<table border>
			<?php
				echo '<tr>';
				while ($rowc = $resultc->fetch() )
					{
	echo '<th valign = "top" align = "center">' . $rowc['dbprodcatname'] . '<br>';
	echo '<table border>';
	$sqlselectp = "SELECT * from ourproducts where dbourproductcat = :bvourproductcat";
	$resultp = $db->prepare($sqlselectp);
	$resultp->bindValue(':bvourproductcat', $rowc['dbprodcatid']);
	$resultp->execute();
	while ($rowp = $resultp->fetch() )
		{
		echo '<tr><td>';
		echo '<form action = "' . $_SERVER['PHP_SELF'] . '" method = "post">';
		echo '<input type = "hidden" name = "ticketid" value = "'. $formfield['ffticketid'] .'">';
		echo '<input type = "hidden" name = "ourproductid" value = "'. $rowp['dbourproductid'] .'">';
		echo '<input type = "hidden" name = "ticketitemprice" value = "'. $rowp['dbourproductcost'] .'">';
		echo '<input type="submit" name="OIEnter" value="'. $rowp['dbourproductname'] . ' - $' 
			. $rowp['dbourproductcost'] .'">';
		echo '</form>';
		echo '</td></tr>';
		}
	echo '</table></th>';	
	}
echo '</tr>';
?>
</table>
</fieldset>
<br><br>
	<table>
		<tr>
		<td>
		<table border>
			<tr>
				<th>Item</th>
				<th>Price</th>
				<th>Notes</th>
				<th></th>
				<th></th>
			</tr>
<?php
	while ($rowo = $resulto->fetch() )
	{
	echo '<tr><td>' . $rowo['dbourproductname'] . '</td><td>' . $rowo['ticketitemprice'] . '</td>';
	echo '<td>' . $rowo['ticketcomments'] . '</td><td>';
	echo '<form action = "' . $_SERVER['PHP_SELF'] . '" method = "post">';
	echo '<input type = "hidden" name = "ticketid" value = "'. $formfield['ffticketid'] .'">';
	echo '<input type = "hidden" name = "ticketitemid" value = "'. $rowo['ticketitemid'] .'">';
	echo '<input type="submit" name="NoteEntry" value="Update">';
	echo '</form></td><td>';
	echo '<form action = "' . $_SERVER['PHP_SELF'] . '" method = "post">';
	echo '<input type = "hidden" name = "ticketid" value = "'. $formfield['ffticketid'] .'">';
	echo '<input type = "hidden" name = "ticketitemid" value = "'. $rowo['ticketitemid'] .'">';
	echo '<input type="submit" name="DeleteItem" value="Delete">';
	echo '</form></td></tr>';
	}
?>
</table>
		<?php
			if (isset($_POST['NoteEntry']))
			{
			$sqlselectoi = "SELECT ticketItems.*, ourproducts.dbourproductname 
				from ticketItems, ourproducts
				WHERE ourproducts.dbourproductid = ticketItems.ticketprodid
				AND ticketItems.ticketitemid = :bvticketitemid";
			$resultoi = $db->prepare($sqlselectoi);
			$resultoi->bindvalue(':bvticketitemid', $_POST['ticketitemid']);
			$resultoi->execute();
			$rowoi = $resultoi->fetch();
			
	echo '</td><td>';
	echo '<form action = "' . $_SERVER['PHP_SELF'] . '" method = "post">';
	echo '<table>';
	echo '<tr><td>Price: <input type = "text" name = "newprice" value = "'. $rowoi['ticketitemprice'] . '"></td></tr>';
	echo '<tr><td>Note: <input type = "text" name = "newnote" value = "'. $rowoi['ticketcomments'] . '"></td></tr>';
	echo '<tr><td>';
	echo '<input type = "hidden" name = "ticketid" value = "'. $formfield['ffticketid'] .'">';
	echo '<input type = "hidden" name = "ticketitemid" value = "'. $rowoi['ticketitemid'] .'">';
	echo '<input type="submit" name="UpdateItem" value="Update Item"></form></td></tr></table>';
	}
	?>
		
		</td></tr>
	</table>
	<br><br>
<?php
	echo '<form action = "completeticket.php" method = "post">';
	echo '<input type = "hidden" name = "ticketid" value = "'. $formfield['ffticketid'] .'">';
	echo '<input type="submit" name="Completeticket" value="Complete ticket">';
	echo '</form>';

}//visible
include_once 'footer.php';
?>