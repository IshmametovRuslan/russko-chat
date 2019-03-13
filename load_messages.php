<?php
include ("db.php");

$res = mysqli_query($db_connect,"SELECT * FROM `messages` ORDER BY `id`");

while ($d = mysqli_fetch_array($res)) {
	echo "<p><b>".$d['login'].": </b><span>".$d['message']."</span></p>";
}
