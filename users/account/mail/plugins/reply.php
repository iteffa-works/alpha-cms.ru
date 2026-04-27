<div id='reply_mess'>  

<?php 
  
if (get('reply_mess') && user('ID') > 0){
  
  get_check_valid();  
  session('REPLY_ID_MESS'.ACCOUNT_ID, intval(get('reply_mess')));
  
}

if (get('reply_mess_no') && user('ID') > 0){
  
  get_check_valid();  
  session('REPLY_ID_MESS'.ACCOUNT_ID, null);
    
}  
  
if (session('REPLY_ID_MESS'.ACCOUNT_ID) > 0){ 
  
  ?>
  <div class='reply'>
  <?=lg('Ответ на сообщение')?>
  <span class='reply-close' onclick="request('/account/mail/messages/?id=<?=ACCOUNT_ID?>&reply_mess_no=<?=intval(session('REPLY_ID_MESS'.ACCOUNT_ID))?>&<?=TOKEN_URL?>', '#reply_mess')"><?=icons('times', 17)?></a>
  </div>
  <?
  
}

?></div>