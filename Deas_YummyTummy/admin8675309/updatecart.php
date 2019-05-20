<?php
$pagetitle = 'Update Cart';
require_once 'header.php';
require_once 'connect.php';

$errormsg = "";
	
		if( isset($_POST['theedit']) ) {
			$showform = 1;
			$formfield['ffcartid'] = $_POST['cartid'];
			$sqlselect = 'SELECT * from cartinfo where dbcartid = :bvcartid';
			$result = $db->prepare($sqlselect);
			$result->bindValue(':bvcartid', $formfield['ffcartid']);
			$result->execute();
			$row = $result->fetch(); 
		}
	
		if( isset($_POST['thesubmit']) )
		{	
			$showform = 2;
			$formfield['ffcartid'] = $_POST['cartid'];
			echo '<p>The form was submitted.</p>';

			//Data Cleansing
			$formfield['ffcartpickup'] = trim($_POST['cartpickup']);
			
			if ($_POST['cartdate'] != '') {
				$formfield['ffcartdate'] = date_create(trim($_POST['cartdate']));
				$formfield['ffcartdate']  = date_format($formfield['ffcartdate'], 'Y-m-d');
			} 
		
			/*  ****************************************************************************
     		CHECK FOR EMPTY FIELDS
    		Complete the lines below for any REQIURED form fields only.
			Do not do for optional fields.
    		**************************************************************************** */
			if(empty($formfield['ffcartpickup'])){$errormsg .= "<p>Your pickup choice is empty.</p>";}
			if(empty($formfield['ffcartdate'])){$errormsg .= "<p>Your cart date is empty.</p>";}
			
			/*  ****************************************************************************
			DISPLAY ERRORS
			If we have concatenated the error message with details, then let the user know
			**************************************************************************** */
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
					$sqlinsert = 'update cartinfo set dbcartpickup = :bvcartpickup,
								  dbcartdate = :bvcartdate
								  where dbcartid = :bvcartid';
					$stmtinsert = $db->prepare($sqlinsert);
					$stmtinsert->bindvalue(':bvcartpickup', $formfield['ffcartpickup']);
					$stmtinsert->bindvalue(':bvcartdate', $formfield['ffcartdate']);
					$stmtinsert->bindvalue(':bvcartid', $formfield['ffcartid']);
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

	if ($showform == 1  && $visible == 1 && $_SESSION['loginpermit'] == 1)
	{
	?>
		<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="theform">
			<fieldset><legend>Cart Information</legend>
				<table border>
					<tr>
						<th>Pick a Delivery Type:</th>
						<td><input type="radio" name="cartpickup" id="cartpickup" 
									value=1 <?php if ($row['dbcartpickup'] == 1) { echo ' checked';} ?> />
							<label for="pickup">Pickup</label>
							<input type="radio" name="cartpickup" id="cartpickup" 
									value=2 <?php if ($row['dbcartpickup'] == 2) { echo ' checked';} ?>/>
									<label for="delivery">Delivery</label>
							</td>
					</tr>
					<tr>
						<?php
						$dateholder = date_create($row['dbcartdate']);
						$dateholder = date_format($dateholder, 'Y-m-d');
						?>
						<th>Date of Cart</th>
						<td><input type="date" name="cartdate" id="cartdate" 
							value="<?php echo $dateholder ?>"></td>
					</tr>
				</table>
				<input type="hidden" name = "cartid" value=<?php echo $formfield['ffcartid'] ?>>
				<input type="submit" name = "thesubmit" value="Enter">
			</fieldset>
		</form>
	<?php
	}
	else if ($showform == 2 && $visible == 1) {
	?>

		<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="theform">
			<fieldset><legend>Cart Information</legend>
					<table border>
					<tr>
						<th>Pick a Delivery Type:</th>
						<td><input type="radio" name="cartpickup" id="cartpickup" 
									value=1 <?php if ($formfield['ffcartpickup'] == 1) { echo ' checked';} ?> />
							<label for="pickup">Pickup</label>
							<input type="radio" name="cartpickup" id="cartpickup" 
									value=2 <?php if ($formfield['ffcartpickup'] == 2) { echo ' checked';} ?> />
									<label for="delivery">Delivery</label>
							</td>
					</tr>
					<tr>
						<th>Date of Order</th>
						<td><input type="date" name="cartdate" id="cartdate" 
							value="<?php echo $formfield['ffcartdate'] ?>"></td>
					</tr>
				</table>
				<input type="hidden" name = "cartid" value=<?php echo $formfield['ffcartid'] ?>>
				<input type="submit" name = "thesubmit" value="Enter">
			</fieldset>
		</form>
	<?php
}
include_once 'footer.php';
?>