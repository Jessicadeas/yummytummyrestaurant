<?php
$pagetitle = 'Select ourproducts';
require_once 'header.php';
require_once 'connect.php';

$errormsg = "";
$showform = 1;
$sqlselectc = "SELECT * from prodcats";
$resultc = $db->prepare($sqlselectc);
$resultc->execute();

	if( isset($_POST['thesubmit']) )
		{
			$addedclause = '';
			$formfield['ffourproductname'] = trim($_POST['ourproductname']);
			$formfield['ffourproductdescr'] = trim($_POST['ourproductdescr']);
			$formfield['ffourproductcost'] = trim(strtolower($_POST['ourproductcost']));
			$formfield['ffourproductcat'] = trim($_POST['ourproductcat']);
			
			if ($formfield['ffourproductcat'] != '') {
				$addedclause .= " AND dbourproductcat = :bvourproductcat";
			}
			
			$sqlselect = "SELECT ourproducts.*, prodcats.dbprodcatname
							FROM ourproducts, prodcats
							WHERE ourproducts.dbourproductcat = prodcats.dbprodcatid
							AND dbourproductname like CONCAT('%', :bvourproductname, '%')
							AND dbourproductdescr like CONCAT('%', :bvourproductdescr, '%')
							AND dbourproductcost like CONCAT('%', :bvourproductcost, '%')"
							. $addedclause;
			$result = $db->prepare($sqlselect);
			$result->bindValue(':bvourproductname', $formfield['ffourproductname']);
			$result->bindValue(':bvourproductdescr', $formfield['ffourproductdescr']);
			$result->bindValue(':bvourproductcost', $formfield['ffourproductcost']);
			if ($formfield['ffourproductcat'] != '') {
				$result->bindValue(':bvourproductcat', $formfield['ffourproductcat']);
			}
			$result->execute();
		}
	else
		{
			$sqlselect = "SELECT ourproducts.*, prodcats.dbprodcatname
							FROM ourproducts, prodcats
							WHERE ourproducts.dbourproductcat = prodcats.dbprodcatid";
			$result = $db-> query($sqlselect);
		}

if ($visible == 1)
{		
	?>
	<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="theform">
			<fieldset><legend>Product Information</legend>
				<table border>
					<tr>
						<th>Product Name</th>
						<td><input type="text" name="ourproductname" id="ourproductname"
						value = "<?php echo $formfield['ffourproductname']; ?>"></td>
					</tr>
					<tr>
						<th>Description</th>
						<td><input type="text" name="ourproductdescr" id="ourproductdescr"
						value = "<?php echo $formfield['ffourproductdescr']; ?>"></td>
					</tr>
					<tr>
						<th>Price</th>
						<td><input type="text" name="ourproductcost" id="ourproductcost"
						value = "<?php echo $formfield['ffourproductcost']; ?>"></td>
					</tr>
					<tr>
						<th><label>Category:</label></th>
						<td><select name="ourproductcat" id="ourproductcat">
						<option value = "">Please Select a Category</option>
						<?php while ($rowc = $resultc->fetch() )
							{
							if ($rowc['dbprodcatid'] == $formfield['ffourproductcat'])
								{$checker = 'selected';}
							else {$checker = '';}
							echo '<option value="'. $rowc['dbprodcatid'] . '" ' . $checker . '>' . $rowc['dbprodcatname'] . '</option>';
							}
						?>
						</select>
						</td>
					</tr>
				</table>
				<input type="submit" name = "thesubmit" value="Enter">
			</fieldset>
		</form>
			<br><br>
	<table border>
	<tr>
		<th>Product</th>
		<th>Description</th>
		<th>Price</th>
		<th>Category</th>
		<th>Edit</th>
	</tr>
	<?php 
		while ( $row = $result-> fetch() )
			{
				echo '<tr><td>' . $row['dbourproductname'] . '</td><td> ' . $row['dbourproductdescr'] . 
				'</td><td> ' . $row['dbourproductcost'] . 
				'</td><td> ' . $row['dbprodcatname'] . 
				'</td><td> ' .
				
				'<form action = "updateourproduct.php" method = "post">
						<input type = "hidden" name = "ourproductid" value = "'
						. $row['dbourproductid'] . 
						'"><input type="submit" name = "theedit" value="Edit">
				</form>'
				
				. '</td></tr>';
			}
		?>
	</table>
<?php
}
include_once 'footer.php';
?>