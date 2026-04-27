<?php  
$account = db::get_string("SELECT `ID` FROM `USERS` WHERE `ID` = ? LIMIT 1", [intval(get('id'))]);  
acms_header(lg('Друзья онлайн %s', user::login_mini($account['ID'])));

if (!isset($account['ID'])){
  
  error('Такого пользователя не существует');
  redirect('/id'.$account['ID']);

}

if (MANAGEMENT == 0 && user('ID') != $account['ID'] && db::get_column("SELECT COUNT(*) FROM `USERS_SETTINGS` WHERE `USER_ID` = ? AND `FRIENDS_PRIVATE` = '0' LIMIT 1", [$account['ID']]) == 1){
  
  error('Пользователь запретил просматривать список друзей');
  redirect('/id'.$account['ID']);

}

$all = db::get_column("SELECT COUNT(`ID`) FROM `FRIENDS` WHERE `MY_ID` = ? AND `ACT` = '0'", [$account['ID']]);
$online = db::get_column("SELECT COUNT(*) FROM `FRIENDS` INNER JOIN `USERS` ON `FRIENDS`.`USER_ID` = `USERS`.`ID` WHERE `FRIENDS`.`MY_ID` = ? AND `FRIENDS`.`ACT` = '0' AND `USERS`.`DATE_VISIT` > ?", [$account['ID'], (TM - config('ONLINE_TIME_USERS'))]);
$applications = db::get_column("SELECT COUNT(`ID`) FROM `FRIENDS` WHERE `USER_ID` = ? AND `ACT` = '1'", [$account['ID']]);
$general = intval(db::get_column("SELECT COUNT(*) FROM `FRIENDS` AS a INNER JOIN `FRIENDS` as b ON (b.`USER_ID` = a.`USER_ID` AND b.`MY_ID` = ? AND b.`USER_ID` != ?) WHERE a.`MY_ID`= ? AND b.`USER_ID` != ? AND a.`ACT` = '0' AND b.`ACT` = '0' GROUP BY a.`ID`", [$account['ID'], $account['ID'], user('ID'), user('ID')]));
  
?>
<div id="search_close">
<div class="search_result"></div>
<div id="search-phone" style="display: none"></div>
</div>  
  
<div class='menu-nav-content'>  
<a class='menu-nav' href='/account/friends/?id=<?=$account['ID']?>'>
<?=lg('Все')?> <span class='menu-nav-count'><?=$all?></span>
</a>  
<a class='menu-nav h' href='/account/friends/online/?id=<?=$account['ID']?>'>
<?=lg('Онлайн')?> <span class='menu-nav-count'><?=$online?></span>
</a>
<?php if ($account['ID'] == user('ID')){ ?>
<a class='menu-nav' href='/account/friends/applications/?id=<?=$account['ID']?>'>
<?=lg('Заявки')?> <span class='menu-nav-count'><?=$applications?></span>
</a> 
<?php } ?>
<?php if (user('ID') > 0 && $account['ID'] != user('ID')){ ?>
<a class='menu-nav' href='/account/friends/general/?id=<?=$account['ID']?>'>
<?=lg('Общие')?> <span class='menu-nav-count'><?=$general?></span>
</a>
<?php } ?>
<?=hooks::challenge('friends_menu', 'friends_menu')?>
<?=hooks::run('friends_menu')?>  
</div>
<?
  
define('URL_FRSCB', '/account/friends/online/?id='.$account['ID']);
require_once (ROOT.'/modules/users/plugins/friends_and_subscribers.php');  

$spage = spage($online, PAGE_SETTINGS);
$page = page($spage);
$limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;

if ($online == 0){ 
  
  html::empty('Пока пусто');
  
}else{
  
  ?><div class='list-body'><?
  
}

$data = db::get_string_all("SELECT * FROM `FRIENDS` INNER JOIN `USERS` ON `FRIENDS`.`USER_ID` = `USERS`.`ID` WHERE `FRIENDS`.`MY_ID` = ? AND `FRIENDS`.`ACT` = '0' AND `USERS`.`DATE_VISIT` > ? ORDER BY `TIME` DESC LIMIT ".$limit.", ".PAGE_SETTINGS, [$account['ID'], (TM - config('ONLINE_TIME_USERS'))]);
while ($list2 = $data->fetch()) {
  
  $list['ID'] = $list2['USER_ID'];  
  require (ROOT.'/modules/users/plugins/users_list.php');
  
}

if ($online > 0){

  ?></div><?
  
}

get_page('/account/friends/online/?id='.$account['ID'].'&', $spage, $page, 'list');

back('/id'.$account['ID']);
acms_footer();