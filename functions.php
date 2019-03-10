<?php

function msgSend() {
	if ( isset( $_POST['messageWrt'] ) && isset( $_POST['sendMessage'] ) ) {
		$messageTxt = $_POST['messageWrt'];
		echo $messageTxt;
	}
}