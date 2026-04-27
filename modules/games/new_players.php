<?php
acms_header('Онлайн игры - новые игроки');
is_active_module('PRIVATE_GAMES');

require (ROOT.'/modules/search/plugins/form/games.php');

$column = db::get_column("SELECT COUNT(`ID`) FROM `GAMES_USERS`");
$spage = spage($column, PAGE_SETTINGS);
$page = page($spage);
$limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;
  
$data = db::get_string_all("SELECT `USER_ID`,`GAME_ID` FROM `GAMES_USERS` ORDER BY `ID` DESC LIMIT ".$limit.", ".PAGE_SETTINGS);

?>
<div class='list-body'>
<div class='list-menu'><b><?=lg('Новые игроки на сайте')?>:</b></div>
<?php while ($list = $data->fetch()) : ?>
<?php
$game = db::get_string("SELECT `NAME`,`ID` FROM `GAMES` WHERE `ID` = ? LIMIT 1", $list['GAME_ID']);
$game_data = '<font color="red">'.lg('игра удалена с сайта').'</font>';
if (isset($game['ID'])) { $game_data = '<a href="/m/games/show/?id='.$list['GAME_ID'].'">'.mb_strtolower(tabs($game['NAME']), 'UTF-8').'</a>'; }
$dop = '
<br />
<span class="time">
'.lg('Вступил(-а) в игру').' '.$game_data.'
</span>
';  
?>
<?php require (ROOT.'/modules/users/plugins/list-mini.php') ?>
<?=$list_mini?>
<?php endwhile ?>
</div>
<?php if ($column == 0) : ?>
<?=html::empty('Нет игроков', 'users')?>
<?php endif ?>
<?
  
get_page('/m/games/new_players/?', $spage, $page, 'list');
back('/m/games/');
acms_footer();