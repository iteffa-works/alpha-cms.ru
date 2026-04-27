<?php
  
if (get('get') == 'delete_ok'){
  
  get_check_valid();
  
  db::get_set("DELETE FROM `LIKES` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ?", [$them['ID'], 'forum']);
  db::get_set("DELETE FROM `EYE` WHERE `OBJECT_ID` = ? AND `TYPE` = ?", [$them['ID'], 'forum']);
  db::get_set("DELETE FROM `ATTACHMENTS` WHERE `ID_POST` = ? AND `TYPE_POST` = ?", [$them['ID'], 'forum']);
  
  $data = db::get_string_all("SELECT * FROM `COMMENTS` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ?", [$them['ID'], 'forum_comments']);
  while ($list = $data->fetch()) {
    
    db::get_set("DELETE FROM `LIKES` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ?", [$list['ID'], 'forum_comments']);
    db::get_set("DELETE FROM `ATTACHMENTS` WHERE `ID_POST` = ? AND `TYPE_POST` = ?", [$list['ID'], 'forum_comments']);
    db::get_set("DELETE FROM `COMMENTS` WHERE `ID` = ? LIMIT 1", [$list['ID']]);
  
  }
  
  balls_add('FORUM', $them['USER_ID']);
  rating_add('FORUM', $them['USER_ID']);
  
  db::get_set("DELETE FROM `FORUM_THEM` WHERE `ID` = ? LIMIT 1", [$them['ID']]);
  
  if (access('forum', null) == true){
    
    logs('Форум - удаление темы [url=/m/forum/show/?id='.$them['ID'].']'.$them['NAME'].'[/url]', user('ID'));
    
  }
  
  success('Удаление прошло успешно');
  redirect('/m/forum/users/?id='.$them['USER_ID']);

}

if (get('get') == 'delete'){
  
  get_check_valid();
  
  ?>
  <div class='list'>
  <?=lg('Вы действительно хотите удалить тему')?> <b><?=tabs($them['NAME'])?></b>?<br /><br />
  <a href='/m/forum/show/?id=<?=$them['ID']?>&get=delete_ok&<?=TOKEN_URL?>' class='button'><?=icons('trash', 17, 'fa-fw')?> <?=lg('Удалить')?></a>
  <a href='/m/forum/show/?id=<?=$them['ID']?>' class='button-o'><?=lg('Отмена')?></a>
  </div>
  <?
  
}