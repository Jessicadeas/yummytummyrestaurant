<?php
if(isset($_SESSION['cartloginid']))
{
	echo "<a href='frontindex.php'>Home</a>  |";
	echo "<a href='update.php'>Update User Info</a>  |";
	echo "<a href='insertticket.php'>New Ticket</a>  |";
	echo "<a href='selectticket.php'>Search Tickets</a> |";
	
	echo "<a href='logout.php'>Log Out</a>";
	echo ' [ Welcome, ' . $_SESSION['cartloginname'] . ' ] ';
	$visible = 1;
}
else
{
	echo "<a href='login.php'>Log In</a>";
	$visible = 0;
}
?>