<?php  
$game = db::get_string("SELECT * FROM `GAMES` WHERE `ID` = ? LIMIT 1", [intval(get('id'))]);
$game_count = db::get_column("SELECT COUNT(*) FROM `GAMES_USERS` WHERE `GAME_ID` = ?", [$game['ID']]);
acms_header(lg('Пользователи в игре %s', tabs($game['NAME'])), 'users');
is_active_module('PRIVATE_GAMES');

if (!isset($game['ID'])) {
  
  error('Неверная директива');
  redirect('/m/games/');

}

if (get('get') == 'friends') {
  
  $root = 'friends';
  $name = 'Друзья в игре';
  $column = db::get_column("SELECT COUNT(`FRIENDS`.`USER_ID`) AS `count_fr` FROM `GAMES_USERS` LEFT JOIN `FRIENDS` ON (`FRIENDS`.`USER_ID` = `GAMES_USERS`.`USER_ID`) WHERE `FRIENDS`.`USER_ID` > '0' AND `GAMES_USERS`.`GAME_ID` = ? AND `FRIENDS`.`MY_ID` = ? AND `FRIENDS`.`ACT` = '0'", [$game['ID'], user('ID')]);
  $spage = spage($column, PAGE_SETTINGS);
  $page = page($spage);
  $limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;
  
  $data = db::get_string_all("SELECT `FRIENDS`.`USER_ID` FROM `GAMES_USERS` LEFT JOIN `FRIENDS` ON (`FRIENDS`.`USER_ID` = `GAMES_USERS`.`USER_ID`) WHERE `FRIENDS`.`USER_ID` > '0' AND `GAMES_USERS`.`GAME_ID` = ? AND `FRIENDS`.`MY_ID` = ? AND `FRIENDS`.`ACT` = '0' ORDER BY `GAMES_USERS`.`ID` DESC LIMIT ".$limit.", ".PAGE_SETTINGS, [$game['ID'], user('ID')]);
  
}else{
  
  $root = 'users';
  $name = 'Пользователи в игре';
  $column = db::get_column("SELECT COUNT(`ID`) FROM `GAMES_USERS` WHERE `GAME_ID` = ?", [$game['ID']]);
  $spage = spage($column, PAGE_SETTINGS);
  $page = page($spage);
  $limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;
  
  $data = db::get_string_all("SELECT * FROM `GAMES_USERS` WHERE `GAME_ID` = ? ORDER BY `ID` DESC LIMIT ".$limit.", ".PAGE_SETTINGS, [$game['ID']]);
  
}

?>
<a href='/m/games/show/?id=<?=$game['ID']?>'>
<div class='list hover'>
<div class="user-avatar-mini">
<img class="img" src="/games/<?=tabs($game['LINK'])?>/img/<?=tabs($game['IMG'])?>" style="width: 60px; height: 60px">
</div>
<div class="user-login-mini" style="padding-left: 20px; width: 55%">
<b><?=tabs($game['NAME'])?></b><br />
<p class="games_info"><?=icons('user', 15, 'fa-fw')?> <?=lg('играют')?> <?=num_format($game_count, 2)?> <?=num_decline($game_count, ['человек', 'человека', 'человек'], 0)?></p>
</div>
</div>
</a>

<div class='list-body'>
<div class='list-menu'>
<a href='/m/games/players/?id=<?=$game['ID']?>&get=users' class='btn<?=($root != 'friends' ? null : '-o')?>'><?=lg('Все пользователи')?></a>
<a href='/m/games/players/?id=<?=$game['ID']?>&get=friends' class='btn<?=($root == 'friends' ? null : '-o')?>'><?=lg('Друзья')?></a>
</div>
<div class='list-menu'><b><?=lg($name)?> "<?=tabs($game['NAME'])?>":</b></div>
<?php while ($list = $data->fetch()) : ?>
<?php require (ROOT.'/modules/users/plugins/list-mini.php') ?>
<?=$list_mini?>
<?php endwhile ?>
</div>
<?php if ($column == 0) : ?>
<?=html::empty('Нет игроков', 'users')?>
<?php endif ?>
<?
  
get_page('/m/games/players/?id='.$game['ID'].'&get='.$root.'&', $spage, $page, 'list');
forward('/m/games/show/?id='.$game['ID'], 'К игре');
forward('/m/games/users/?id='.user('ID'), 'К моим играм');
back('/m/games/');
acms_footer();