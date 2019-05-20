<?php
if(isset($_SESSION['loginid']))
{
	echo "<a href='insert.php'>Insert</a>  |";
	echo "<a href='select.php'>Search</a> |";
	echo "<a href='insertpermit.php'>Insert Permissions</a>  |";
	echo "<a href='selectpermit.php'>Search Permissions</a> |";
	echo "<a href='insertprodcat.php'>Insert Categories</a>  |";
	echo "<a href='selectprodcat.php'>Search Categories</a> |";
	echo "<a href='insertourproduct.php'>Insert Our Products</a>  |";
	echo "<a href='selectourproduct.php'>Search Our Products</a> |";
	echo "<a href='insertcart.php'>Insert Cart</a>  |";
	echo "<a href='selectcart.php'>Search Cart</a> |";
	echo "<a href='makecart.php'>Make Cart</a> |";
	echo "<a href='insertticket.php'>Insert Ticket</a>  |";
	echo "<a href='insertticketitems.php'>Insert Ticket Items</a>	|";
	echo "<a href='selectticket.php'>Search Tickets</a>  |";
	
	echo "<a href='logout.php'>Log Out</a>";
	echo ' [ Welcome, ' . $_SESSION['loginname'] . ' ] ';
	$visible = 1;
}
else
{
	echo "<a href='login.php'>Log In</a>";
	$visible = 0;
}
?>