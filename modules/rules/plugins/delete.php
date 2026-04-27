<?php
  
if (get('get') == 'delete_ok'){
  
  get_check_valid();
  
  db::get_set("DELETE FROM `RULES` WHERE `ID` = ? LIMIT 1", [$rules['ID']]);
  
  success('Удаление прошло успешно');
  redirect('/m/rules/');

}

if (get('get') == 'delete'){
  
  get_check_valid();
  
  ?>
  <div class='list'>
  <?=lg('Вы действительно хотите удалить раздел правил')?> <b><?=tabs($rules['NAME'])?></b>?<br /><br />
  <a href='/m/rules/show/?id=<?=$rules['ID']?>&get=delete_ok&<?=TOKEN_URL?>' class='button'><?=icons('trash', 17, 'fa-fw')?> <?=lg('Удалить')?></a>
  <a href='/m/rules/show/?id=<?=$rules['ID']?>' class='button-o'><?=lg('Отмена')?></a>
  </div>
  <?
  
}