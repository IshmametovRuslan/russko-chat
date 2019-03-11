<?php
if ( isset( $_POST['login'] ) && isset( $_POST['password'] ) ) {

	include 'db.php';

	$login    = htmlspecialchars( trim( $_POST['login'] ) );
	$password = htmlspecialchars( trim( $_POST['password'] ) );

	$res  = mysqli_query( "SELECT * FROM `users` WHERE `login`='$login'" );
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

	header("location: index.php");
}