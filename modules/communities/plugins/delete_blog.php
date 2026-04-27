<?php
  
if (get('get') == 'delete_ok'){
  
  get_check_valid();
  
  db::get_set("DELETE FROM `LIKES` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ?", [$blog['ID'], 'blogs']);
  db::get_set("DELETE FROM `EYE` WHERE `OBJECT_ID` = ? AND `TYPE` = ?", [$blog['ID'], 'blogs']);
  db::get_set("DELETE FROM `ATTACHMENTS` WHERE `ID_POST` = ? AND `TYPE_POST` = ?", [$blog['ID'], 'blogs']);
  
  $data = db::get_string_all("SELECT * FROM `COMMENTS` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ?", [$blog['ID'], 'blogs_comments']);
  while ($list = $data->fetch()) {
    
    db::get_set("DELETE FROM `LIKES` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ?", [$list['ID'], 'blogs_comments']);
    db::get_set("DELETE FROM `ATTACHMENTS` WHERE `ID_POST` = ? AND `TYPE_POST` = ?", [$list['ID'], 'blogs_comments']);
    db::get_set("DELETE FROM `COMMENTS` WHERE `ID` = ? LIMIT 1", [$list['ID']]);
  
  }
  
  db::get_set("DELETE FROM `BLOGS` WHERE `ID` = ? LIMIT 1", [$blog['ID']]);
  
  if (access('communities', null) == true){
    
    logs('Блоги сообществ - удаление записи [url=/m/communities/show_blog/?id='.$blog['ID'].']'.$blog['NAME'].'[/url]', user('ID'));
    
  }
  
  success('Удаление прошло успешно');
  redirect('/m/communities/blogs/?id='.$comm['ID']);

}

if (get('get') == 'delete'){
  
  get_check_valid();
  
  ?>
  <div class='list'>
  <?=lg('Вы действительно хотите удалить запись')?> <b><?=tabs($blog['NAME'])?></b>?<br /><br />
  <a href='/m/communities/show_blog/?id=<?=$blog['ID']?>&get=delete_ok&<?=TOKEN_URL?>' class='button'><?=icons('trash', 17, 'fa-fw')?> <?=lg('Удалить')?></a>
  <a href='/m/communities/show_blog/?id=<?=$blog['ID']?>' class='button-o'><?=lg('Отмена')?></a>
  </div>
  <?
  
}