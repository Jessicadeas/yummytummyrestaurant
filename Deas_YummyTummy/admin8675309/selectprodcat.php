<?php
$pagetitle = 'Select Category';
require_once 'header.php';
require_once 'connect.php';

$errormsg = "";
$showform = 1;

$sqlselectc = "SELECT * from prodcats";
$resultc = $db->prepare($sqlselectc);
$resultc->execute();

if ($visible == 1)
{
	?>
	<table border>
	<tr>
		<th>Category</th>
		<th>Edit</th>
	</tr>
	<?php 
		while ( $row = $resultc-> fetch() )
			{
				echo '<tr><td>' . $row['dbprodcatname'] . '</td><td>' .
								
				'<form action = "updateprodcat.php" method = "post">
						<input type = "hidden" name = "prodcatid" value = "'
						. $row['dbprodcatid'] . 
						'"><input type="submit" name = "theedit" value="Edit">
				</form>'  . '</td></tr>' ;
			}
		?>
	</table>
<?php
}
include_once 'footer.php';
?>