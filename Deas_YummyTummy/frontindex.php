<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="utf-8" />
    <title>Yummy Tummy</title>
	<link rel="stylesheet" href="css/index.css" />
</head>
<body>
 <img src="images/burgerlogo.jpg" width="270" height="200" class="floatleft">
<header>
    <h1>Yummy Tummy!!</h1>
</header>
<nav>
    <?php include_once "menu.php";?>
</nav>
<section>
        <h2><?php echo $pagetitle; ?></h2>
    <article>
	

	</article>
<footer>
	<br><br>
    <?php echo '&copy;  2019' ?>
</footer>
</body>
</html>