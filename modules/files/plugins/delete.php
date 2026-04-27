<?php
  
if (get('get') == 'delete_ok'){
  
  get_check_valid();
  
  db::get_set("DELETE FROM `LIKES` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ?", [$file['ID'], 'files']);
  db::get_set("DELETE FROM `EYE` WHERE `OBJECT_ID` = ? AND `TYPE` = ?", [$file['ID'], 'files']);
  db::get_set("DELETE FROM `DOWNLOADS` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ?", [$file['ID'], 'files']);
  
  $data = db::get_string_all("SELECT * FROM `COMMENTS` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ?", [$file['ID'], 'files_comments']);
  while ($list = $data->fetch()) {
    
    db::get_set("DELETE FROM `LIKES` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ?", [$list['ID'], 'files_comments']);
    db::get_set("DELETE FROM `ATTACHMENTS` WHERE `ID_POST` = ? AND `TYPE_POST` = ?", [$list['ID'], 'files_comments']);
    db::get_set("DELETE FROM `COMMENTS` WHERE `ID` = ? LIMIT 1", [$list['ID']]);
  
  }
  
  db::get_set("DELETE FROM `FILES` WHERE `ID` = ? LIMIT 1", [$file['ID']]);
  
  @unlink(ROOT."/files/upload/files/source/".$file['ID'].".".$file['EXT']);
  
  if (access('files', null) == true){
    
    logs('Файлы - удаление [url=/m/files/show/?id='.$file['ID'].']'.$file['NAME'].'[/url]', user('ID'));
    
  }
  
  balls_add('FILES', $file['USER_ID']);
  rating_add('FILES', $file['USER_ID']);
  
  success('Удаление прошло успешно');
  redirect('/m/files/users/?id='.$file['USER_ID'].'&dir='.$file['ID_DIR']);

}

if (get('get') == 'delete'){
  
  get_check_valid();
  
  ?>
  <div class='list'>
  <?=lg('Вы действительно хотите удалить файл')?> <b><?=tabs($file['NAME'])?></b>?<br /><br />
  <a href='/m/files/show/?id=<?=$file['ID']?>&get=delete_ok&<?=TOKEN_URL?>' class='button'><?=icons('trash', 17, 'fa-fw')?> <?=lg('Удалить')?></a>
  <a href='/m/files/show/?id=<?=$file['ID']?>' class='button-o'><?=lg('Отмена')?></a>
  </div>
  <?
  
}