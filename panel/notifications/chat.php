<?php
html::title('Чат адмнистрации');
acms_header();
access('administration_show');

?>
<div class='navigation'>
<a href='/admin/desktop/'><?=icons('home', 25)?></a>
<?=icons('angle-right', 25, 'fa-fw')?>
<?=lg('Чат администрации')?>
</div>
<?
  
if (access('comments', null) == true && db::get_column("SELECT COUNT(*) FROM `COMMENTS` WHERE `OBJECT_TYPE` = 'admin_chat_comments' LIMIT 1") > 0){
  
  if (get('get') == 'delete_all_ok'){
    
    get_check_valid();
    
    db::get_set("DELETE FROM `COMMENTS` WHERE `OBJECT_TYPE` = 'admin_chat'");
    db::get_set("DELETE FROM `LIKES` WHERE `OBJECT_TYPE` = 'admin_chat'");
    db::get_set("DELETE FROM `ATTACHMENTS` WHERE `TYPE_POST` = 'admin_chat'");
      
    success('Удаление прошло успешно');
    redirect('/admin/notifications/chat/');
      
  }
  
  if (get('get') == 'delete_all'){
    
    get_check_valid();
    
    ?>
    <div class='list'>
    <?=lg('Вы действительно хотите очистить чат от всех сообщений')?>?<br /><br />
    <a href='/admin/notifications/chat/?get=delete_all_ok&<?=TOKEN_URL?>' class='button'><?=icons('trash', 17, 'fa-fw')?> <?=lg('Очистить')?></a>
    <a href='/admin/notifications/chat/' class='button-o'><?=lg('Отмена')?></a>
    </div>
    <?
    
  }
  
  ?>
  <div class='list'>
  <a href='/admin/notifications/chat/?get=delete_all&<?=TOKEN_URL?>' class='button'><?=icons('trash', 17, 'fa-fw')?> <?=lg('Очистить чат')?></a>
  </div>
  <?
  
}

comments('/admin/notifications/chat/', 'admin_chat', 0);

back('/admin/desktop/');
acms_footer();