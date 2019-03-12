<?php

// Проверяем введены ли логин и пароль
if ( isset( $_POST['login'] ) && isset( $_POST['password'] ) ) {

	// Все данные записываем в переменные
	$login    = htmlspecialchars( trim( $_POST['login'] ) );
	$password = md5( htmlspecialchars( trim( $_POST['password'] ) ) );
	$name     = htmlspecialchars( trim( $_POST['name'] ) );
	$surname  = htmlspecialchars( trim( $_POST['surname'] ) );
	$city     = htmlspecialchars( trim( $_POST['city'] ) );


	//Проверяем заполнены ли все поля
	if ( $login == '' || $password == '' || $name == '' || $city == '' ) {
		die( 'Заполните все поля!' );
	}

	//Подключаем файл БД
	include 'db.php';

	//Делаем запрос к БД
	$res  = mysqli_query( $db_connect, "SELECT `login` FROM `users` WHERE `login` = '$login'" );
	$data = mysqli_fetch_array( $res );

	//Проверяем существует ли такой логин
	if ( ! empty( $data['login'] ) ) {
		die( 'Такой логин уже существует!' );
	}

	//Проверяем длину пароля
	if ( strlen( $password ) < 7 ) {
		echo $password;
		die( 'Пароль не должен быть меньше 7-ми символов!' );
	}

	//Вносим данные пользователя в БД
	$query  = "INSERT INTO `users` (`login`,`password`,`name`,`surname`,`city`) VALUES ('$login','$password','$name','$surname','$city')";
	$result = mysqli_query( $db_connect, $query );

	if ( $result == true ) {
		echo 'Вы успешно зарегестрированы! <a href="index.php">На главную</a>';
	} else {
		echo 'Ошибка! ----> ' . mysqli_error();
	}
}