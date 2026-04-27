<?php
acms_header('Гостевая');
is_active_module('PRIVATE_GUESTBOOK');

require_once (ROOT.'/modules/guestbook/plugins/delete.php');

if (user('ID') == 0){
  
  $comments_set = 'Извините, для вас комментирование недоступно';
  
}

comments('/m/guestbook/', 'guestbook', 1);

back('/', 'На главную');
acms_footer();