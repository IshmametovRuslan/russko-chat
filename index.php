<?php session_start(); ?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport"
	      content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel="stylesheet" href="css/style.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
	<title>Russko-chat</title>

</head>
<body>

<div class="page">
	<?php

	if ( ! isset( $_SESSION['login'] ) && ! isset( $_SESSION['id'] ) ) {
		?>
		<div class="reg-form-block">
			<form action="register.php" id="reg-form" method="post">
				<h4>Зарегестрируйтесь</h4>
				<lable>Логин:</lable>
				<input type="text" name="login">
				<lable>Пароль:</lable>
				<input type="password" name="password">
				<lable>Имя:</lable>
				<input type="text" name="name">
				<lable>Фамилия:</lable>
				<input type="text" name="surname">
				<lable>Город:</lable>
				<input type="text" name="city">
				<input type="submit" value="Зарегестрироваться">
			</form>
		</div>
		<div class="auth-form-block">
			<form action="login.php" method="post">
				<h4>Вход</h4>
				<lable>Логин:</lable>
				<input type="text" name="login">
				<lable>Пароль:</lable>
				<input type="password" name="password">
				<input type="submit" value="Вход">
			</form>
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
	?>
</div>
</body>
</html>