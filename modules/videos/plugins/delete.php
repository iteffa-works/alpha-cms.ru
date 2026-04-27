<?php
  
if (get('get') == 'delete_ok'){
  
  get_check_valid();
  
  db::get_set("DELETE FROM `LIKES` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ?", [$video['ID'], 'videos']);
  db::get_set("DELETE FROM `EYE` WHERE `OBJECT_ID` = ? AND `TYPE` = ?", [$video['ID'], 'videos']);
  db::get_set("DELETE FROM `DOWNLOADS` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ?", [$video['ID'], 'videos']);
  
  $data = db::get_string_all("SELECT * FROM `COMMENTS` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ?", [$video['ID'], 'videos_comments']);
  while ($list = $data->fetch()) {
    
    db::get_set("DELETE FROM `LIKES` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ?", [$list['ID'], 'videos_comments']);
    db::get_set("DELETE FROM `ATTACHMENTS` WHERE `ID_POST` = ? AND `TYPE_POST` = ?", [$list['ID'], 'videos_comments']);
    db::get_set("DELETE FROM `COMMENTS` WHERE `ID` = ? LIMIT 1", [$list['ID']]);
  
  }
  
  db::get_set("DELETE FROM `VIDEOS` WHERE `ID` = ? LIMIT 1", [$video['ID']]);
  
  @unlink(ROOT."/files/upload/videos/source/".$video['ID'].".".$video['EXT']);
  @unlink(ROOT."/files/upload/videos/screen/".$video['ID'].".jpg");
  @unlink(ROOT."/files/upload/videos/screen/240x240/".$video['ID'].".jpg");
  
  balls_add('VIDEOS', $video['USER_ID']);
  rating_add('VIDEOS', $video['USER_ID']);
  
  if (access('videos', null) == true){
    
    logs('Видео - удаление [url=/m/videos/show/?id='.$video['ID'].']'.$video['NAME'].'[/url]', user('ID'));
    
  }
  
  success('Удаление прошло успешно');
  redirect('/m/videos/users/?id='.$video['USER_ID'].'&dir='.$video['ID_DIR']);

}

if (get('get') == 'delete'){
  
  get_check_valid();
  
  ?>
  <div class='list'>
  <?=lg('Вы действительно хотите удалить видео')?> <b><?=tabs($video['NAME'])?></b>?<br /><br />
  <a href='/m/videos/show/?id=<?=$video['ID']?>&get=delete_ok&<?=TOKEN_URL?>' class='button'><?=icons('trash', 17, 'fa-fw')?> <?=lg('Удалить')?></a>
  <a href='/m/videos/show/?id=<?=$video['ID']?>' class='button-o'><?=lg('Отмена')?></a>
  </div>
  <?
  
}