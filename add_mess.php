<?php

if ( isset( $_POST['mess'] ) && $_POST['mess'] != '' && $_POST['mess'] != ' ' ) {
	session_start();

	$mess = $_POST['mess'];

	$login = $_SESSION['login'];

	include "db.php";

	$res = mysqli_query( $db_connect, "INSERT INTO `messeges` (`login`,`message`) VALUES ('$login','$mess')" );

}
