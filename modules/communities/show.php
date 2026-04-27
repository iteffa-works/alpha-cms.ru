<?php
$comm = db::get_string("SELECT * FROM `COMMUNITIES` WHERE `URL` = ? LIMIT 1", [esc(get('id'))]);
$par = db::get_string("SELECT `ADMINISTRATION`,`USER_ID`,`ID`,`ACT` FROM `COMMUNITIES_PAR` WHERE `COMMUNITY_ID` = ? AND `USER_ID` = ? AND `ACT` = ? LIMIT 1", [$comm['ID'], user('ID'), 1]);
acms_header(lg('Сообщество %s', communities::name($comm['ID'])));

if (!isset($comm['ID'])) {
  
  error('Неверная директива');
  redirect('/m/communities/');

}

if (config('PRIVATE_COMMUNITIES') == 0){
  
  error('Модуль отключен администратором');
  redirect('/');
  
}

require_once (ROOT.'/modules/communities/plugins/block.php');

if (direct::e_file('style/version/'.version('DIR').'/includes/community.php') == true) {
  
  require (ROOT.'/style/version/'.version('DIR').'/includes/community.php');
  acms_footer();
  
}

?><div class='pofile'><?

require_once (ROOT.'/modules/communities/plugins/screensaver.php');
require_once (ROOT.'/modules/communities/plugins/avatar.php');
require_once (ROOT.'/modules/communities/plugins/name_community.php');
require_once (ROOT.'/modules/communities/plugins/menu.php');
require_once (ROOT.'/modules/communities/plugins/menu_comm.php');
require_once (ROOT.'/modules/communities/plugins/info_comm.php');

?></div><?
  
require_once (ROOT.'/modules/communities/plugins/blogs.php');

acms_footer();