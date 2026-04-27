<?php
  
if (access('comments', null) == true){
  
  if (get('get') == 'delete_all_ok'){
    
    get_check_valid();
    
    db::get_set("DELETE FROM `COMMENTS` WHERE `OBJECT_TYPE` = 'guestbook_comments'");
    db::get_set("DELETE FROM `LIKES` WHERE `OBJECT_TYPE` = 'guestbook_comments'");
    db::get_set("DELETE FROM `ATTACHMENTS` WHERE `TYPE_POST` = 'guestbook_comments'");
      
    success('Удаление прошло успешно');
    redirect('/m/guestbook/');
      
  }
  
  if (get('get') == 'delete_all'){
    
    get_check_valid();
    
    ?>
    <div class='list'>
    <?=lg('Вы действительно хотите очистить гостевую от всех сообщений')?>?<br /><br />
    <a href='/m/guestbook/?get=delete_all_ok&<?=TOKEN_URL?>' class='button'><?=icons('trash', 17, 'fa-fw')?> <?=lg('Очистить')?></a>
    <a href='/m/guestbook/' class='button-o'><?=lg('Отмена')?></a>
    </div>
    <?
    
  }
  
  ?>
  <div class='list'>
  <a href='/m/guestbook/?get=delete_all&<?=TOKEN_URL?>' class='btn'><?=icons('trash', 17, 'fa-fw')?> <?=lg('Очистить гостевую')?></a>
  </div>
  <?
  
}