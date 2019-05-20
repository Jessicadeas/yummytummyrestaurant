<?php
	$pagetitle = "Insert Permission Info";
	require_once 'header.php';
	
	$errormsg = "";
	$showform = 1;
	
	require_once "connect.php";

		if( isset($_POST['XXsubmit']) )
		{
			echo '<p>The form was submitted.</p>';


			$formfield['ffpermitlevel'] = trim($_POST['XXpermitlevel']);

			if(empty($formfield['ffpermitlevel'])){$errormsg .= "<p>Your permission level is empty.</p>";}
			
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
					$sqlinsert = 'INSERT INTO userpermit (dbpermitlevel)
								  VALUES (:bvpermitlevel)';
					$stmtinsert = $db->prepare($sqlinsert);
					$stmtinsert->bindvalue(':bvpermitlevel', $formfield['ffpermitlevel']);
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

	$sqlselect = "SELECT userpermit.* FROM userpermit";
							
	$result = $db-> query($sqlselect);

if($_SESSION['loginpermit'] == 1)
	{
	?>
	<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="XXform">
			<fieldset><legend>Insert Permission Information</legend>
				<table border>
					<tr>
						<th><label for="XXname">Permission Level:</label></th>
						<td><input type="text" name="XXpermitlevel" id="XXpermitlevel" size="10" value="<?php if( isset($formfield['ffpermitlevel'])){echo $formfield['ffpermitlevel'];}?>"/></td>
					</tr>
					<tr>
						<th>Submit:</th>
						<td><input type="submit" name="XXsubmit" value="SELECT" /></td>
					</tr>
				</table>
			</fieldset>
		</form>
			<br><br>
	<table border>
	<tr>
		<th>Permission ID</th>
		<th>Permission Level</th>
	</tr>
	<?php 
		while ( $row = $result-> fetch() )
			{
				echo '<tr><td>' . $row['dbpermitid'] . '</td><td>' .
							$row['dbpermitlevel'] . '</td></tr>';
			}
		?>
	</table>
<?php
	}
include_once 'footer.php';
?>