<?php
$pagetitle = 'Update ticket';
require_once 'header.php';
require_once 'connect.php';

$errormsg = "";
	
		if( isset($_POST['theedit']) ) {
			$showform = 1;
			$formfield['ffticketNum'] = $_POST['ticketNum'];
			$sqlselect = 'SELECT * from tickets where ticketNum = :bvticketNum';
			$result = $db->prepare($sqlselect);
			$result->bindValue(':bvticketNum', $formfield['ffticketNum']);
			$result->execute();
			$row = $result->fetch(); 
		}
	
		if( isset($_POST['thesubmit']) )
		{	
			$showform = 2;
			$formfield['ffticketNum'] = $_POST['ticketNum'];
			echo '<p>The form was submitted.</p>';

			//Data Cleansing
			$formfield['ffticketpickup'] = trim($_POST['ticketpickup']);
			
			if ($_POST['ticketdate'] != '') {
				$formfield['ffticketdate'] = date_create(trim($_POST['ticketdate']));
				$formfield['ffticketdate']  = date_format($formfield['ffticketdate'], 'Y-m-d');
			} 
		
	//check for empty fields
    	
		if(empty($formfield['ffticketpickup'])){$errormsg .= "<p>Your pickup choice is empty.</p>";}
			if(empty($formfield['ffticketdate'])){$errormsg .= "<p>Your ticket date is empty.</p>";}
			
	//check for errors
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
					$sqlinsert = 'update tickets set ticketOption = :bvticketpickup,
								  ticketDate = :bvticketdate
								  where ticketNum = :bvticketNum';
					$stmtinsert = $db->prepare($sqlinsert);
					$stmtinsert->bindvalue(':bvticketpickup', $formfield['ffticketpickup']);
					$stmtinsert->bindvalue(':bvticketdate', $formfield['ffticketdate']);
					$stmtinsert->bindvalue(':bvticketNum', $formfield['ffticketNum']);
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

	if ($showform == 1  && $visible == 1 && $_SESSION['cartloginpermit'] == 5)
	{
	?>
		<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="theform">
			<fieldset><legend>Ticket Information</legend>
				<table border>
					<tr>
						<th>Pick a Delivery Type:</th>
						<td><input type="radio" name="ticketpickup" id="ticketpickup" 
									value=1 <?php if ($row['ticketOption'] == 1) { echo ' checked';} ?> />
							<label for="pickup">Pickup</label>
							<input type="radio" name="ticketpickup" id="ticketpickup" 
									value=2 <?php if ($row['ticketOption'] == 2) { echo ' checked';} ?>/>
									<label for="delivery">Delivery</label>
							</td>
					</tr>
					<tr>
						<?php
						$dateholder = date_create($row['ticketDate']);
						$dateholder = date_format($dateholder, 'Y-m-d');
						?>
						<th>Date of ticket</th>
						<td><input type="date" name="ticketdate" id="ticketdate" 
							value="<?php echo $dateholder ?>"></td>
					</tr>
				</table>
				<input type="hidden" name = "ticketNum" value=<?php echo $formfield['ffticketNum'] ?>>
				<input type="submit" name = "thesubmit" value="Enter">
			</fieldset>
		</form>
	<?php
	}
	else if ($showform == 2 && $visible == 1) {
	?>

		<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="theform">
			<fieldset><legend>Ticket Information</legend>
					<table border>
					<tr>
						<th>Pick a Delivery Type:</th>
						<td><input type="radio" name="ticketpickup" id="ticketpickup" 
									value=1 <?php if ($formfield['ffticketpickup'] == 1) { echo ' checked';} ?> />
							<label for="pickup">Pickup</label>
							<input type="radio" name="ticketpickup" id="ticketpickup" 
									value=2 <?php if ($formfield['ffticketpickup'] == 2) { echo ' checked';} ?> />
									<label for="delivery">Delivery</label>
							</td>
					</tr>
					<tr>
						<th>Date of Order</th>
						<td><input type="date" name="ticketdate" id="ticketdate" 
							value="<?php echo $formfield['ffticketdate'] ?>"></td>
					</tr>
				</table>
				<input type="hidden" name = "ticketNum" value=<?php echo $formfield['ffticketNum'] ?>>
				<input type="submit" name = "thesubmit" value="Enter">
			</fieldset>
		</form>
	<?php
}
include_once 'footer.php';
?>