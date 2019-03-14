function send() {
	let mess = $( "#mess_to_send" ).val();

	$.ajax( {
		type : "POST",
		url : "functions.php",
		data : { mess : mess },
		success : function () {
			load_messages();

			$( "#mess_to_send" ).val( '' );
		}
	} );
}

function load_messages() {

	$.ajax( {
		type : "POST",
		url : "functions.php",
		data : "res=ok",
		success : function ( html ) {
			$( "#messages" ).empty();
			$( "#messages" ).append( html );
			$( "#messages" ).scrollTop( 90000 );
		}
	} );
}
load_messages();

setInterval( load_messages, 1000 );
