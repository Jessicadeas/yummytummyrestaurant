<?php
	$pagetitle = "Update Permission Info";
	require_once 'header.php';
	require_once "connect.php";
	
	$errormsg = "";
	
	if( isset($_POST['theedit']) )
		{
			$showform = 1;
			$formfield['ffpermitid'] = $_POST['permitid'];
			$sqlselect = 'SELECT * from userpermit where dbpermitid = :bvpermitid';
			$result = $db->prepare($sqlselect);
			$result->bindValue(':bvpermitid', $formfield['ffpermitid']);
			$result->execute();
			$row = $result->fetch(); 
		}

		if( isset($_POST['XXsubmit']) )
		{
			echo '<p>The form was submitted.</p>';
			$showform = 2;
			$formfield['ffpermitid'] = $_POST['permitid'];
			$formfield['ffpermitlevel'] = $_POST['XXpermitlevel'];
			
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
					$sqlinsert = 'update userpermit set dbpermitlevel = :bvpermitlevel
									WHERE dbpermitid = :bvpermitid';
					$stmtinsert = $db->prepare($sqlinsert);
					$stmtinsert->bindvalue(':bvpermitlevel', $formfield['ffpermitlevel']);
					$stmtinsert->bindvalue(':bvpermitid', $formfield['ffpermitid']);
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
if($_SESSION['loginpermit'] == 1)
	{
if ($showform == 1)
	{
	?>
	<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="XXform">
			<fieldset><legend>Update Permission Information</legend>
				<table border>
					<tr>
						<th><label for="XXpermitlevel">Permission Level:</label></th>
						<td><input type="text" name="XXpermitlevel" id="XXpermitlevel" size="10"
								value="<?php echo $row['dbpermitlevel'];?>"/></td>
					</tr>
					<tr>
						<th>Submit:</th>
						<td>
						<input type="hidden" name = "permitid" value=<?php echo $formfield['ffpermitid'] ?>>
						<input type="submit" name="XXsubmit" value="SELECT" />
						</td>
					</tr>
				</table>
			</fieldset>
		</form>
<?php
	}
else if ($showform == 2)
	{
	?>
	<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="XXform">
			<fieldset><legend>Update Permission Information</legend>
				<table border>
					<tr>
						<th><label for="XXpermitlevel">Permission Level:</label></th>
						<td><input type="text" name="XXpermitlevel" id="XXpermitlevel" size="10"
							value="<?php if( isset($formfield['ffpermitlevel'])){echo $formfield['ffpermitlevel'];}?>"/></td>
					</tr>
					<tr>
						<th>Submit:</th>
						<td>
						<input type="hidden" name = "permitid" value=<?php echo $formfield['ffpermitid'] ?>>
						<input type="submit" name="XXsubmit" value="SELECT" />
						</td>
					</tr>
				</table>
			</fieldset>
		</form>
<?php
	}
	}
include_once 'footer.php';
?>