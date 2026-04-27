<?php
  
if (get('get') == 'delete_ok'){
  
  get_check_valid();
  
  db::get_set("DELETE FROM `LIKES` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ?", [$news['ID'], 'news']);
  db::get_set("DELETE FROM `EYE` WHERE `OBJECT_ID` = ? AND `TYPE` = ?", [$news['ID'], 'news']);
  db::get_set("DELETE FROM `ATTACHMENTS` WHERE `ID_POST` = ? AND `TYPE_POST` = ?", [$news['ID'], 'news']);
  
  $data = db::get_string_all("SELECT * FROM `COMMENTS` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ?", [$news['ID'], 'news_comments']);
  while ($list = $data->fetch()) {
    
    db::get_set("DELETE FROM `LIKES` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ?", [$list['ID'], 'news_comments']);
    db::get_set("DELETE FROM `ATTACHMENTS` WHERE `ID_POST` = ? AND `TYPE_POST` = ?", [$list['ID'], 'news_comments']);
    db::get_set("DELETE FROM `COMMENTS` WHERE `ID` = ? LIMIT 1", [$list['ID']]);
  
  }
  
  db::get_set("DELETE FROM `NEWS` WHERE `ID` = ? LIMIT 1", [$news['ID']]);
  
  logs('Новости - удаление новости [url=/m/news/show/?id='.$news['ID'].']'.$news['NAME'].'[/url]', user('ID'));
  
  success('Удаление прошло успешно');
  redirect('/m/news/');

}

if (get('get') == 'delete'){
  
  get_check_valid();
  
  ?>
  <div class='list'>
  <?=lg('Вы действительно хотите удалить новость')?> <b><?=tabs($news['NAME'])?></b>?<br /><br />
  <a href='/m/news/show/?id=<?=$news['ID']?>&get=delete_ok&<?=TOKEN_URL?>' class='button'><?=icons('trash', 17, 'fa-fw')?> <?=lg('Удалить')?></a>
  <a href='/m/news/show/?id=<?=$news['ID']?>' class='button-o'><?=lg('Отмена')?></a>
  </div>
  <?
  
}