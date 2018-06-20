<?php

session_start(); 
if (!empty($_SESSION['auth']) and $_SESSION['auth']) {
ob_start();
include("lab_7.html");
$text = ob_get_clean();
$temp = '';
$login = $_SESSION['login'];

$host = 'localhost';
$database = 'series';
$user = 'root';

$link = mysqli_connect($host, $user, '', $database)
	or die("Ошибка".mysqli_error($link));
	
//Функция удаления элемента
function  delete_from_sql($link, $field, $current_field) {
	$query = "DELETE FROM messages WHERE $field='$current_field'";
	$result = mysqli_query($link, $query)
		or die("Ошибка".mysqli_error($link));
}

$query = "SELECT * FROM messages";
$result = mysqli_query($link, $query)
	or die("Ошибка".mysqli_error($link));

if($result){
		$temp.='<p style=" margin: auto ;width: 500px; text-align: center; font-size: 25px; align-content: center; left: 50%">'."Сообщения на форуме\n".'</p>';
		$temp.='<br>';
		$temp.='<table border="1"; cellpadding="0" style="margin: 0 auto; width: 1300px; min-height: 100px ; text-align: center">'.'<tr>';
 		$temp.= '<th>'."Пользователь".'</th>';
 		$temp.= '<th>'."Сообщение".'</th>'.'<tr>';
		while($row = mysqli_fetch_array($result)){
			$temp.= '<td>'.$row["login"].'</td>';
			$temp.= '<td>'.$row["message"].'</td>'.'<tr>';
		}
		$temp.= '</table>';

 $text = preg_replace('/{Answers}/', $temp, $text);
 echo $text;
}

//добавление новой записи
if (isset($_POST['Add'])){
	$message = '';

	if ($_POST['message'] != ''){
		$message = $_POST['message'];
		$query = "INSERT INTO messages VALUES('$login', '$message')";
		$result = mysqli_query($link, $query)
			or die("Ошибка".mysqli_error($link));
		header("Location: lab_7p.php");
	}	
	else
	{
		echo "Заполните  поле";
	}	
}


//удаление
if (isset($_POST['Delete'])) {
	if  ($login != 'admin')
	{
		echo "Вы не можете удалять сообщения, так как не являетесь администратором";
		return;
	}
	if ($_POST['message'] !='' && $login == 'admin') {
		delete_from_sql($link, 'message', $_POST['message']);
	}
	else
	{
		echo "Заполните поле удаления";
	}
	header("Location: lab_7p.php");
}

if (isset($_POST['Refresh'])) {
	header("Location: lab_7p.php");
}

mysqli_close($link);
}
else if (empty($_SESSION['auth']) or $_SESSION['auth'] == false) {
		//Проверяем, не пустые ли нужные нам куки...
		if ( !empty($_COOKIE['login']) and !empty($_COOKIE['key']) ) {
			//Пишем логин и ключ из КУК в переменные (для удобства работы):
			$host = 'localhost';
			$database = 'series';
			$user = 'root';

			$link = mysqli_connect($host, $user, '', $database)
				or die("Ошибка".mysqli_error($link));
			$login = $_COOKIE['login']; 
			$key = $_COOKIE['key'];
			$query = 'SELECT*FROM users WHERE login="'.$login.'" AND cookie="'.$key.'"';
			$result = mysqli_fetch_assoc(mysqli_query($link, $query)); 

			if (!empty($result)) {
				//Стартуем сессию:
				session_start(); 

				//Пишем в сессию информацию о том, что мы авторизовались:
				$_SESSION['auth'] = true; 
				$_SESSION['login'] = $user['login']; 
				header("Location: lab_7p.php");
		}

			else
{
	echo "Вы не были авторизованы";
	echo '<a href="Auth.php"> Авторизация </a>';
}
	}

}
?>