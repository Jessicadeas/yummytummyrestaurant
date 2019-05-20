<?php
$pagetitle = 'Select Ticket';
require_once 'header.php';
require_once 'connect.php';

$errormsg = "";
$showform = 1;
	
	if( isset($_POST['thesubmit']) )
		{
			$stringclause = "";
			$formfield['ffticketpickup'] = $_POST['ticketpickup'];
			
			if ($formfield['ffticketpickup'] != '') {
				$stringclause .= " AND ticketOption = :bvticketpickup";
			}
			
			if ($_POST['ticketDate'] != '') {
				$formfield['ffticketDate'] = date_create(trim($_POST['ticketDate']));
				$formfield['ffticketDate']  = date_format($formfield['ffticketDate'], 'Y-m-d');
				$stringclause = " AND tickets.ticketDate like CONCAT('%', :bvticketDate, '%')";
			} 
			
		
			$sqlselect = "select tickets.* FROM tickets
							WHERE tickets.ticketEmp = :bvcartuser"
							. $stringclause;
							
			$result = $db->prepare($sqlselect);
			
			$result->bindValue(':bvcartuser', $_SESSION['loginid']);
			if ($formfield['ffticketpickup'] != '') {
				$result->bindValue(':bvticketpickup', $formfield['ffticketpickup']);
			}
			if ($formfield['ffticketDate'] != '') {
				$result->bindValue(':bvticketDate', $formfield['ffticketDate']);
			}
			
			$result->execute();
			
		}
	else
		{
			$sqlselect = "select tickets.*	from tickets
							WHERE tickets.ticketEmp = :bvcartuser";
			$result = $db->prepare($sqlselect);
			
			$result->bindValue(':bvcartuser', $_SESSION['loginid']);
			$result->execute();
			
		}

if ($visible == 1)
{		
	?>
	<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="theform">
			<fieldset><legend>Ticket Information</legend>
				<table border>
					<tr>
						<th>Pick a Delivery Type:</th>
						<td><input type="radio" name="ticketpickup" id="ticketpickup" 
									value=1 />
							<label for="pickup">Pickup</label>
							<input type="radio" name="ticketpickup" id="ticketpickup" 
									value=2 />
									<label for="delivery">Delivery</label>
							</td>
					</tr>
					<tr>
						<th>Date of Ticket</th>
						<td><input type="date" name="ticketDate" id="ticketDate"></td>
					</tr>
				</table>
				<input type="submit" name = "thesubmit" value="Enter">
			</fieldset>
		</form>
			<br><br>
	<table border>
	<tr>
		<th>Ticket ID</th>
		<th>Employee</th>
		<th>Date and Time</th>
		<th>Ticket Complete</th>
		<th>Pickup Or Delivery</th>
		<th>Total</th>
		<th>Edit</th>
		<th>Edit Ticket Items</th>
	</tr>
	<?php 
		while ( $row = $result-> fetch() )
			{
				if($row['TicketComplete'] == 0) {
					$opencart = "NO";
				} else {
					$opencart= "YES";
				}

				if($row['ticketOption'] == 1) {
					$pord = "PICKUP";
				} else {
					$pord= "DELIVERY";
				}
				
				$sqlselectci = "SELECT tickets.*
					FROM tickets
					WHERE tickets.ticketNum = :bvticketNum";
					$resultci = $db->prepare($sqlselectci);
					$resultci->bindValue(':bvticketNum', $row['ticketNum']);
					$resultci->execute();
				
				$ordertotal = 0;
				while ( $rowci = $resultci-> fetch() ) {
					$tickettotal = $tickettotal + $rowci['ticketitemprice'];
				}
				
				echo '<tr><td>' . $row['ticketNum'] . '</td><td> '  . $row['ticketEmp']
				. '</td><td> '  . $row['ticketDate'] 
				. '</td><td> '  . $opencart . '</td><td> '  . $pord . 
				'</td><td> ' . $tickettotal . '</td><td> ' .
				'<form action = "updateticket.php" method = "post">
						<input type = "hidden" name = "ticketid" value = "'
						. $row['ticketNum'] . 
						'"><input type="submit" name = "theedit" value="Edit">
				</form>' .
				'</td><td> ' .
				'<form action="insertticketitems.php" method = "post">
				<input type = "hidden" name = "ticketitemid" value = "'
						. $row['ticketNum'] .
				'"><input type="submit" name="thesubmit" value="Edit Ticket Items">
				</form>'
				. '</td></tr>';
			}
		?>
	</table>
<?php
}
include_once 'footer.php';
?>