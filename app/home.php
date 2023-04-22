<?php

	session_start();
	if (!isset($_SESSION['zalogowany'])){
		header('Location: index.php');
		exit();
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie-edge">
    <link rel="stylesheet" href="style.css">
    <title>Strona główna</title>
</head>

<body>
    <?php
       
       echo "<p>Witaj ". $_SESSION['user']."!"."</br>";
    ?>
    <button class="button_logout"><a class="logout" href="logout.php">Wyloguj</a></button>
</body>
</html>