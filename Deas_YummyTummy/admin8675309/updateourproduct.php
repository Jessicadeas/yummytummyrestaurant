<?php
$pagetitle = 'Update Product';
require_once 'header.php';
require_once 'connect.php';

$errormsg = "";
$sqlselectc = "SELECT * from prodcats";
$resultc = $db->prepare($sqlselectc);
$resultc->execute();
	
		if( isset($_POST['theedit']) ) {
			$showform = 1;
			$formfield['ffourproductid'] = $_POST['ourproductid'];
			$sqlselect = 'SELECT * from ourproducts where dbourproductid = :bvourproductid';
			$result = $db->prepare($sqlselect);
			$result->bindValue(':bvourproductid', $formfield['ffourproductid']);
			$result->execute();
			$row = $result->fetch(); 
		}
	
		if( isset($_POST['thesubmit']) )
		{	
			$showform = 2;
			$formfield['ffourproductid'] = $_POST['ourproductid'];
			echo '<p>The form was submitted.</p>';

			//Data Cleansing
			$formfield['ffourproductname'] = trim($_POST['ourproductname']);
			$formfield['ffourproductdescr'] = trim($_POST['ourproductdescr']);
			$formfield['ffourproductcost'] = trim(strtolower($_POST['ourproductcost']));
			$formfield['ffourproductcat'] = trim($_POST['ourproductcat']);
		
			/*  ****************************************************************************
     		CHECK FOR EMPTY FIELDS
    		Complete the lines below for any REQIURED form fields only.
			Do not do for optional fields.
    		**************************************************************************** */
			if(empty($formfield['ffourproductname'])){$errormsg .= "<p>Your product name field is empty.</p>";}
			if(empty($formfield['ffourproductdescr'])){$errormsg .= "<p>Your description is empty.</p>";}
			if(empty($formfield['ffourproductcost'])){$errormsg .= "<p>Your price is empty.</p>";}
			if(empty($formfield['ffourproductcat'])){$errormsg .= "<p>Your category is empty.</p>";}
			
			/*  ****************************************************************************
			DISPLAY ERRORS
			If we have concatenated the error message with details, then let the user know
			**************************************************************************** */
			if($errormsg != "")
			{
				echo "<div class='error'><p>THERE ARE ERRORS!</p>";
				echo $errormsg;
				echo "</div>";
			}
			else
			{
				try
				{
					//enter data into database
					$sqlinsert = 'update ourproducts set dbourproductname = :bvourproductname,
								  dbourproductdescr = :bvourproductdescr,
								  dbourproductcost = :bvourproductcost,
								  dbourproductcat = :bvourproductcat
								  where dbourproductid = :bvourproductid';
					$stmtinsert = $db->prepare($sqlinsert);
					$stmtinsert->bindvalue(':bvourproductname', $formfield['ffourproductname']);
					$stmtinsert->bindvalue(':bvourproductdescr', $formfield['ffourproductdescr']);
					$stmtinsert->bindvalue(':bvourproductcost', $formfield['ffourproductcost']);
					$stmtinsert->bindvalue(':bvourproductcat', $formfield['ffourproductcat']);
					$stmtinsert->bindvalue(':bvourproductid', $formfield['ffourproductid']);
					$stmtinsert->execute();
					echo "<div class='success'><p>There are no errors.  Thank you.</p></div>";
				}//try
				catch(PDOException $e)
				{
					echo 'ERROR!!!' .$e->getMessage();
					exit();
				}
			}//else statement end
		}//if isset submit

	if ($showform == 1  && $visible == 1 && $_SESSION['loginpermit'] == 1)
	{
	?>
		<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="theform">
			<fieldset><legend>Product Information</legend>
				<table border>
					<tr>
						<th>Product Name</th>
						<td><input type="text" name="ourproductname" id="ourproductname"
						value = "<?php echo $row['dbourproductname']; ?>"></td>
					</tr>
					<tr>
						<th>Description</th>
						<td><input type="text" name="ourproductdescr" id="ourproductdescr"
						value = "<?php echo $row['dbourproductdescr']; ?>"></td>
					</tr>
					<tr>
						<th>Price</th>
						<td><input type="text" name="ourproductcost" id="ourproductcost"
						value = "<?php echo $row['dbourproductcost']; ?>"></td>
					</tr>
					<tr>
						<th><label>Category:</label></th>
						<td><select name="ourproductcat" id="ourproductcat">
						<option value = "">Please Select a Category</option>
						<?php while ($rowc = $resultc->fetch() )
							{
							if ($rowc['dbprodcatid'] == $row['dbourproductcat'])
								{$checker = 'selected';}
							else {$checker = '';}
							echo '<option value="'. $rowc['dbprodcatid'] . '" ' . $checker . '>' . $rowc['dbprodcatname'] . '</option>';
							}
						?>
						</select>
						</td>
					</tr>
				</table>
				<input type="hidden" name = "ourproductid" value=<?php echo $formfield['ffourproductid'] ?>>
				<input type="submit" name = "thesubmit" value="Enter">
			</fieldset>
		</form>
	<?php
	}
	else if ($showform == 2 && $visible == 1 && $_SESSION['loginpermit'] == 1) {
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
				<input type="hidden" name = "ourproductid" value=<?php echo $formfield['ffourproductid'] ?>>
				<input type="submit" name = "thesubmit" value="Enter">
			</fieldset>
		</form>
	<?php
}
include_once 'footer.php';
?>