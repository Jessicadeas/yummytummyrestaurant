<?php
	require_once "header.php";
	require_once "connect.php";

	//NECESSARY VARIABLES
	$errormsg = "";
	$showform = 0;
	//DATABASE CONNECTION
	require_once "connect.php";

		if( isset($_POST['theedit']) ) {
			$showform = 1;
			$formfield['ffprodcatid'] = $_POST['prodcatid'];
			$sqlselect = 'SELECT * from prodcats where dbprodcatid = :bvprodcatid';
			$result = $db->prepare($sqlselect);
			$result->bindValue(':bvprodcatid', $formfield['ffprodcatid']);
			$result->execute();
			$row = $result->fetch(); 
		}
	
		if( isset($_POST['thesubmit']) )
		{	
			$showform = 2;
			$formfield['ffprodcatid'] = $_POST['prodcatid'];
			$formfield['ffprodcatname'] = $_POST['prodcatname'];
			echo '<p>The form was submitted.</p>';
	
			
			if(empty($formfield['ffprodcatname'])){$errormsg .= "<p>Your category is empty.</p>";}
			
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
					$sqlinsert = 'update prodcats set dbprodcatname = :bvprodcatname
								  where dbprodcatid = :bvprodcatid';
					$stmtinsert = $db->prepare($sqlinsert);
					$stmtinsert->bindvalue(':bvprodcatname', $formfield['ffprodcatname']);
					$stmtinsert->bindvalue(':bvprodcatid', $formfield['ffprodcatid']);
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
	if ($visible == 1)
	{

	if ($showform == 1 && $_SESSION['loginpermit'] == 1)
	{
	?>
				<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="theform">
			<fieldset><legend>Category Information</legend>
				<table border>
					<tr>
						<th>Category</th>
						<td><input type="text" name="prodcatname" id="prodcatname"
						value = "<?php echo $row['dbprodcatname']; ?>"	></td>
					</tr>
					
				</table>
				<input type="hidden" name = "prodcatid" value=<?php echo $formfield['ffprodcatid'] ?>>
				<input type="submit" name = "thesubmit" value="Enter">
			</fieldset>
		</form>
	<?php
	}
	else if ($showform == 2) {
	?>

				<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="theform">
			<fieldset><legend>Category Information</legend>
				<table border>
					<tr>
						<th>Category</th>
						<td><input type="text" name="prodcatname" id="prodcatname"
						value = "<?php echo $formfield['ffprodcatname']; ?>"	></td>
					</tr>
					
				</table>
				<input type="hidden" name = "prodcatid" value=<?php echo $formfield['ffprodcatid'] ?>>
				<input type="submit" name = "thesubmit" value="Enter">
			</fieldset>
		</form>
	<?php
		}
		else {
		echo "You do not have permission to update";
		}
	?>	
	
<?php
}//visible
include_once 'footer.php';
?>