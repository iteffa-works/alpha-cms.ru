<?php  
$account = db::get_string("SELECT `ID` FROM `USERS` WHERE `ID` = ? LIMIT 1", [intval(get('id'))]);  
acms_header('Заявки в друзья');

if (!isset($account['ID'])){
  
  error('Такого пользователя не существует');
  redirect('/id'.$account['ID']);

}

if ($account['ID'] != user('ID')){
  
  error('Неверная директива');
  redirect('/id'.$account['ID']);

}

$all = db::get_column("SELECT COUNT(`ID`) FROM `FRIENDS` WHERE `MY_ID` = ? AND `ACT` = '0'", [$account['ID']]);
$online = db::get_column("SELECT COUNT(*) FROM `FRIENDS` INNER JOIN `USERS` ON `FRIENDS`.`USER_ID` = `USERS`.`ID` WHERE `FRIENDS`.`MY_ID` = ? AND `FRIENDS`.`ACT` = '0' AND `USERS`.`DATE_VISIT` > ?", [$account['ID'], (TM - config('ONLINE_TIME_USERS'))]);
$applications = db::get_column("SELECT COUNT(`ID`) FROM `FRIENDS` WHERE `USER_ID` = ? AND `ACT` = '1'", [$account['ID']]);
  
?>
<div id="search_close">
<div class="search_result"></div>
<div id="search-phone" style="display: none"></div>
</div>  
  
<div class='menu-nav-content'>  
<a class='menu-nav' href='/account/friends/?id=<?=$account['ID']?>'>
<?=lg('Все')?> <span class='menu-nav-count'><?=$all?></span>
</a>  
<a class='menu-nav' href='/account/friends/online/?id=<?=$account['ID']?>'>
<?=lg('Онлайн')?> <span class='menu-nav-count'><?=$online?></span>
</a>
<a class='menu-nav h' href='/account/friends/applications/?id=<?=$account['ID']?>'>
<?=lg('Заявки')?> <span class='menu-nav-count'><?=$applications?></span>
</a> 
<?=hooks::challenge('friends_menu', 'friends_menu')?>
<?=hooks::run('friends_menu')?>  
</div>
<?
  
if (get('type_app') == "my"){
  
  $sql = "`MY_ID`";
  $link = "&type_app=my";
  $type = "USER_ID";
  
  ?>
  <div class='list'>
  <a class='btn-o' href='/account/friends/applications/?id=<?=$account['ID']?>'><?=lg('Входящие')?></a>
  <a class='btn' href='/account/friends/applications/?id=<?=$account['ID']?>&type_app=my'><?=lg('Исходящие')?></a>
  </div>  
  <?
  
}else{
  
  $sql = "`USER_ID`";
  $link = null;
  $type = "MY_ID";
  
  ?>
  <div class='list'>
  <a class='btn' href='/account/friends/applications/?id=<?=$account['ID']?>'><?=lg('Входящие')?></a>
  <a class='btn-o' href='/account/friends/applications/?id=<?=$account['ID']?>&type_app=my'><?=lg('Исходящие')?></a>
  </div>  
  <?

} 
  
define('URL_FRSCB', '/account/friends/applications/?id='.$account['ID'].$link);
require_once (ROOT.'/modules/users/plugins/friends_and_subscribers.php');  

$column = db::get_column("SELECT COUNT(`ID`) FROM `FRIENDS` WHERE ".$sql." = ? AND `ACT` = '1'", [$account['ID']]);
$spage = spage($column, PAGE_SETTINGS);
$page = page($spage);
$limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;

if ($column == 0){ 
  
  html::empty('Пока пусто');
  
}else{
  
  ?><div class='list-body'><?
  
}

$data = db::get_string_all("SELECT * FROM `FRIENDS` WHERE ".$sql." = ? AND `ACT` = '1' ORDER BY `ID` DESC LIMIT ".$limit.", ".PAGE_SETTINGS, [$account['ID']]);
while ($list2 = $data->fetch()) {
  
  $list['ID'] = $list2[$type];
  
  if (get('type_app') == "my"){
    
    $user_menu_list = '<div id="friends2'.$list['ID'].'"><br /><a class="btn" onclick="request(\''.url_request_get(URL_FRSCB).'page='.$page.'&friends_cancel='.$list['ID'].'&'.TOKEN_URL.'\', \'#friends2'.$list['ID'].'\')" ajax="no">'.icons('times', 15, 'fa-fw').' '.lg('Отменить').'</a></div>';
    
  }else{
    
    $user_menu_list = '<div id="friends2'.$list['ID'].'"><br /><a class="btn" onclick="request(\''.url_request_get(URL_FRSCB).'page='.$page.'&friends_ok='.$list['ID'].'&'.TOKEN_URL.'\', \'#friends2'.$list['ID'].'\')" ajax="no">'.icons('check', 15, 'fa-fw').' '.lg('Принять').'</a> <a class="btn" onclick="request(\''.url_request_get(URL_FRSCB).'page='.$page.'&friends_no='.$list['ID'].'&'.TOKEN_URL.'\', \'#friends2'.$list['ID'].'\')" ajax="no">'.icons('times', 15, 'fa-fw').' '.lg('Отклонить').'</a></div>';
    
  }
  
  require (ROOT.'/modules/users/plugins/users_list.php');
  
}

if ($column > 0){

  ?></div><?
  
}

get_page('/account/friends/applications/?id='.$account['ID'].$link.'&', $spage, $page, 'list');

back('/id'.$account['ID']);
acms_footer();