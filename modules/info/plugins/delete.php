<?php
  
if (get('get') == 'delete_ok'){
  
  get_check_valid();
  
  db::get_set("DELETE FROM `INFO` WHERE `ID` = ? LIMIT 1", [$info['ID']]);
  
  success('Удаление прошло успешно');
  redirect('/m/info/');

}

if (get('get') == 'delete'){
  
  get_check_valid();
  
  ?>
  <div class='list'>
  <?=lg('Вы действительно хотите удалить информацию')?> <b><?=tabs($info['NAME'])?></b>?<br /><br />
  <a href='/m/info/show/?id=<?=$info['ID']?>&get=delete_ok&<?=TOKEN_URL?>' class='button'><?=icons('trash', 17, 'fa-fw')?> <?=lg('Удалить')?></a>
  <a href='/m/info/show/?id=<?=$info['ID']?>' class='button-o'><?=lg('Отмена')?></a>
  </div>
  <?
  
}