<?php
  
$account = db::get_string("SELECT `ID` FROM `USERS` WHERE `ID` = ? LIMIT 1", [intval(get('id'))]);  
  
html::title(lg('Подписчики %s', user::login_mini($account['ID'])));
acms_header();

if (!isset($account['ID'])){
  
  error('Такого пользователя не существует');
  redirect('/id'.$account['ID']);

}

if (MANAGEMENT == 0 && user('ID') != $account['ID'] && db::get_column("SELECT COUNT(*) FROM `USERS_SETTINGS` WHERE `USER_ID` = ? AND `SUBSCRIBERS_PRIVATE` = '0' LIMIT 1", [$account['ID']]) == 1){
  
  error('Пользователь запретил просматривать список подписчиков');
  redirect('/id'.$account['ID']);

}

$subscriptions = db::get_column("SELECT COUNT(`ID`) FROM `SUBSCRIBERS` WHERE `MY_ID` = ?", [$account['ID']]);
$subscribers = db::get_column("SELECT COUNT(`ID`) FROM `SUBSCRIBERS` WHERE `USER_ID` = ?", [$account['ID']]);
  
?> 
<div class='menu-nav-content'>  
<a class='menu-nav h' href='/account/subscribers/?id=<?=$account['ID']?>'>
<?=lg('Подписчики')?> <span class='menu-nav-count'><?=$subscribers?></span>
</a>  
<a class='menu-nav' href='/account/subscribers/subscriptions/?id=<?=$account['ID']?>'>
<?=lg('Подписки')?> <span class='menu-nav-count'><?=$subscriptions?></span>
</a>  
</div>
<?
  
define('URL_FRSCB', '/account/subscribers/?id='.$account['ID']);
require_once (ROOT.'/modules/users/plugins/friends_and_subscribers.php');  

$spage = spage($subscribers, PAGE_SETTINGS);
$page = page($spage);
$limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;

if ($subscribers == 0){ 
  
  html::empty('Пока пусто');
  
}else{
  
  ?><div class='list-body'><?
  
}

$data = db::get_string_all("SELECT * FROM `SUBSCRIBERS` WHERE `USER_ID` = ? ORDER BY `ID` DESC LIMIT ".$limit.", ".PAGE_SETTINGS, [$account['ID']]);
while ($list2 = $data->fetch()) {
  
  $list['ID'] = $list2['MY_ID'];
  require (ROOT.'/modules/users/plugins/users_list.php');
  
}

if ($subscribers > 0){

  ?></div><?
  
}

get_page('/account/subscribers/?id='.$account['ID'].'&', $spage, $page, 'list');

back('/id'.$account['ID']);
acms_footer();