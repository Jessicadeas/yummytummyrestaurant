<?php
	$pagetitle = "Select User Info";
	require_once 'header.php';

	$errormsg = "";
	$showform = 1;

	require_once "connect.php";

	$sqluserpermit = 'select * from userpermit';
	$resultup = $db->prepare($sqluserpermit);
	$resultup->execute();
	
	if( isset($_POST['XXsubmit']) )
		{
			
			$stringclause = '';
			$formfield['ffname'] = trim($_POST['XXname']);
			$formfield['ffemail'] = trim(strtolower($_POST['XXemail']));
			$formfield['ffpass'] = trim($_POST['XXpass']);
			$formfield['ffgrade'] = $_POST['XXgrade'];
			$formfield['ffcolor'] = $_POST['XXcolor'];
			if ($_POST['XXdate'] != '') {
				$formfield['ffdate'] = date("Y-m-d", strtotime($_POST['XXdate']));
			} else {
				$formfield['ffdate'] = '';
			}
			$formfield['ffcomments'] = trim($_POST['XXcomments']);
			$formfield['ffuserpermit'] = $_POST['XXuserpermit'];
			
			if ($formfield['ffgrade'] != '') {
				$stringclause .= " AND dbclassgrade = :bvgrade";
			}
			
			if ($formfield['ffcolor'] != '') {
				$stringclause .= " AND dbcolorpicked = :bvcolor";
			}
			
			if ($formfield['ffdate'] != '') {
				$stringclause .= " AND dbdate = :bvdate";
			}	
			
			if ($formfield['ffuserpermit'] != '') {
				$stringclause .= " AND dbuserpermit = :bvuserpermit";
			}	
						
			$sqlselect = "SELECT userinfo.*, userpermit.dbpermitlevel
							FROM userinfo, userpermit
							where userinfo.dbuserpermit = userpermit.dbpermitid
							AND dbfullname like CONCAT('%', :bvname, '%')
							AND dbemail like CONCAT('%', :bvemail, '%')
							AND dbpassword like CONCAT('%', :bvpass, '%')
							AND dbcomments like CONCAT('%', :bvcomments, '%')" . $stringclause;
			
			$result = $db->prepare($sqlselect);
			
			$result->bindValue(':bvname', $formfield['ffname']);
			$result->bindValue(':bvemail', $formfield['ffemail']);
			$result->bindValue(':bvpass', $formfield['ffpass']);
			if ($formfield['ffgrade'] != '') {
				$result->bindValue(':bvgrade', $formfield['ffgrade']);
			}
			if ($formfield['ffcolor'] != '') {
				$result->bindValue(':bvcolor', $formfield['ffcolor']);
			}
			if ($formfield['ffdate'] != '') {
				$result->bindValue(':bvdate', $formfield['ffdate']);
			}
			if ($formfield['ffuserpermit'] != '') {
				$result->bindValue(':bvuserpermit', $formfield['ffuserpermit']);
			}
			$result->bindValue(':bvcomments', $formfield['ffcomments']);
						
			$result->execute();
		}
	else
		{
			$sqlselect = "SELECT userinfo.*, userpermit.dbpermitlevel
							FROM userinfo, userpermit
							where userinfo.dbuserpermit = userpermit.dbpermitid";
			$result = $db-> query($sqlselect);
		}
if($_SESSION['loginpermit'] == 1 || $_SESSION['loginpermit'] == 2)
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
						<td><input type="submit" name="XXsubmit" value="SELECT" /></td>
					</tr>
				</table>
			</fieldset>
		</form>
	<br><br>
	<table border>
	<tr>
		<th>Name</th>
		<th>E-Mail</th>
		<th>Grade</th>
		<th>Color</th>
		<th>Date</th>
		<th>Comments</th>
		<th>Permission</th>
		<th>Edit</th>
	</tr>
	<?php 
		while ( $row = $result-> fetch() )
			{
				echo '<tr><td>' . $row['dbfullname'] . '</td><td> ' . $row['dbemail'] 
				. '</td><td> ' . $row['dbclassgrade'] 
				. '</td><td> ' . $row['dbcolorpicked'] . '</td><td> ' . $row['dbdate']
				. '</td><td> ' . $row['dbcomments'] . '</td><td> ' . $row['dbpermitlevel'] . 
				'</td><td>' .
				'<form action = "update.php" method = "post">
						<input type = "hidden" name = "userid" value = "'
						. $row['this'] . 
						'"><input type="submit" name = "theedit" value="Edit">
				</form>'  .
				
				'</td></tr>';
			}
		?>
	</table>
<?php
	}
include_once 'footer.php';
?>