<?php
$pagetitle = 'Make Cart';
require_once 'header.php';
require_once 'connect.php';

$errormsg = "";
$showform = 1;

   if( isset($_POST['themake']) )
		{
			$showform = 0;
			$carttotal = 0;
			$formfield['ffcartid'] = $_POST['cartid'];
			
			$sqlselecto = "SELECT cartitems.*, ourproducts.dbourproductname
				FROM cartitems, our products
				WHERE ourproducts.dbourproductid = cartitems.dbourproductid
				AND cartitems.dbcartid = :bvcartid";
				$resulto = $db->prepare($sqlselecto);
				$resulto->bindValue(':bvcartid', $formfield['ffcartid']);
				$resulto->execute();
						
			echo '<h2>Review Items, when cart is made select button below</h2>
				<table border>
				<tr>
				<th>Item</th>
				<th>Price</th>
				<th>Notes</th>
				</tr>';
				
				while ($rowo = $resulto->fetch() )
				{
					$carttotal = $carttotal + $rowo['dbcartitemprice'];
					
					echo '<tr><td>' . $rowo['dbourproductname']. '</td><td>'
							.$rowo['dbcartitemprice']. '</td>';
					echo '<td>' . $rowo['dbcartitemnotes'] . '</td></tr>';
					
					echo '<tr><th>Total</th>';
					echo '<th>' . $carttotal . '</th><td></td></tr>';
					echo '</table><br><br>';
					
					echo '<form action="makecart.php" method = "post">
						<input type = "hidden name = "cartid" value = "'
										.$formfield['ffcartid'].
						'"><input type="submit" name="makecomplete" value="Mark Cart as Made">
						</form>';
				}
				
				if( isset($_POST['makecomplete']) )
				{
					$formfield['ffcartid'] = $_POST['cartid'];
					$showform = 0;
					
					$sqlinsert = 'update cartinfo set dbcartmade = :bvcartmade
								where dbcartid = :bvcartid';
					$stmtinsert = $db->prepare($sqlinsert);
					$stmtinsert->bindValue(':bvcartmade', 1);
					$stmtinsert->bindValue(':bvcartid', $formfield['ffcartid']);
					$stmtinsert->execute();
					
					echo '<h2>Your cart has been made. Thank you!</h2>';
				}
				
				
				if( isset($_POST['makecomplete']) )
				{
					$formfield['ffcartid'] = $_POST['cartid'];
					$showform = 0;
			
	
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
			
		
			$sqlselect = "select cartinfo.*	from cartinfo
							WHERE cartinfo.dbcartmade = :bvcartmade"
							. $stringclause;
							
							
			$result = $db->prepare($sqlselect);
			
			$result->bindValue(':bvcartmade', 0);
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
							WHERE cartinfo.dbcartmade = :bvcartmade";
			$result = $db->prepare($sqlselect);
			
			$result->bindValue(':bvcartmade', 0);
			$result->execute();
			
		}

if ($visible == 1 && $_SESSION['loginpermit'] == 1 && $showform = 1)
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
		<th>Make Cart</th>
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
				'<form action="makecart.php" method = "post">
				<input type = "hidden" name = "cartid" value = "'
						. $row['dbcartid'] .
				'"><input type="submit" name="themake" value="View Cart Items to Make">
				</form>'
				. '</td></tr>';
			}
		?>
	</table>
<?php
}
include_once 'footer.php';
?>