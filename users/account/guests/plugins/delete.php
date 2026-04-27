<?php

if (get('get') == 'delete_ok'){
  
  get_check_valid();
  
  db::get_set("DELETE FROM `USERS_GUESTS` WHERE `MY_ID` = ?", [user('ID')]);
  
  success('Удаление прошло успешно');
  redirect('/account/guests/');

}

if (get('get') == 'delete'){
  
  get_check_valid();
  
  ?>
  <div class='list'>
  <?=lg('Вы действительно хотите очистить гостей')?>?<br /><br />
  <a href='/account/guests/?get=delete_ok&<?=TOKEN_URL?>' class='button'><?=icons('trash', 17, 'fa-fw')?> <?=lg('Очистить')?></a>
  <a href='/account/guests/' class='button-o'><?=lg('Отмена')?></a>
  </div>
  <?
  
}