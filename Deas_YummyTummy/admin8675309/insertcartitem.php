<?php
	require_once "header.php";
	require_once "connect.php";

	$formfield['ffcartid'] = $_POST['cartid'];
	$formfield['ffcartitemid'] = $_POST['cartitemid'];
	$formfield['ffourproductid'] = $_POST['ourproductid'];
	$formfield['ffcartitemprice'] = $_POST['cartitemprice'];
	
	$sqlselectc = "SELECT * from prodcats";
	$resultc = $db->prepare($sqlselectc);
	$resultc->execute();
	
	if (isset($_POST['OIEnter']))
	{
		$sqlinsert = 'INSERT INTO cartitems (dbcartid, dbourproductid,
				dbcartitemprice) VALUES (:bvcartid, :bvourproductid, :bvcartitemprice)';
			$stmtinsert = $db->prepare($sqlinsert);
			$stmtinsert->bindvalue(':bvcartid', $formfield['ffcartid']);
			$stmtinsert->bindvalue(':bvourproductid', $formfield['ffourproductid']);
			$stmtinsert->bindvalue(':bvcartitemprice', $formfield['ffcartitemprice']);
			$stmtinsert->execute();
	}

	if (isset($_POST['DeleteItem']))
	{
		$sqldelete = 'DELETE FROM cartitems 
					WHERE dbcartitemid = :bvcartitemid';
		$stmtdelete = $db->prepare($sqldelete);
		$stmtdelete->bindvalue(':bvcartitemid', $formfield['ffcartitemid']);
		$stmtdelete->execute();
	}
	
	if (isset($_POST['UpdateItem']))
	{
		$formfield['ffcartitemprice'] = $_POST['newprice'];
		$formfield['ffcartitemnotes'] = trim($_POST['newnote']);
		$sqlupdateoi = 'Update cartitems 
					set dbcartitemprice = :bvcartitemprice,
						dbcartitemnotes = :bvcartitemnotes
					WHERE dbcartitemid = :bvcartitemid';
		$stmtupdateoi = $db->prepare($sqlupdateoi);
		$stmtupdateoi->bindvalue(':bvcartitemid', $formfield['ffcartitemid']);
		$stmtupdateoi->bindvalue(':bvcartitemprice', $formfield['ffcartitemprice']);
		$stmtupdateoi->bindvalue(':bvcartitemnotes', $formfield['ffcartitemnotes']);
		$stmtupdateoi->execute();
	}
	
	$sqlselecto = "SELECT cartitems.*, ourproducts.dbourproductname
			FROM cartitems, ourproducts
			WHERE ourproducts.dbourproductid = cartitems.dbourproductid
			AND cartitems.dbcartid = :bvcartid";
	$resulto = $db->prepare($sqlselecto);
	$resulto->bindValue(':bvcartid', $formfield['ffcartid']);
	$resulto->execute();
	
	if ($visible == 1 && $_SESSION['loginpermit'] == 1)
	{
?>

<fieldset><legend>Enter Items for Ticket Number 
		<?php echo $formfield['ffcartid'] ;?> </legend>
		
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
		echo '<input type = "hidden" name = "cartid" value = "'. $formfield['ffcartid'] .'">';
		echo '<input type = "hidden" name = "ourproductid" value = "'. $rowp['dbourproductid'] .'">';
		echo '<input type = "hidden" name = "cartitemprice" value = "'. $rowp['dbourproductcost'] .'">';
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
	echo '<tr><td>' . $rowo['dbourproductname'] . '</td><td>' . $rowo['dbcartitemprice'] . '</td>';
	echo '<td>' . $rowo['dbcartitemnotes'] . '</td><td>';
	echo '<form action = "' . $_SERVER['PHP_SELF'] . '" method = "post">';
	echo '<input type = "hidden" name = "cartid" value = "'. $formfield['ffcartid'] .'">';
	echo '<input type = "hidden" name = "cartitemid" value = "'. $rowo['dbcartitemid'] .'">';
	echo '<input type="submit" name="NoteEntry" value="Update">';
	echo '</form></td><td>';
	echo '<form action = "' . $_SERVER['PHP_SELF'] . '" method = "post">';
	echo '<input type = "hidden" name = "cartid" value = "'. $formfield['ffcartid'] .'">';
	echo '<input type = "hidden" name = "cartitemid" value = "'. $rowo['dbcartitemid'] .'">';
	echo '<input type="submit" name="DeleteItem" value="Delete">';
	echo '</form></td></tr>';
	}
?>
</table>
		<?php
			if (isset($_POST['NoteEntry']))
			{
			$sqlselectoi = "SELECT cartitems.*, ourproducts.dbourproductname 
				from cartitems, ourproducts
				WHERE ourproducts.dbourproductid = cartitems.dbourproductid
				AND cartitems.dbcartitemid = :bvcartitemid";
			$resultoi = $db->prepare($sqlselectoi);
			$resultoi->bindvalue(':bvcartitemid', $_POST['cartitemid']);
			$resultoi->execute();
			$rowoi = $resultoi->fetch();
			
	echo '</td><td>';
	echo '<form action = "' . $_SERVER['PHP_SELF'] . '" method = "post">';
	echo '<table>';
	echo '<tr><td>Price: <input type = "text" name = "newprice" value = "'. $rowoi['dbcartitemprice'] . '"></td></tr>';
	echo '<tr><td>Note: <input type = "text" name = "newnote" value = "'. $rowoi['dbcartitemnotes'] . '"></td></tr>';
	echo '<tr><td>';
	echo '<input type = "hidden" name = "cartid" value = "'. $formfield['ffcartid'] .'">';
	echo '<input type = "hidden" name = "cartitemid" value = "'. $rowoi['dbcartitemid'] .'">';
	echo '<input type="submit" name="UpdateItem" value="Update Item"></form></td></tr></table>';
	}
	?>
		
		</td></tr>
	</table>
	<br><br>
<?php
	echo '<form action = "completecart.php" method = "post">';
	echo '<input type = "hidden" name = "cartid" value = "'. $formfield['ffcartid'] .'">';
	echo '<input type="submit" name="CompleteCart" value="Complete Cart">';
	echo '</form>';

}//visible
include_once 'footer.php';
?>