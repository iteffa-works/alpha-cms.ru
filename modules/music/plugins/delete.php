<?php
  
if (get('get') == 'delete_ok'){
  
  get_check_valid();
  
  db::get_set("DELETE FROM `LIKES` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ?", [$music['ID'], 'music']);
  db::get_set("DELETE FROM `EYE` WHERE `OBJECT_ID` = ? AND `TYPE` = ?", [$music['ID'], 'music']);
  db::get_set("DELETE FROM `DOWNLOADS` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ?", [$music['ID'], 'music']);
  
  $data = db::get_string_all("SELECT * FROM `COMMENTS` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ?", [$music['ID'], 'music_comments']);
  while ($list = $data->fetch()) {
    
    db::get_set("DELETE FROM `LIKES` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ?", [$list['ID'], 'music_comments']);
    db::get_set("DELETE FROM `ATTACHMENTS` WHERE `ID_POST` = ? AND `TYPE_POST` = ?", [$list['ID'], 'music_comments']);
    db::get_set("DELETE FROM `COMMENTS` WHERE `ID` = ? LIMIT 1", [$list['ID']]);
  
  }
  
  db::get_set("DELETE FROM `MUSIC` WHERE `ID` = ? LIMIT 1", [$music['ID']]);
  
  @unlink(ROOT."/files/upload/music/source/".$music['ID'].".".$music['EXT']);
  @unlink(ROOT."/files/upload/music/screen/".$music['ID'].".jpg");
  @unlink(ROOT."/files/upload/music/screen/240x240/".$music['ID'].".jpg");
  @unlink(ROOT."/files/upload/music/screen/120x120/".$music['ID'].".jpg");
  
  balls_add('MUSIC', $music['USER_ID']);
  rating_add('MUSIC', $music['USER_ID']);
  
  if (access('music', null) == true){
    
    logs('Музыка - удаление [url=/m/music/show/?id='.$music['ID'].']'.$music['NAME'].'[/url]', user('ID'));
    
  }
  
  success('Удаление прошло успешно');
  redirect('/m/music/users/?id='.$music['USER_ID'].'&dir='.$music['ID_DIR']);

}

if (get('get') == 'delete'){
  
  get_check_valid();
  
  ?>
  <div class='list'>
  <?=lg('Вы действительно хотите удалить музыку')?> <b><?=tabs($music['FACT_NAME'])?></b>?<br /><br />
  <a href='/m/music/show/?id=<?=$music['ID']?>&get=delete_ok&<?=TOKEN_URL?>' class='button'><?=icons('trash', 17, 'fa-fw')?> <?=lg('Удалить')?></a>
  <a href='/m/music/show/?id=<?=$music['ID']?>' class='button-o'><?=lg('Отмена')?></a>     
  </div>
  <?
  
}