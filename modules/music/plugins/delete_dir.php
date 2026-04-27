<?php
  
if (get('get') == 'delete_dir_ok') {
  
  get_check_valid();
  
  if (db::get_column("SELECT COUNT(`ID`) FROM `MUSIC_DIR` WHERE `ID_DIR` = ? AND `USER_ID` = ? LIMIT 1", [$id_dir, $account['ID']]) > 0){ 
    
    error('Альбом не может быть удален, пока в нем есть хотябы один альбом');
    redirect('/m/music/users/?id='.$account['ID'].'&dir='.$id_dir);
  
  }
  
  if (db::get_column("SELECT COUNT(`ID`) FROM `MUSIC` WHERE `ID_DIR` = ? AND `USER_ID` = ?", [$id_dir, $account['ID']]) >= 100){ 
    
    error('Альбом не может быть удален, пока в нем более 100 файлов');
    redirect('/m/music/users/?id='.$account['ID'].'&dir='.$id_dir);
  
  }
  
  $data = db::get_string_all("SELECT `ID`,`USER_ID` FROM `MUSIC` WHERE `ID_DIR` = ?", [$id_dir]);
  while ($list = $data->fetch()){
    
    db::get_set("DELETE FROM `LIKES` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ?", [$list['ID'], 'music']);
    db::get_set("DELETE FROM `EYE` WHERE `OBJECT_ID` = ? AND `TYPE` = ?", [$list['ID'], 'music']);
    db::get_set("DELETE FROM `DOWNLOADS` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ?", [$list['ID'], 'music']);
    
    $data2 = db::get_string_all("SELECT * FROM `COMMENTS` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ?", [$list['ID'], 'music_comments']);
    while ($list2 = $data2->fetch()) {
      
      db::get_set("DELETE FROM `LIKES` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ?", [$list2['ID'], 'music_comments']);
      db::get_set("DELETE FROM `ATTACHMENTS` WHERE `ID_POST` = ? AND `TYPE_POST` = ?", [$list2['ID'], 'music_comments']);
      db::get_set("DELETE FROM `COMMENTS` WHERE `ID` = ? LIMIT 1", [$list2['ID']]);
      
    }
    
    balls_add('MUSIC', $list['USER_ID']);
    rating_add('MUSIC', $list['USER_ID']);
    
    @unlink(ROOT.'/files/upload/music/source/'.$list['ID'].'.'.$list['EXT']);
    @unlink(ROOT.'/files/upload/music/screen/'.$list['ID'].'.jpg');
    @unlink(ROOT.'/files/upload/music/screen/120x120/'.$list['ID'].'.jpg');
    @unlink(ROOT.'/files/upload/music/screen/240x240/'.$list['ID'].'.jpg');
    
    db::get_set("DELETE FROM `MUSIC` WHERE `ID` = ? LIMIT 1", [$list['ID']]);
    
  }
  
  db::get_set("DELETE FROM `MUSIC_DIR` WHERE `ID` = ? LIMIT 1", [$id_dir]);
  
  $dir = db::get_string("SELECT `NAME` FROM `MUSIC_DIR` WHERE `ID` = ? LIMIT 1", [$id_dir]);
  
  if (access('music', null) == true){
    
    logs('Музыка - удаление альбома [url=/m/music/users/?id='.$account['ID'].'&dir='.$id_dir.']'.$dir['NAME'].'[/url]', user('ID'));
    
  }
  
  success('Удаление прошло успешно');
  redirect('/m/music/users/?id='.$account['ID']);
  
}

if (get('get') == 'delete_dir'){
  
  get_check_valid();
  $dir = db::get_string("SELECT `NAME` FROM `MUSIC_DIR` WHERE `ID` = ? LIMIT 1", [$id_dir]);
  
  ?>
  <div class='list'>
  <?=lg('Вы действительно хотите удалить альбом')?> <b><?=tabs($dir['NAME'])?></b>?<br /><br />
  <a href='/m/music/users/?id=<?=$account['ID']?>&dir=<?=$id_dir?>&get=delete_dir_ok&<?=TOKEN_URL?>' class='button'><?=icons('trash', 17, 'fa-fw')?> <?=lg('Удалить')?></a>
  <a href='/m/music/users/?id=<?=$account['ID']?>&dir=<?=$id_dir?>' class='button-o'><?=lg('Отмена')?></a>
  </div>
  <?
  
}