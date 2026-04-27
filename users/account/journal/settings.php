<?php  
html::title('Настройки журнала');
acms_header();
access('users');

function notif($param) {
  
  $notif = db::get_string("SELECT * FROM `NOTIFICATIONS_SETTINGS` WHERE `USER_ID` = ? LIMIT 1", [user('ID')]);
  
  return $notif[$param];
  
}

?>
<div class='list-body'>
<?=direct::components(ROOT.'/users/account/journal/components/set/', 0)?>
</div>
<?

back('/account/journal/');
acms_footer();