<?php
  
if (get('get') == 'all_delete_ok'){
  
  get_check_valid();
  
  db::get_set("DELETE FROM `GAMES` WHERE `ID` = ? LIMIT 1", [$game['ID']]);
  
  logs('Онлайн игры - удаление игры [url=/m/games/show/?id='.$game['ID'].']'.$game['NAME'].'[/url]', user('ID'));
  
  success('Удаление прошло успешно');
  redirect('/m/games/');

}

if (get('get') == 'all_delete'){
  
  get_check_valid();
  
  ?>
  <div class='list'>
  <?=lg('Вы действительно хотите безвозвратно удалить с сайта игру')?> <b><?=tabs($game['NAME'])?></b>?<br /><br />
  <a href='/m/games/show/?id=<?=$game['ID']?>&get=all_delete_ok&<?=TOKEN_URL?>' class='button'><?=icons('trash', 17, 'fa-fw')?> <?=lg('Удалить')?></a>
  <a href='/m/games/show/?id=<?=$game['ID']?>' class='button-o'><?=lg('Отмена')?></a>
  </div>
  <?
  
}