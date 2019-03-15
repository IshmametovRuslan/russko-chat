<?php
include "db.php";
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
 *Функция Запуска сессии
 *
 */
function addSession() {

	global $db_connect;

	if ( ! isset( $_SESSION['login'] ) && ! isset( $_SESSION['id'] ) ) {
		?>
		<div class="link">
			<a href="?p=login">Вход</a>
			<a href="?p=register">Регистрация</a>
		</div>
		<?php
	}
	if ( isset( $_SESSION['login'] ) && isset( $_SESSION['id'] ) ) {

		include 'db.php';
		$user      = $_SESSION['login'];
		$res       = mysqli_query( $db_connect, "SELECT * FROM `users` WHERE `login` = '$user'" );
		$user_data = mysqli_fetch_array( $res ); ?>

		<div class="user-data">
			<p>Ваш логин: <b><?php echo $user_data['login']; ?></b></p>
			<p>Ваше имя: <b><?php echo $user_data['name']; ?></b></p>
			<p>Ваша фамилия: <b><?php echo $user_data['surname']; ?></b></p>
			<p>Место жительства: <b><?php echo $user_data['city']; ?></b></p>
			<p><a href="exit.php">Выход</a></p>
		</div>
		<?php
		include 'chat.php';
	}
}

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
		global $text_alarm;

		if ( $result == true ) {
			header( "location: ?p=login" );
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

	if ( isset( $_POST['login'] ) && isset( $_POST['password'] ) && isset( $_POST['action'] ) && $_POST['action'] == 'login' ) {

		global $db_connect;

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

/**
 * Функция добавления сообщения в БД
 */
function addMsg() {

	global $db_connect;

	if ( isset( $_POST['mess'] ) && $_POST['mess'] != '' && $_POST['mess'] != ' ' ) {

		session_start();

		$mess  = $_POST['mess'];
		$login = $_SESSION['login'];
		$res   = mysqli_query( $db_connect, "INSERT INTO `messages` (`login`,`message`) VALUES ('$login','$mess')" );

	}

	return $res;
}

addMsg();

/**
 * Функция вывода сообщений на экран
 *
 */
function viewMsg() {
	if ( $_POST && $_POST['res'] == 'ok' ) {

		global $db_connect;

		$res = mysqli_query( $db_connect, "SELECT * FROM `messages` ORDER BY `id`" );

		while ( $d = mysqli_fetch_array( $res ) ) {
			echo "<div id='single-message'><p><b>" . $d['login'] . ": </b><span>" . $d['message'] . "</span></p></div>";
		}
	}
}

viewMsg();




