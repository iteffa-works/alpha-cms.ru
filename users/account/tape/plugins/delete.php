<?php
  
if (get('delete_one') && db::get_column("SELECT COUNT(*) FROM `TAPE` WHERE `USER_ID` = ? AND `ID` = ? LIMIT 1", [user('ID'), intval(get('delete_one'))]) > 0){
  
  get_check_valid();
  
  db::get_set("DELETE FROM `TAPE` WHERE `USER_ID` = ? AND `ID` = ? LIMIT 1", [user('ID'), intval(get('delete_one'))]);
  
}

if (get('get') == 'delete_all_ok'){
  
  get_check_valid();
  
  db::get_set("DELETE FROM `TAPE` WHERE `USER_ID` = ?", [user('ID')]);
  
  success('Удаление прошло успешно');
  redirect('/account/tape/');

}

if (get('get') == 'delete_all'){
  
  get_check_valid();
  
  ?>
  <div class='list'>
  <?=lg('Вы действительно хотите очистить ленту от всех событий')?>?<br /><br />
  <a href='/account/tape/?get=delete_all_ok&<?=TOKEN_URL?>' class='button'><?=icons('trash', 17, 'fa-fw')?> <?=lg('Очистить')?></a>
  <a href='/account/tape/' class='button-o'><?=lg('Отмена')?></a>
  </div>
  <?
  
}