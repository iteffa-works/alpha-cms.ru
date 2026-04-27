<?php
  
if (get('delete_one') && db::get_column("SELECT COUNT(*) FROM `NOTIFICATIONS` WHERE `USER_ID` = ? AND `ID` = ? LIMIT 1", [user('ID'), intval(get('delete_one'))]) > 0){
  
  get_check_valid();
  
  db::get_set("DELETE FROM `NOTIFICATIONS` WHERE `USER_ID` = ? AND `ID` = ? LIMIT 1", [user('ID'), intval(get('delete_one'))]);
  
  redirect('/account/journal/?page='.$page.'&type='.tabs(get('type')));
  
}

if (get('get') == 'delete_all_ok'){
  
  get_check_valid();
  
  db::get_set("DELETE FROM `NOTIFICATIONS` WHERE `USER_ID` = ?", [user('ID')]);
  
  success('Удаление прошло успешно');
  redirect('/account/journal/?type='.tabs(get('type')));

}

if (get('get') == 'delete_all'){
  
  get_check_valid();
  
  ?>
  <div class='list'>
  <?=lg('Вы действительно хотите очистить журнал от всех событий')?>?<br /><br />
  <a href='/account/journal/?get=delete_all_ok&type=all&type=<?=tabs(get('type'))?>&<?=TOKEN_URL?>' class='button'><?=icons('trash', 17, 'fa-fw')?> <?=lg('Очистить')?></a>
  <a href='/account/journal/?type=all' class='button-o'><?=lg('Отмена')?></a>
  </div>
  <?
  
}