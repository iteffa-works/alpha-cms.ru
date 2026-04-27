<?php if (user('ID') > 0) { ?>

var messages = $('#messages').attr('action');    
$('#messages').load(messages + " #messages");
                           
<?php } ?>