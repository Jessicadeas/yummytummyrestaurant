<?php
	$pagetitle = "Update User Info";
	require_once 'header.php';
	require_once "connect.php";
	
	$errormsg = "";
	
	$sqluserpermit = 'select * from userpermit';
	$resultup = $db->prepare($sqluserpermit);
	$resultup->execute();
	
		if( isset($_POST['theedit']) )
		{
			$showform = 1;
			$formfield['ffuserid'] = $_POST['userid'];
			$sqlselect = 'SELECT * from userinfo where this = :bvuserid';
			$result = $db->prepare($sqlselect);
			$result->bindValue(':bvuserid', $formfield['ffuserid']);
			$result->execute();
			$row = $result->fetch(); 
		}

		if( isset($_POST['XXsubmit']) )
		{
			echo '<p>The form was submitted.</p>';
			$showform = 2;
			$formfield['ffuserid'] = $_POST['userid'];

			//Data Cleansing
			$formfield['ffname'] = trim($_POST['XXname']);
			$formfield['ffemail'] = trim(strtolower($_POST['XXemail']));
			$formfield['ffpass'] = trim($_POST['XXpass']);
			$formfield['ffpass2'] = trim($_POST['XXpass2']);
			$formfield['ffgrade'] = $_POST['XXgrade'];
			$formfield['ffcolor'] = $_POST['XXcolor'];
			$formfield['ffdate'] = date('Y-m-d', strtotime($_POST['XXdate']));
			$formfield['ffcomments'] = trim($_POST['XXcomments']);
			$formfield['ffuserpermit'] = $_POST['XXuserpermit'];
			
			/*  ****************************************************************************
     		CHECK FOR EMPTY FIELDS
    		Complete the lines below for any REQIURED form fields only.
			Do not do for optional fields.
    		**************************************************************************** */
			if(empty($formfield['ffname'])){$errormsg .= "<p>Your name is empty.</p>";}
			if(empty($formfield['ffemail'])){$errormsg .= "<p>Your email is empty.</p>";}
			if(empty($formfield['ffpass'])){$errormsg .= "<p>Your password is empty.</p>";}
			if(empty($formfield['ffpass2'])){$errormsg .= "<p>Your confirm password is empty.</p>";}
			if(empty($formfield['ffgrade'])){$errormsg .= "<p>Your grade is empty.</p>";}
			if (!isset($formfield['ffcolor']) || empty($formfield['ffcolor']))
			{
				$errormsg .= "<p>Your color selection is empty.</p>";
			}
			if(empty($formfield['ffcomments'])){$errormsg .= "<p>Your comment is empty.</p>";}
			if($formfield['ffdate'] == ''){$errormsg .= "<p>Your date is empty.</p>";}
			
			if (!isset($formfield['ffuserpermit']) || empty($formfield['ffuserpermit']))
			{
				$errormsg .= "<p>Your user permission is empty.</p>";
			}
     		//CHECK FOR MATCHING PASSWORDS
			if($formfield['ffpass'] != $formfield['ffpass2'])
			{
				$errormsg .= "<p>Your passwords do not match.</p>";
			}
			
     		//VALIDATE THE EMAIL
    		if (!filter_var($formfield['ffemail'], FILTER_VALIDATE_EMAIL))
			{
				$errormsg .= "<p>Your email is not valid.</p>";
			}

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
				$options = [
					'cost' => 12,
					'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),
				];
				$encpass = password_hash($formfield['ffpass'], PASSWORD_BCRYPT, $options);

				try
				{
					//enter data into database
					$sqlinsert = 'update userinfo set dbfullname = :bvname, dbpassword = :bvpass,
									dbemail = :bvemail, dbcomments = :bvcomments, dbcolorpicked = :bvcolor,
									dbclassgrade = :bvgrade, dbdate = :bvdate, dbuserpermit = :bvuserpermit
									WHERE this = :bvuserid';
					$stmtinsert = $db->prepare($sqlinsert);
					$stmtinsert->bindvalue(':bvname', $formfield['ffname']);
					$stmtinsert->bindvalue(':bvemail', $formfield['ffemail']);
					$stmtinsert->bindvalue(':bvpass', $encpass);
					$stmtinsert->bindvalue(':bvgrade', $formfield['ffgrade']);
					$stmtinsert->bindvalue(':bvcolor', $formfield['ffcolor']);
					$stmtinsert->bindvalue(':bvdate', $formfield['ffdate']);
					$stmtinsert->bindvalue(':bvcomments', $formfield['ffcomments']);
					$stmtinsert->bindvalue(':bvuserpermit', $formfield['ffuserpermit']);
					$stmtinsert->bindvalue(':bvuserid', $formfield['ffuserid']);
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
			<fieldset><legend>Search Personal Information</legend>
				<table border>
					<tr>
						<th><label for="XXname">Name:</label></th>
						<td><input type="text" name="XXname" id="XXname" size="10" value="<?php echo $row['dbfullname'];?>"/></td>
					</tr>
					<tr>
						<th><label for="XXemail">Email:</label></th>
						<td><input type="text" name="XXemail" id="XXemail" value="<?php echo $row['dbemail'];?>" /></td>
					</tr>
					<tr>
						<th><label for="XXpass">Password:</label></th>
						<td><input type="password" name="XXpass" id="XXpass" value="" /></td>
					</tr>
					<tr>
						<th><label for="XXpass2">Confirm Password:</label></th>
						<td><input type="password" name="XXpass2" id="XXpass2" value="" /></td>
					</tr>
					<tr>
						<th><label for="XXgrade">Class Grade:</label></th>
						<td><select name="XXgrade" id="XXgrade">
								<option value="" <?php if( $row['dbclassgrade'] == "" ){echo ' selected';}?>>SELECT ONE</option>
								<option value="FR" <?php if( $row['dbclassgrade']  == "FR" ){echo ' selected';}?>>Freshman</option>
								<option value="SO" <?php if( $row['dbclassgrade']  == "SO" ){echo ' selected';}?>>Sophomore</option>
								<option value="JR" <?php if( $row['dbclassgrade']  == "JR" ){echo ' selected';}?>>Junior</option>
								<option value="SR" <?php if( $row['dbclassgrade']  == "SR" ){echo ' selected';}?>>Senior</option>
							</select>
						</td>
					</tr>
					<tr>
						<th>Pick a color:</th>
						<td><input type="radio" name="XXcolor" id="colorred" 
									value="red" <?php if( $row['dbcolorpicked'] == "red" ){echo ' checked';}?> /><label for="colorred">Red</label>
							<input type="radio" name="XXcolor" id="colorgreen" 
									value="green" <?php if( $row['dbcolorpicked'] == "green" ){echo ' checked';}?>/><label for="colorgreen">Green</label>
							<input type="radio" name="XXcolor" id="colorblue" 
									value="blue" <?php if( $row['dbcolorpicked'] == "blue" ){echo ' checked';}?>/><label for="colorblue">Blue</label>
						</td>
					</tr>
					<tr>
						<th><label for="XXdate">Date:</label></th>
						<td><input type = "date" name="XXdate" id="XXdate" value ="<?php echo $row['dbdate'];?>" /></td>
					</tr>
					<tr>
						<th><label for="XXcomments">Comments:</label></th>
						<td><textarea name="XXcomments" id="XXcomments"><?php echo $row['dbcomments'];?></textarea></td>
					</tr>
					<tr>
						<th><label>Permissions:</label></th>
						<td><select name="XXuserpermit" id="XXuserpermit">
						<option value = "">Please Select a Permission</option>
						<?php while ($rowup = $resultup->fetch() )
							{
							if ($rowup['dbpermitid'] == $row['dbuserpermit'])
								{$checker = 'selected';}
							else {$checker = '';}
							echo '<option value="'. $rowup['dbpermitid'] . '" ' . $checker . '>' . $rowup['dbpermitlevel'] . '</option>';
							}
						?>
						</select>
						</td>
					</tr>
					<tr>
						<th>Submit:</th>
						<td>
						<input type="hidden" name = "userid" value=<?php echo $formfield['ffuserid'] ?>>
						<input type="submit" name="XXsubmit" value="SELECT" />
						</td>
					</tr>
				</table>
			</fieldset>
		</form>
<?php
	}
else if ($showform == 2  && $visible == 1)
	{
	?>
	<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="XXform">
			<fieldset><legend>Search Personal Information</legend>
				<table border>
					<tr>
						<th><label for="XXname">Name:</label></th>
						<td><input type="text" name="XXname" id="XXname" size="10" value="<?php if( isset($formfield['ffname'])){echo $formfield['ffname'];}?>"/></td>
					</tr>
					<tr>
						<th><label for="XXemail">Email:</label></th>
						<td><input type="text" name="XXemail" id="XXemail" value="<?php if( isset($formfield['ffemail'])){echo $formfield['ffemail'];}?>" /></td>
					</tr>
					<tr>
						<th><label for="XXpass">Password:</label></th>
						<td><input type="password" name="XXpass" id="XXpass" value="<?php if( isset($formfield['ffpass'])){echo $formfield['ffpass'];}?>" /></td>
					</tr>
					<tr>
						<th><label for="XXpass2">Confirm Password:</label></th>
						<td><input type="password" name="XXpass2" id="XXpass2" value="<?php if( isset($formfield['ffpass2'])){echo $formfield['ffpass'];}?>" /></td>
					</tr>
					<tr>
						<th><label for="XXgrade">Class Grade:</label></th>
						<td><select name="XXgrade" id="XXgrade">
								<option value="" <?php if( isset($formfield['ffgrade']) && $formfield['ffgrade'] == "" ){echo ' selected';}?>>SELECT ONE</option>
								<option value="FR" <?php if( isset($formfield['ffgrade']) && $formfield['ffgrade'] == "FR" ){echo ' selected';}?>>Freshman</option>
								<option value="SO" <?php if( isset($formfield['ffgrade']) && $formfield['ffgrade'] == "SO" ){echo ' selected';}?>>Sophomore</option>
								<option value="JR" <?php if( isset($formfield['ffgrade']) && $formfield['ffgrade'] == "JR" ){echo ' selected';}?>>Junior</option>
								<option value="SR" <?php if( isset($formfield['ffgrade']) && $formfield['ffgrade'] == "SR" ){echo ' selected';}?>>Senior</option>
							</select>
						</td>
					</tr>
					<tr>
						<th>Pick a color:</th>
						<td><input type="radio" name="XXcolor" id="colorred" 
									value="red" <?php if( isset($formfield['ffcolor']) && $formfield['ffcolor'] == "red" ){echo ' checked';}?> />
							<label for="colorred">Red</label>
							<input type="radio" name="XXcolor" id="colorgreen" 
									value="green" <?php if( isset($formfield['ffcolor']) && $formfield['ffcolor'] == "green" ){echo ' checked';}?>/><label for="colorgreen">Green</label>
							<input type="radio" name="XXcolor" id="colorblue" 
									value="blue" <?php if( isset($formfield['ffcolor']) && $formfield['ffcolor'] == "blue" ){echo ' checked';}?>/><label for="colorblue">Blue</label>
						</td>
					</tr>
					<tr>
						<th><label for="XXdate">Date:</label></th>
						<td><input type = "date" name="XXdate" id="XXdate" value ="<?php if( isset($formfield['ffdate'])){echo $formfield['ffdate'];}?>" /></td>
					</tr>
					<tr>
						<th><label for="XXcomments">Comments:</label></th>
						<td><textarea name="XXcomments" id="XXcomments"><?php if( isset($formfield['ffcomments'])){echo $formfield['ffcomments'];}?></textarea></td>
					</tr>
					<tr>
						<th><label>Permissions:</label></th>
						<td><select name="XXuserpermit" id="XXuserpermit">
						<option value = "">Please Select a Permission</option>
						<?php while ($rowup = $resultup->fetch() )
							{
							if ($rowup['dbpermitid'] == $formfield['ffuserpermit'])
								{$checker = 'selected';}
							else {$checker = '';}
							echo '<option value="'. $rowup['dbpermitid'] . '" ' . $checker . '>' . $rowup['dbpermitlevel'] . '</option>';
							}
						?>
						</select>
						</td>
					</tr>
					<tr>
						<th>Submit:</th>
						<td>
						<input type="hidden" name = "userid" value=<?php echo $formfield['ffuserid'] ?>>
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