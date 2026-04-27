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
  
  balls_add('BLOGS', $blog['USER_ID']);
  rating_add('BLOGS', $blog['USER_ID']);
  
  if (access('blogs', null) == true){
    
    logs('Блоги - удаление записи [url=/m/blogs/show/?id='.$blog['ID'].']'.$blog['NAME'].'[/url]', user('ID'));
    
  }
  
  success('Удаление прошло успешно');
  redirect('/m/blogs/users/?id='.$blog['USER_ID']);

}

if (get('get') == 'delete'){
  
  get_check_valid();
  
  ?>
  <div class='list'>
  <?=lg('Вы действительно хотите удалить запись')?> <b><?=tabs($blog['NAME'])?></b>?<br /><br />
  <a href='/m/blogs/show/?id=<?=$blog['ID']?>&get=delete_ok&<?=TOKEN_URL?>' class='button'><?=icons('trash', 17, 'fa-fw')?> <?=lg('Удалить')?></a>
  <a href='/m/blogs/show/?id=<?=$blog['ID']?>' class='button-o'><?=lg('Отмена')?></a>
  </div>
  <?
  
}