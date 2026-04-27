<?php
$account = db::get_string("SELECT * FROM `USERS` WHERE `ID` = ? LIMIT 1", [intval(get('id'))]);
$settings = db::get_string("SELECT * FROM `USERS_SETTINGS` WHERE `USER_ID` = ? LIMIT 1", [$account['ID']]);  

acms_header(lg('Страница %s', user::login_mini($account['ID'])));

if (!isset($account['ID'])){
  
  error('Неверная директива');
  redirect('/');

}

if ($account['ID'] != user('ID') && user('ID') > 0){
  
  if (db::get_column("SELECT COUNT(*) FROM `USERS_GUESTS` WHERE `USER_ID` = ? AND `MY_ID` = ? LIMIT 1", [user('ID'), $account['ID']]) == 0){
    
    db::get_add("INSERT INTO `USERS_GUESTS` (`USER_ID`, `MY_ID`, `TIME`) VALUES (?, ?, ?)", [user('ID'), $account['ID'], TM]);
  
  }else{
    
    db::get_set("UPDATE `USERS_GUESTS` SET `COUNT` = `COUNT` + '1', `TIME` = ?, `READ` = '1' WHERE `USER_ID` = ? AND `MY_ID` = ? LIMIT 1", [TM, user('ID'), $account['ID']]);
  
  }
  
}

if (config('SYSTEM') == $account['ID'] && $account['ID'] != user('ID')) {
  
  ?>
  <div class="list" <?=(MANAGEMENT == 1 ? 'style="margin-bottom: 12px"' : null)?>>
  <center>
  <?=user::avatar($account['ID'], 80)?><br />
  <?=user::login($account['ID'], 0, 1)?><br /><br />
  <span class='time'><?=lg('Это административный аккаунт и он используется только для оповещений')?></span>
  <?php if (MANAGEMENT == 1) : ?>
  <br /><br />
  <?=lg('Данные аккаунта закрыты для всех пользователей, кроме создателя и системных администраторов')?>
  <?php endif ?>
  </center>
  </div>
  <?
    
  if (MANAGEMENT == 0) { acms_footer(); }
  
}

require_once (ROOT.'/users/account/page/plugins/block.php');
require_once (ROOT.'/users/account/page/plugins/private.php');

if (direct::e_file('style/version/'.version('DIR').'/includes/page.php') == true) {
  
  require (ROOT.'/style/version/'.version('DIR').'/includes/page.php');
  acms_footer();
  
}

?><div class='pofile'><?

require_once (ROOT.'/users/account/page/plugins/screensaver.php');
require_once (ROOT.'/users/account/page/plugins/avatar.php');
require_once (ROOT.'/users/account/page/plugins/name_user.php');
require_once (ROOT.'/users/account/page/plugins/menu.php');
require_once (ROOT.'/users/account/page/plugins/menu_user.php');
require_once (ROOT.'/users/account/page/plugins/info_form.php');

?></div><?
  
hooks::challenge('account', 'account');
hooks::run('account');
  
require_once (ROOT.'/users/account/page/plugins/photos.php'); 
require_once (ROOT.'/users/account/page/plugins/blogs.php');  

acms_footer();