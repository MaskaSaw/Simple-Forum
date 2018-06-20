<?php
$host = 'localhost';
$database = 'series';
$user = 'root';
$result = '';

$link = mysqli_connect($host, $user, '', $database)
	or die("Ошибка".mysqli_error($link));

if (isset($_POST['Enter'])){

	if ( !empty($_REQUEST['Password']) and !empty($_REQUEST['Login']) ) {
		//Пишем логин и пароль из формы в переменные (для удобства работы):
		$login = $_REQUEST['Login']; 
		$password = $_REQUEST['Password'];
		$query = 'SELECT*FROM users WHERE login="'.$login.'" AND password="'.$password.'"';
			$result = mysqli_query($link, $query); //ответ базы запишем в переменную $result
		$user = mysqli_fetch_assoc($result);
		if (!empty($user)) {
			session_start(); 
			$_SESSION['auth'] = true;  
			$_SESSION['login'] = $user['login'];
			$key = 'Yes';
			setcookie('login', $user['login'], time()+60*60*24*30); //логин
			setcookie('key', $key, time()+60*60*24*30);
			$query = 'UPDATE users SET cookie="'.$key.'" WHERE login="'.$login.'"';
			mysqli_query($link, $query);
			header("Location: lab_7p.php");
			exit;
		} 
		else {
			//Пользователь неверно ввел логин или пароль, выполним какой-то код.
			echo "Неправильные имя пользователя или пароль";
		}
	}
}


if (isset($_POST['Register'])){
if ( !empty($_REQUEST['Password']) and !empty($_REQUEST['Login']) ) {
		//Пишем логин и пароль из формы в переменные (для удобства работы):
		$login = $_REQUEST['Login']; 
		$password = $_REQUEST['Password']; 
		$id = "5";

		/*
			Формируем и отсылаем SQL запрос:
			ВСТАВИТЬ В таблицу_users УСТАНОВИТЬ логин = $login И пароль = $password
		*/
		$query = "INSERT INTO users VALUES('$id','$login', '$password', '')";
		$result = mysqli_query($link, $query)
			or die("Ошибка".mysqli_error($link)); 

		//Выведем сообщение об успешной регистрации:
		echo 'Вы успешно зарегистрированы!';
	}
	//Не заполнено какого-либо из полей...
	else {
		echo 'Поля не могут быть пустыми!';
	}
}
?>
<form action="Auth.php" method="POST" style="margin: 0 auto; width: 600px; text-align: center">
<div style="margin-top: 10px">
		<input class="inp" type="Text" placeholder="Логин" name="Login">
		<input class="inp" type="Text" placeholder="Пароль" name="Password">
		<input type="submit" name="Enter" value="Войти">
		<input type="submit" name="Register" value="Зарегистрироваться">
</div>
<?php
 	
?>