<?php
  
if (get('get') == 'delete_ok'){
  
  get_check_valid();
  
  db::get_set("DELETE FROM `LIKES` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ?", [$photo['ID'], 'photos']);
  db::get_set("DELETE FROM `EYE` WHERE `OBJECT_ID` = ? AND `TYPE` = ?", [$photo['ID'], 'photos']);
  db::get_set("DELETE FROM `DOWNLOADS` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ?", [$photo['ID'], 'photos']);
  
  $data = db::get_string_all("SELECT * FROM `COMMENTS` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ?", [$photo['ID'], 'photos_comments']);
  while ($list = $data->fetch()) {
    
    db::get_set("DELETE FROM `LIKES` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ?", [$list['ID'], 'photos_comments']);
    db::get_set("DELETE FROM `ATTACHMENTS` WHERE `ID_POST` = ? AND `TYPE_POST` = ?", [$list['ID'], 'photos_comments']);
    db::get_set("DELETE FROM `COMMENTS` WHERE `ID` = ? LIMIT 1", [$list['ID']]);
  
  }
  
  db::get_set("DELETE FROM `PHOTOS` WHERE `ID` = ? LIMIT 1", [$photo['ID']]);
  
  @unlink(ROOT."/files/upload/photos/source/".$photo['SHIF'].".".$photo['EXT']);
  @unlink(ROOT."/files/upload/photos/50x50/".$photo['SHIF'].".jpg");
  @unlink(ROOT."/files/upload/photos/150x150/".$photo['SHIF'].".jpg");
  @unlink(ROOT."/files/upload/photos/240x240/".$photo['SHIF'].".jpg");
  @unlink(ROOT."/files/upload/photos/260x600/".$photo['SHIF'].".jpg");
  
  balls_add('PHOTOS', $photo['USER_ID']);
  rating_add('PHOTOS', $photo['USER_ID']);
  
  if (access('photos', null) == true){
    
    logs('Фото - удаление [url=/m/photos/show/?id='.$photo['ID'].']'.$photo['NAME'].'[/url]', user('ID'));
    
  }
  
  success('Удаление прошло успешно');
  redirect('/m/photos/users/?id='.$photo['USER_ID'].'&dir='.$photo['ID_DIR']);

}

if (get('get') == 'delete'){
  
  get_check_valid();
  
  ?>
  <div class='list'>
  <?=lg('Вы действительно хотите удалить фото')?> <b><?=tabs($photo['NAME'])?></b>?<br /><br />
  <a href='/m/photos/show/?id=<?=$photo['ID']?>&get=delete_ok&<?=TOKEN_URL?>' class='button'><?=icons('trash', 17, 'fa-fw')?> <?=lg('Удалить')?></a>
  <a href='/m/photos/show/?id=<?=$photo['ID']?>' class='button-o'><?=lg('Отмена')?></a>
  </div>
  <?
  
}