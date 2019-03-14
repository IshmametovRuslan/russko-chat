<?php
include 'db.php';

/**
 * Функция определения запрошенной страницы
 *
 */
function getPageName() {
	if ( ! empty( $_GET['p'] ) ) {
		$page = $_GET['p'];
	} else {
		$page = 'index';
	}

	return $page;
}


/**
 * Функция проверки на наличие файла
 */
function filePresence( $filename ) {
	if ( file_exists( $filename ) ) {
		include $filename;
	} else {
		include '404.php';
	}
}

/**
 * Функция подключения файла в зависимости от URL'a
 */
function getPage() {

	$page = getPageName();
	switch ( $page ) {
		case 'register':
			filePresence( 'register.php' );
			break;
		case 'login':
			filePresence( 'login.php' );
			break;
	}
}

/**
 * Функция определения дирктории сайта
 *
 */
function getRootPath() {
	$path = dirname( __FILE__ );

	return $path;
}

getRootPath();

/**
 *
 *
 */

/**
 * Функция регистрации пользователя
 *
 */
function regUser() {

	if ( isset( $_POST['login'] ) && isset( $_POST['password'] ) && isset( $_POST['action'] ) && $_POST['action'] == 'registration' ) {

		global $db_connect;

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
}

regUser();

/**
 * Функция авторизации пользователя
 *
 */
function userLogin() {

	if ( isset( $_POST['login'] ) && isset( $_POST['password'] ) && isset( $_POST['action'] ) && $_POST['action'] == 'login') {

		global $db_connect;

		include 'db.php';

		$login    = htmlspecialchars( trim( $_POST['login'] ) );
		$password = md5( htmlspecialchars( trim( $_POST['password'] ) ) );

		$res  = mysqli_query( $db_connect, "SELECT * FROM `users` WHERE `login`='$login' AND `password`='$password'" );
		$data = mysqli_fetch_array( $res );

		if ( empty( $data['login'] ) ) {
			die( 'Такого пользователя не существует' );
		}

		if ( empty( $data['password'] ) ) {
			die( 'Пароль не верен!' );
		}

		session_start();

		$_SESSION['login'] = $data['login'];
		$_SESSION['id']    = $data['id'];

		header( "location: index.php" );
	}
}
userLogin();