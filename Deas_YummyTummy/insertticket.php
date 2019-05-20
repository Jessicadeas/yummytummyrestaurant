<?php
	$pagetitle = 'Insert Ticket';
	require_once "header.php";
	require_once "connect.php";
	$showform = 1;
	
	
	if (isset($_POST['thesubmit']) )
	{
		$formfield['ffticketpickup'] = $_POST['ticketOption'];
	
	if(empty($formfield['ffticketpickup'])) {
			$errormsg .= "<p>You Have not Selected a Delivery Option</p>";
		
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
				(ticketNum, ticketUser, ticketDate, ticketOption, ticketComplete) 
				VALUES (:bvticketit, :bvticketUser, now(), :bvticketOption, 0)';
			
			$stmtinsert = $db->prepare($sqlinsert);
			$stmtinsert->bindvalue(':bvticketit', $maxid);
			$stmtinsert->bindvalue(':bvticketUser', $_SESSION['cartloginid']);
			$stmtinsert->bindvalue(':bvticketOption', $formfield['ffticketpickup']);
			$stmtinsert->execute();
			
			echo "Ticket Number: " . $maxid;
			echo '<br><br><form action="insertticketitems.php" method = "post">';
			echo '<input type = "hidden" name = "ticketid" value = "'. $maxid .'">';
			echo '<input type="submit" name="thesubmit" value="Enter Ticket Items">';
			echo "</form>";
			$showform = 0;
		}
		
			
		}
	

if ($visible == 1 && $showform == 1 && $_SESSION['cartloginpermit'] == 5)
{
?>


	<form action="<?php echo $_SERVER['PHP_SELF'];?>" method = "post">
		<fieldset><legend>Ticket Info</legend>
		
		<table border>
					<tr>
						<th>Pick a Delivery Type:</th>
						<td><input type="radio" name="ticketOption" id="ticketOption" 
									value=1 <?php echo ' checked';?> />
							<label for="pickup">Pickup</label>
							<input type="radio" name="ticketOption" id="ticketOption" 
									value=2 />
									<label for="delivery">Delivery</label>
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