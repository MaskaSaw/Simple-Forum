<?php
	session_start(); //стартуем сессию, иначе будет ошибка при попытке разрушить
	
	$host = 'localhost';
	$database = 'series';
	$user = 'root';
	$result = '';
	$login = $_SESSION['login'];

	$link = mysqli_connect($host, $user, '', $database)
	or die("Ошибка".mysqli_error($link));
	$query = 'SELECT*FROM users WHERE login="'.$login.'" ';
	$key = '';
	$query = 'UPDATE users SET cookie="'.$key.'" WHERE login="'.$login.'"';
	mysqli_query($link, $query);

	session_destroy(); //разрушаем сессию для пользователя
	header("Location: Auth.php");
	exit;
?>