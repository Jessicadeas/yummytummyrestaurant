<?php
$pagetitle = 'Select Cart';
require_once 'header.php';
require_once 'connect.php';

$errormsg = "";
$showform = 1;
	
	if( isset($_POST['thesubmit']) )
		{
			$stringclause = "";
			$formfield['ffcartpickup'] = $_POST['cartpickup'];
			
			if ($formfield['ffcartpickup'] != '') {
				$stringclause .= " AND dbcartpickup = :bvcartpickup";
			}
			
			if ($_POST['cartdate'] != '') {
				$formfield['ffcartdate'] = date_create(trim($_POST['cartdate']));
				$formfield['ffcartdate']  = date_format($formfield['ffcartdate'], 'Y-m-d');
				$stringclause = " AND cartinfo.dbcartdate like CONCAT('%', :bvcartdate, '%')";
			} 
			
		
			$sqlselect = "select cartinfo.* FROM cartinfo
							WHERE cartinfo.dbcartuser = :bvcartuser"
							. $stringclause;
							
			$result = $db->prepare($sqlselect);
			
			$result->bindValue(':bvcartuser', $_SESSION['loginid']);
			if ($formfield['ffcartpickup'] != '') {
				$result->bindValue(':bvcartpickup', $formfield['ffcartpickup']);
			}
			if ($formfield['ffcartdate'] != '') {
				$result->bindValue(':bvcartdate', $formfield['ffcartdate']);
			}
			
			$result->execute();
			
		}
	else
		{
			$sqlselect = "select cartinfo.*	from cartinfo
							WHERE cartinfo.dbcartuser = :bvcartuser";
			$result = $db->prepare($sqlselect);
			
			$result->bindValue(':bvcartuser', $_SESSION['loginid']);
			$result->execute();
			
		}

if ($visible == 1)
{		
	?>
	<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="theform">
			<fieldset><legend>Cart Information</legend>
				<table border>
					<tr>
						<th>Pick a Delivery Type:</th>
						<td><input type="radio" name="cartpickup" id="cartpickup" 
									value=1 />
							<label for="pickup">Pickup</label>
							<input type="radio" name="cartpickup" id="cartpickup" 
									value=2 />
									<label for="delivery">Delivery</label>
							</td>
					</tr>
					<tr>
						<th>Date of Cart</th>
						<td><input type="date" name="cartdate" id="cartdate"></td>
					</tr>
				</table>
				<input type="submit" name = "thesubmit" value="Enter">
			</fieldset>
		</form>
			<br><br>
	<table border>
	<tr>
		<th>Cart ID</th>
		<th>Date and Time</th>
		<th>Cart Complete</th>
		<th>Pickup Or Delivery</th>
		<th>Total</th>
		<th>Edit</th>
		<th>Edit Cart Items</th>
	</tr>
	<?php 
		while ( $row = $result-> fetch() )
			{
				if($row['dbcartcomplete'] == 0) {
					$opencart = "NO";
				} else {
					$opencart= "YES";
				}

				if($row['dbcartpickup'] == 1) {
					$pord = "PICKUP";
				} else {
					$pord= "DELIVERY";
				}
				
				$sqlselectci = "SELECT cartitems.*
					FROM cartitems
					WHERE cartitems.dbcartid = :bvcartid";
					$resultci = $db->prepare($sqlselectci);
					$resultci->bindValue(':bvcartid', $row['dbcartid']);
					$resultci->execute();
				
				$ordertotal = 0;
				while ( $rowci = $resultci-> fetch() ) {
					$carttotal = $carttotal + $rowci['dbcartitemprice'];
				}
				
				echo '<tr><td>' . $row['dbcartid'] . '</td><td> '  . $row['dbcartdate'] 
				. '</td><td> '  . $opencart . '</td><td> '  . $pord . 
				'</td><td> ' . $carttotal . '</td><td> ' .
				'<form action = "updatecart.php" method = "post">
						<input type = "hidden" name = "cartid" value = "'
						. $row['dbcartid'] . 
						'"><input type="submit" name = "theedit" value="Edit">
				</form>' .
				'</td><td> ' .
				'<form action="insertcartitem.php" method = "post">
				<input type = "hidden" name = "cartid" value = "'
						. $row['dbcartid'] .
				'"><input type="submit" name="thesubmit" value="Edit Cart Items">
				</form>'
				. '</td></tr>';
			}
		?>
	</table>
<?php
}
include_once 'footer.php';
?>