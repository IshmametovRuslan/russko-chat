<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

<table>
	<tr>
		<td>
			<div id="messages"></div>
		</td>
	</tr>
	<tr>
		<td>
			<form action="javascript:send();">
				<input type="text" id="mess_to_send">
				<input type="button" id="sendBtn" value="Отправить">
			</form>
		</td>
	</tr>
</table>
<script type="text/javascript">

		$("#sendBtn").click(
			function(){
				send();
				return false;
			}
		);


	function send() {
		let mess = $( "#mess_to_send" ).val();


		$.ajax( {
			type : "POST",
			url : "add_mess.php",
			data : "mess=" + mess,
			success : function ( ) {
				load_messages();

				$( "#mess_to_send" ).val( '' );
			}
		} );
	}

	function load_messages() {

		$.ajax( {
			type : "POST",
			url : "load_messages.php",
			data : "res=ok",
			success : function ( html ) {
				$( "#messages" ).empty();
				$( "#messages" ).append( html );
				$( "#messages" ).scrollTop( 90000 );
			}
		} );
	}
</script>
<script>
	load_messages();

	setInterval(load_messages, 3000);

</script>