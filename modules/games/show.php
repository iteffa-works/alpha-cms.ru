<?php
$game = db::get_string("SELECT * FROM `GAMES` WHERE `ID` = ? LIMIT 1", [intval(get('id'))]);
$game_user = db::get_string("SELECT * FROM `GAMES_USERS` WHERE `GAME_ID` = ? AND `USER_ID` = ? LIMIT 1", [$game['ID'], user('ID')]);
$game_count = db::get_column("SELECT COUNT(*) FROM `GAMES_USERS` WHERE `GAME_ID` = ?", [$game['ID']]);
acms_header(lg('Игра %s', tabs($game['NAME'])));
is_active_module('PRIVATE_GAMES');

if (user('ID') == 0) {
  
  error('Для начала игры необходимо зарегистрироваться или авторизоваться на сайте');
  redirect('/login/');
  
}

if (!isset($game['ID'])) {
  
  error('Неверная директива');
  redirect('/m/games/');

}

if (access('games', null) == true) {
  
  require (ROOT.'/modules/games/plugins/delete.php');
  
}

if (get('get') == 'delete' && isset($game_user['ID'])){
  
  get_check_valid();

  db::get_set("UPDATE `GAMES` SET `RATING` = `RATING` - '1' WHERE `ID` = ? LIMIT 1", [$game['ID']]);
  db::get_set("DELETE FROM `GAMES_USERS` WHERE `GAME_ID` = ? AND `USER_ID` = ? LIMIT 1", [$game['ID'], user('ID')]);

  success('Игра успешно удалена из вашего списка');
  redirect('/m/games/');

}

if (get('get') == 'go' && !isset($game_user['ID'])){
  
  get_check_valid();
  
  db::get_add("INSERT INTO `GAMES_USERS` (`USER_ID`, `GAME_ID`) VALUES (?, ?)", [user('ID'), $game['ID']]);
  db::get_set("UPDATE `GAMES` SET `RATING` = `RATING` + '1' WHERE `ID` = ? LIMIT 1", [$game['ID']]);

  redirect('/games/'.tabs($game['LINK']).'/');

}

if (get('get') == 'go_user' && isset($game_user['ID'])){

  redirect('/games/'.tabs($game['LINK']).'/');

}

?>  
<div class='list-body'>    
<div class="list-menu">
<div class="user-avatar-mini">
<img class="img" src="/games/<?=tabs($game['LINK'])?>/img/<?=tabs($game['IMG'])?>" style="width: 60px; height: 60px">
</div>
<div class="user-login-mini" style="padding-left: 20px; width: 55%">
<b><?=tabs($game['NAME'])?></b><br />
<p class="games_info"><?=icons('user', 15, 'fa-fw')?> <?=lg('в игре')?> <?=num_format($game_count, 2)?> <?=num_decline($game_count, ['человек', 'человека', 'человек'], 0)?></p>
</div>
<br /><?=tabs($game['MESSAGE'])?>
</div>  
<div class='list-menu'>  
<?php if (isset($game_user['ID'])) : ?>
<a href='/m/games/show/?id=<?=$game['ID']?>&get=go_user' class='btn'><?=icons('gamepad', 15, 'fa-fw')?> <?=lg('Играть')?></a>
<a href='/m/games/show/?id=<?=$game['ID']?>&get=delete&<?=TOKEN_URL?>' class='btn-o'><?=icons('trash', 15, 'fa-fw')?> <?=lg('Убрать из своих игр')?></a>
<?php else : ?>
<a href='/m/games/show/?id=<?=$game['ID']?>&get=go&<?=TOKEN_URL?>' class='btn'><?=icons('plus', 15, 'fa-fw')?> <?=lg('Играть')?></a>
<?php endif ?>
<?php if (access('games', null) == true) : ?>
<a href="/m/games/edit/?id=<?=$game['ID']?>" class="btn"><?=icons('gear', 15, 'fa-fw')?> <?=lg('Редактировать игру')?></a>
<a href="/m/games/show/?id=<?=$game['ID']?>&get=all_delete&<?=TOKEN_URL?>" class="btn"><?=icons('times', 15, 'fa-fw')?> <?=lg('Удалить игру')?></a>
<?php endif ?>
<a href="/m/games/players/?id=<?=$game['ID']?>" class="btn"><?=icons('user', 15, 'fa-fw')?> <?=lg('Игроки')?></a>
<a href="/m/games/players/?id=<?=$game['ID']?>&get=friends" class="btn"><?=icons('user-plus', 15, 'fa-fw')?> <?=lg('Друзья в игре')?></a>  
</div>
</div>
<?

back('/m/games/', 'Ко всем играм');
forward('/m/games/users/?id='.user('ID'), 'К моим играм');
acms_footer();