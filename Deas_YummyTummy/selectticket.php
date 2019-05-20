<?php
$pagetitle = 'Search Tickets';
require_once 'header.php';
require_once 'connect.php';

$errormsg = "";
$showform = 1;
	
	if( isset($_POST['thesubmit']) )
		{
			$stringclause = "";
			$formfield['ffticketOption'] = $_POST['ticketOption'];
			
			if ($formfield['ffticketOption'] != '') {
				$stringclause .= " AND ticketOption = :bvticketOption";
			}
			
			if ($_POST['ticketDate'] != '') {
				$formfield['ffticketDate'] = date_create(trim($_POST['ticketDate']));
				$formfield['ffticketDate']  = date_format($formfield['ffticketDate'], 'Y-m-d');
				$stringclause = " AND tickets.ticketDate like CONCAT('%', :bvticketDate, '%')";
			} 
			
			
			$sqlselect = "select tickets.* FROM tickets
							WHERE tickets.ticketUser = :bvticketuser"
							. $stringclause;
							
			$result = $db->prepare($sqlselect);
			
			$result->bindValue(':bvticketuser', $_SESSION['cartloginid']);
			if ($formfield['ffticketOption'] != '') {
				$result->bindValue(':bvticketOption', $formfield['ffticketOption']);
			}
			if ($formfield['ffticketDate'] != '') {
				$result->bindValue(':bvticketDate', $formfield['ffticketDate']);
			}
			
			$result->execute();
			
		}
else
		{
			$sqlselect = "select tickets.*	from tickets
							WHERE tickets.ticketUser = :bvticketUser";
			$result = $db->prepare($sqlselect);
			
			$result->bindValue(':bvticketUser', $_SESSION['cartloginid']);
			$result->execute();
			
		}

if ($visible == 1 && $_SESSION['cartloginpermit'] ==5)
{		
	?>
	<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="theform">
			<fieldset><legend>Ticket Information</legend>
				<table border>
					<tr>
						<th>Pick a Delivery Type:</th>
						<td><input type="radio" name="ticketOption" id="ticketOption" 
									value=1 />
							<label for="pickup">Pickup</label>
							<input type="radio" name="ticketOption" id="ticketOption" 
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
					$openticket = "NO";
				} else {
					$openticket= "YES";
				}

				if($row['ticketOption'] == 1) {
					$pord = "PICKUP";
				} else {
					$pord= "DELIVERY";
				}
				
				$sqlselectci = "SELECT ticketItems.*
					FROM ticketItems
					WHERE ticketItems.ticketid = :bvticketNum";
					$resultci = $db->prepare($sqlselectci);
					$resultci->bindValue(':bvticketNum', $row['ticketNum']);
					$resultci->execute();
				
				$ordertotal = 0;
				while ( $rowci = $resultci-> fetch() ) {
					$tickettotal = $tickettotal + $rowci['ticketitemprice'];
				}
				
				echo '<tr><td>' . $row['ticketNum'] . '</td><td> '  . $row['ticketDate'] 
				. '</td><td> '  . $openticket . '</td><td> '  . $pord . 
				'</td><td> ' . $tickettotal . '</td><td> ' .
				'<form action = "updateticket.php" method = "post">
						<input type = "hidden" name = "ticketid" value = "'
						. $row['ticketNum'] . 
						'"><input type="submit" name = "theedit" value="Edit">
				</form>' .
				'</td><td> ' .
				'<form action="insertticketitems.php" method = "post">
				<input type = "hidden" name = "ticketNum" value = "'
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