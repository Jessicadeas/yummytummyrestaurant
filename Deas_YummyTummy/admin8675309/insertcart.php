<?php
	$pagetitle = 'Insert Cart';
	require_once "header.php";
	require_once "connect.php";
	$showform = 1;
	
	if (isset($_POST['thesubmit']) )
	{
		$formfield['ffcartpickup'] = $_POST['cartpickup'];
			
		if(empty($formfield['ffcartpickup'])) {
			$errormsg .= "<p>You Have not Selected a Delivery Option</p>";
		}
				
		if ($errormsg != "") {
			echo "YOU HAVE ERRORS!!!!";
			echo $errormsg;
		} 
		else
		{			
			$sqlmax = "SELECT MAX(dbcartid) AS maxid from cartinfo";
			$resultmax = $db->prepare($sqlmax);
			$resultmax->execute();
			$rowmax = $resultmax->fetch();
			$maxid = $rowmax["maxid"];	
			$maxid = $maxid + 1;

			$sqlinsert = 'INSERT INTO cartinfo 
				(dbcartid, dbcartuser, dbcartcomplete, dbcartpickup, dbcartdate) 
				VALUES (:bvcartid, :bvcartuser, 0, :bvcartpickup, now())';
			
			$stmtinsert = $db->prepare($sqlinsert);
			$stmtinsert->bindvalue(':bvcartid', $maxid);
			$stmtinsert->bindvalue(':bvcartuser', $_SESSION['loginid']);
			$stmtinsert->bindvalue(':bvcartpickup', $formfield['ffcartpickup']);
			$stmtinsert->execute();
			
			echo "Cart Number: " . $maxid;
			echo '<br><br><form action="insertcartitem.php" method = "post">';
			echo '<input type = "hidden" name = "cartid" value = "'. $maxid .'">';
			echo '<input type="submit" name="thesubmit" value="Enter Cart Items">';
			echo "</form>";
			$showform = 0;
		}
	}
	

if ($visible == 1 && $showform == 1 && $_SESSION['loginpermit'] == 1)
{
?>


	<form action="<?php echo $_SERVER['PHP_SELF'];?>" method = "post">
		<fieldset><legend>Cart Info</legend>
		
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
			</tr>
		</table>
		<input type="submit" name="thesubmit" value="Enter">
		</fieldset>
	</form>

<?php
}//visible
include_once 'footer.php';
?>