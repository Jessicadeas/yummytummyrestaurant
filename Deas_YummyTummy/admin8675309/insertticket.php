<?php
	$pagetitle = 'Insert Ticket';
	require_once "header.php";
	require_once "connect.php";
	$showform = 1;
	$sqlselectc = "SELECT * from userinfo";
$resultc = $db->prepare($sqlselectc);
$resultc->execute();
	
	if (isset($_POST['thesubmit']) )
	{
		$formfield['ffcartpickup'] = $_POST['cartpickup'];
			$formfield['ffUser'] = trim($_POST['user']);
		if(empty($formfield['ffcartpickup'])) {
			$errormsg .= "<p>You Have not Selected a Delivery Option</p>";
			if(empty($formfield['ffUser'])){$errormsg .= "<p>Your user is empty.</p>";}
				
			if ($_POST['XXdate'] != '') {
				$formfield['ffdate'] = date("Y-m-d", strtotime($_POST['XXdate']));
			} else {
				$formfield['ffdate'] = '';
			}
		}
				
		if ($errormsg != "") {
			echo "YOU HAVE ERRORS!!!!";
			echo $errormsg;
		} 
		else
		{			
			$sqlmax = "SELECT MAX(ticketNum) AS maxid from tickets";
			$resultmax = $db->prepare($sqlmax);
			$resultmax->execute();
			$rowmax = $resultmax->fetch();
			$maxid = $rowmax["maxid"];	
			$maxid = $maxid + 1;

			$sqlinsert = 'INSERT INTO tickets 
				(ticketNum, ticketEmp, ticketUser, ticketDate, ticketOption, ticketComplete) 
				VALUES (:bvcartid, :bvticketEmp, :bvticketUser, now(), :bvcartpickup, 0)';
			
			$stmtinsert = $db->prepare($sqlinsert);
			$stmtinsert->bindvalue(':bvcartid', $maxid);
			$stmtinsert->bindvalue(':bvticketEmp', $_SESSION['loginid']);
			$stmtinsert->bindvalue(':bvticketUser', $formfield['ffUser']);
			$stmtinsert->bindvalue(':bvcartpickup', $formfield['ffcartpickup']);
			$stmtinsert->execute();
			
			echo "Cart Number: " . $maxid;
			echo '<br><br><form action="insertticketitems.php" method = "post">';
			echo '<input type = "hidden" name = "ticketid" value = "'. $maxid .'">';
			echo '<input type="submit" name="thesubmit" value="Enter Ticket Items">';
			echo "</form>";
		
			
		}
		$sqlselect = "SELECT userinfo.*, tickets.ticketNum
							FROM userinfo, tickets
							WHERE userinfo.this = tickets.ticketUser";

	$result = $db-> query($sqlselect);
	}
	

if ($visible == 1 && $showform == 1 && $_SESSION['loginpermit'] == 1)
{
?>


	<form action="<?php echo $_SERVER['PHP_SELF'];?>" method = "post">
		<fieldset><legend>Ticket Info</legend>
		
		<table border>
					<tr>
						<th>Pick a Delivery Type:</th>
						<td><input type="radio" name="cartpickup" id="cartpickup" 
									value=1 <?php echo ' checked';?> />
							<label for="pickup">Pickup</label>
							<input type="radio" name="cartpickup" id="cartpickup" 
									value=2 />
									<label for="delivery">Delivery</label>
							</td>
					</tr>
					<tr>
						<th><label>User:</label></th>
						<td><select name="user" id="user">
						<option value = "">Please Select a User</option>
						<?php while ($rowc = $resultc->fetch() )
							{
							if ($rowc['this'] == $formfield['ffUser'])
								{$checker = 'selected';}
							else {$checker = '';}
							echo '<option value="'. $rowc['this'] . '" ' . $checker . '>' . $rowc['dbfullname'] . '</option>';
							}
							?>
							</select>
							</td>
			</tr>
			<tr>
						<th><label for="XXdate">Date:</label></th>
						<td><input type = "date" name="XXdate" id="XXdate" value ="<?php if( isset($formfield['ffdate'])){echo $formfield['ffdate'];}?>" /></td>
					</tr>
		</table>
		<input type="submit" name="thesubmit" value="Enter">
		</fieldset>
	</form>

<?php
}//visible
include_once 'footer.php';
?>