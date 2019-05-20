<?php
$pagetitle = 'Insert Category';
require_once 'header.php';
require_once 'connect.php';

$errormsg = "";
$showform = 1;

		if( isset($_POST['thesubmit']) )
		{
			echo '<p>The form was submitted.</p>';

			//Data Cleansing
			$formfield['ffprodcatname'] = trim($_POST['prodcatname']);
		
			/*  ****************************************************************************
     		CHECK FOR EMPTY FIELDS
    		Complete the lines below for any REQIURED form fields only.
			Do not do for optional fields.
    		**************************************************************************** */
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
					$sqlinsert = 'INSERT INTO prodcats (dbprodcatname)
								  VALUES (:bvprodcatname)';
					$stmtinsert = $db->prepare($sqlinsert);
					$stmtinsert->bindvalue(':bvprodcatname', $formfield['ffprodcatname']);
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


	$sqlselect = 'SELECT * from prodcats';

	$result = $db-> query($sqlselect);

if ($visible == 1 && $_SESSION['loginpermit'] == 1)
{
	?>
		<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="theform">
			<fieldset><legend>Category Information</legend>
				<table border>
					<tr>
						<th>Category</th>
						<td><input type="text" name="prodcatname" id="prodcatname"
						value = <?php echo $formfield['ffprodcatname']; ?>></td>
					</tr>
				</table>
				<input type="submit" name = "thesubmit" value="Enter">
			</fieldset>
		</form>
			<br><br>
	<table border>
	<tr>
		<th>Category ID</th>
		<th>Category Name</th>		
	</tr>
	<?php 
		while ( $row = $result-> fetch() )
			{
				echo '<tr><td>' . $row['dbprodcatid'] . '</td><td> ' . $row['dbprodcatname'] . '</td></tr> ';
			}
		?>
	</table>
<?php
}
include_once 'footer.php';
?>