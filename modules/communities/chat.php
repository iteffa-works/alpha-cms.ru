<?php
$comm = db::get_string("SELECT `ID`,`URL`,`USER_ID` FROM `COMMUNITIES` WHERE `ID` = ? LIMIT 1", [intval(get('id'))]);
$par = db::get_string("SELECT `ADMINISTRATION`,`ID` FROM `COMMUNITIES_PAR` WHERE `COMMUNITY_ID` = ? AND `USER_ID` = ? AND `ACT` = ? LIMIT 1", [$comm['ID'], user('ID'), 1]);
html::title(lg('Чат сообщества %s', communities::name($comm['ID'])));
acms_header();
communities::blocked($comm['ID']);

if (!isset($comm['ID'])) {
  
  error('Неверная директива');
  redirect('/m/communities/');

}

if (config('PRIVATE_COMMUNITIES') == 0){
  
  error('Модуль отключен администратором');
  redirect('/');
  
}

if (access('communities', null) == true || $par['ADMINISTRATION'] == 1 || $par['ADMINISTRATION'] == 2){
  
  if (get('get') == 'delete_all_ok'){
    
    get_check_valid();
    
    db::get_set("DELETE FROM `COMMENTS` WHERE `OBJECT_TYPE` = ? AND `OBJECT_ID` = ?", ['comm_chat_comments', $comm['ID']]);
    db::get_set("DELETE FROM `LIKES` WHERE `OBJECT_TYPE` = ? AND `OBJECT_ID` = ?", ['comm_chat_comments', $comm['ID']]);
    db::get_set("DELETE FROM `ATTACHMENTS` WHERE `TYPE_POST` = ? AND `OBJECT_ID` = ?", ['comm_chat_comments', $comm['ID']]);
      
    success('Удаление прошло успешно');
    redirect('/m/communities/chat/?id='.$comm['ID']);
      
  }
  
  if (get('get') == 'delete_all'){
    
    get_check_valid();
    
    ?>
    <div class='list'>
    <?=lg('Вы действительно хотите очистить чат от всех сообщений')?>?<br /><br />
    <a href='/m/communities/chat/?id=<?=$comm['ID']?>&get=delete_all_ok&<?=TOKEN_URL?>' class='button'><?=icons('trash', 17, 'fa-fw')?> <?=lg('Очистить')?></a>
    <a href='/m/communities/chat/?id=<?=$comm['ID']?>' class='button-o'><?=lg('Отмена')?></a>
    </div>
    <?
    
  }
  
  ?>
  <div class='list'>
  <a href='/m/communities/chat/?id=<?=$comm['ID']?>&get=delete_all&<?=TOKEN_URL?>' class='btn'><?=icons('trash', 17, 'fa-fw')?> <?=lg('Очистить чат')?></a>
  </div>
  <?
  
}

if (!isset($par['ID'])){
  
  $comments_set = 'Писать в чат могут только участники сообщества';
  
}

if ($par['ADMINISTRATION'] == 2){
  
  $user_id = user('ID');
  
}else{
  
  $user_id = $comm['USER_ID'];
  
}

comments('/m/communities/chat/?id='.$comm['ID'], 'comm_chat', 0, 'message', $user_id, $comm['ID']);

back('/public/'.$comm['URL']);
acms_footer();