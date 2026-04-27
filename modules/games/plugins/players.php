<?php
$html = null;
$data = db::get_string_all("SELECT `USER_ID`,`GAME_ID` FROM `GAMES_USERS` ORDER BY `ID` DESC LIMIT 3");
while ($list = $data->fetch()) {
  
  $game = db::get_string("SELECT `NAME`,`ID` FROM `GAMES` WHERE `ID` = ? LIMIT 1", $list['GAME_ID']);
  
  $game_data = '<font color="red">'.lg('игра удалена с сайта').'</font>';
  if (isset($game['ID'])) { $game_data = '<a href="/m/games/show/?id='.$list['GAME_ID'].'">'.mb_strtolower(tabs($game['NAME']), 'UTF-8').'</a>'; }
  
  $dop = '
  <br />
  <span class="time">
  '.lg('Вступил(-а) в игру').' '.$game_data.'
  </span>
  ';
  
  require (ROOT.'/modules/users/plugins/list-mini.php');  
  $html .= $list_mini;
  
}
?>

<?php if (str($html) > 0) : ?>
<div class='list-body'> 
<div class='list-menu'>
<b><?=lg('Встречайте новых игроков!')?></b>
</div>
<?=$html?>
<a href='/m/games/new_players/'>
<div class='list-menu hover' style='color: #5CB3F9'>
<b><?=lg('Все игроки')?></b>
<span style='float: right'><?=icons('chevron-right', 14)?></span>
</div>
</a>
</div>
<?php endif ?>