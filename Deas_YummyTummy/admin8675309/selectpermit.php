<?php
	$pagetitle = "Select Permission Info";
	require_once 'header.php';

	$errormsg = "";
	$showform = 1;

	require_once "connect.php";

	if( isset($_POST['XXsubmit']) )
		{
			
			$stringclause = '';
			$formfield['ffpermitlevel'] = trim($_POST['XXpermitlevel']);
						
			$sqlselect = "SELECT userpermit.*
							FROM userpermit
							where dbpermitlevel like CONCAT('%', :bvpermitlevel, '%')";
			
			$result = $db->prepare($sqlselect);
			
			$result->bindValue(':bvpermitlevel', $formfield['ffpermitlevel']);
									
			$result->execute();
		}
	else
		{
			$sqlselect = "SELECT userpermit.* FROM userpermit";
							
			$result = $db-> query($sqlselect);
		}
if($_SESSION['loginpermit'] == 1 || $_SESSION['loginpermit'] == 2)
	{	
	?>
	<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="XXform">
			<fieldset><legend>Search Permission Information</legend>
				<table border>
					<tr>
						<th><label for="XXpermitlevel">Permission Level:</label></th>
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
		<th>Edit</th>
	</tr>
	<?php 
		while ( $row = $result-> fetch() )
			{
				echo '<tr><td>' . $row['dbpermitid'] . '</td><td>' .
							$row['dbpermitlevel'] . '</td><td>' .
				'<form action = "updatepermit.php" method = "post">
						<input type = "hidden" name = "permitid" value = "'
						. $row['dbpermitid'] . 
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