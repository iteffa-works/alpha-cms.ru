<?php
  
$game_count = db::get_column("SELECT COUNT(*) FROM `GAMES_USERS` WHERE `GAME_ID` = ?", [$list['ID']]);

$game_off = null;
$game_play = null;
$game_edit = null;
$game_players = null;
$game_delete = null;
$game_friends = null;
$game_friends_list = null;

if (isset($my_games) && intval(get('id')) == user('ID')) {
  
  $game_off = '<a href="/m/games/show/?id='.$list['ID'].'&get=delete&'.TOKEN_URL.'"><div class="list-menu hover">'.icons('trash', 18, 'fa-fw').' '.lg('Убрать из своих игр').'</div></a>';
  
}else{
  
  $game_play = '<a href="/m/games/show/?id='.$list['ID'].'"><div class="list-menu hover">'.icons('plus', 18, 'fa-fw').' '.lg('Играть').'</div></a>';
  
}

if (access('games', null) == true && user('ID') > 0) {
  
  $game_edit = '<a href="/m/games/edit/?id='.$list['ID'].'"><div class="list-menu hover">'.icons('gear', 18, 'fa-fw').' '.lg('Редактировать игру').'</div></a>';
  $game_delete = '<a href="/m/games/show/?id='.$list['ID'].'&get=all_delete&'.TOKEN_URL.'"><div class="list-menu hover">'.icons('times', 18, 'fa-fw').' '.lg('Удалить игру').'</div></a>';
  
}

if (user('ID') > 0) {
  
  $game_players = '<a href="/m/games/players/?id='.$list['ID'].'"><div class="list-menu hover">'.icons('user', 18, 'fa-fw').' '.lg('Игроки').'</div></a>';
  $game_friends = '<a href="/m/games/players/?id='.$list['ID'].'&get=friends"><div class="list-menu hover">'.icons('user-plus', 18, 'fa-fw').' '.lg('Друзья в игре').'</div></a>';
  
}

if (user('ID') > 0) {
  
  $s_fr = null;
  
  $data_fr = db::get_string_all("SELECT `FRIENDS`.`USER_ID` FROM `GAMES_USERS` LEFT JOIN `FRIENDS` ON (`FRIENDS`.`USER_ID` = `GAMES_USERS`.`USER_ID`) WHERE `FRIENDS`.`USER_ID` > '0' AND `GAMES_USERS`.`GAME_ID` = ? AND `FRIENDS`.`MY_ID` = ? AND `FRIENDS`.`ACT` = '0' ORDER BY `GAMES_USERS`.`ID` DESC LIMIT 5", [$list['ID'], user('ID')]);
  while ($list_fr = $data_fr->fetch()) {
    
    $s_fr .= user::avatar($list_fr['USER_ID'], 28, 0).' ';
    
  }
  
  if (str($s_fr) > 0) {
    
    $game_friends_list = "
    <a href='/m/games/players/?id=".$list['ID']."&get=friends'>
    <div class='games_fr_list'>
    <span class='games_fr_list_text'>".lg('Друзья в игре').":</span> <span class='games_fr_list_avatars'>".$s_fr."</span>
    </div>
    </a>
    ";
    
  }
  
}
  
$games_list = '
<div class="modal_phone modal_center_close" id="cmenu'.$list['ID'].'2" onclick="modal_center(\'cmenu'.$list['ID'].'\', \'close\')"></div>
<div id="cmenu'.$list['ID'].'" class="modal_center modal_center_open">
<div class="modal_bottom_title2">'.lg('Действия').'<button onclick="modal_center_close()">'.icons('times', 20).'</button></div>
<div class="modal-container">
'.$game_off.$game_play.$game_edit.$game_delete.$game_players.$game_friends.'
</div>
</div>

<div class="list-menu hover">
<a href="/m/games/show/?id='.$list['ID'].'">
<div class="user-avatar-mini">
<img class="img" src="/games/'.tabs($list['LINK']).'/img/'.tabs($list['IMG']).'" style="width: 60px; height: 60px">
</div>
<div class="user-login-mini" style="padding-left: 20px; width: 55%">
<b>'.tabs($list['NAME']).'</b><br />
<p class="games_info">'.icons('user', 15, 'fa-fw').' '.lg('в игре').' '.num_format($game_count, 2).' '.num_decline($game_count, ['человек', 'человека', 'человек'], 0).'</p>
</div>
</a>
<span onclick="modal_center(\'cmenu'.$list['ID'].'\', \'open\')" class="user-login-menu ell-optimize">'.icons('ellipsis-v', 20).'</span>
'.$game_friends_list.'
</div>
';