<?php
$account_id = abs(intval(db::get_column("SELECT `ID` FROM `USERS` WHERE `ID` = ? LIMIT 1", [intval(get('id'))])));
acms_header(lg('Онлайн игры %s', user::login_mini($account_id)));
is_active_module('PRIVATE_GAMES');

if ($account_id == 0) {
  
  error('Неверная директива');
  redirect('/m/games/');

}
  
?> 
<div class='menu-nav-content'>  
<a class='menu-nav' href='/m/games/?'>
<?=lg('Все')?>
</a>    
<a class='menu-nav' href='/m/games/?get=rating'>
<?=lg('ТОП')?>
</a>    
<a class='menu-nav' href='/m/games/?get=new'>
<?=lg('Новые')?>
</a>  
<a class='menu-nav h' href='/m/games/users/?id=<?=$account_id?>'>
<?=user::login_mini($account_id)?>
</a>
</div> 
<?
  
require (ROOT.'/modules/search/plugins/form/games.php');
if ($account_id == user('ID')) { require (ROOT.'/modules/games/plugins/resources.php'); }
  
$column = db::get_column("SELECT COUNT(*) FROM `GAMES_USERS` WHERE `USER_ID` = ?", [$account_id]);
$spage = spage($column, PAGE_SETTINGS);
$page = page($spage);
$limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;

if ($column == 0){ 
  
  html::empty(lg('Пока нет игр').'<br /><a href="/m/games/" class="btn" style="margin-top: 10px">'.icons('gamepad', 15, 'fa-fw').' '.lg('Подберите игры').'</a>');
  
}else{
  
  ?>
  <div class='list-body'>
  <div class='list-menu'><b><?=lg('Онлайн игры')?> <?=user::login_mini($account_id)?>:</b></div>
  <?
  
}

$data = db::get_string_all("SELECT * FROM `GAMES_USERS` WHERE `USER_ID` = ? ORDER BY `ID` DESC LIMIT ".$limit.", ".PAGE_SETTINGS, [$account_id]);
while ($my_games = $data->fetch()) {
  
  $list = db::get_string("SELECT * FROM `GAMES` WHERE `ID` = ? LIMIT 1", [$my_games['GAME_ID']]);  
  
  //Если игра удалена с сайта администратором
  if (!isset($list['ID'])){
    
    db::get_set("DELETE FROM `GAMES_USERS` WHERE `ID` = ?", [$my_games['ID']]);
    
  }
  
  require (ROOT.'/modules/games/plugins/list.php');
  echo $games_list;
  
}

if ($column > 0){ 
  
  ?></div><?
  
}

get_page('/m/games/users/?id='.$account_id.'&', $spage, $page, 'list');

back('/m/games/', 'Ко всем играм');
forward('/id'.$account_id, lg('К странице %s', user::login_mini($account_id)));
acms_footer();