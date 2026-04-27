<?php  
$comm = db::get_string("SELECT * FROM `COMMUNITIES` WHERE `ID` = ? LIMIT 1", [intval(get('id'))]);
$par = db::get_string("SELECT * FROM `COMMUNITIES_PAR` WHERE `COMMUNITY_ID` = ? AND `USER_ID` = ? AND `ACT` = ? LIMIT 1", [$comm['ID'], user('ID'), 1]);
acms_header(lg('Удаление сообщества %s', communities::name($comm['ID'])), 'users');
communities::blocked($comm['ID']);
is_active_module('PRIVATE_COMMUNITIES');
get_check_valid();

if (!isset($comm['ID'])) {
  
  error('Неверная директива');
  redirect('/m/communities/');

}

if (isset($par['ID']) && $par['ADMINISTRATION'] == 1 || access('communities', null) == true){
  
  if (post('ok_delete_comm')){
    
    $data = db::get_string_all("SELECT * FROM `BLOGS` WHERE `COMMUNITY` = ?", $comm['ID']);
    while ($list = $data->fetch()) {
      
      $blogdata = db::get_string_all("SELECT * FROM `COMMENTS` WHERE `OBJECT_ID` = ?", $list['ID']); 
      while ($commentlist = $blogdata->fetch()) {
        
        db::get_set("DELETE FROM `COMMENTS` WHERE `ID` = ? AND `OBJECT_TYPE` = ?", [$commentlist['ID'], 'blogs_comments']); 
        
        $likecommentdata = db::get_string_all("SELECT * FROM `LIKES` WHERE `OBJECT_ID` = ?", $commentlist['ID']); 
        while ($likecommentlist = $likecommentdata->fetch()) {
          
          db::get_set("DELETE FROM `LIKES` WHERE `ID` = ? AND `OBJECT_TYPE` = ?", [$likecommentlist['ID'], 'blogs_comments']);
        
        }	
        
        $attachmentsdata = db::get_string_all("SELECT * FROM `ATTACHMENTS` WHERE `ID_POST` = ? AND `TYPE_POST` = ?", [$commentlist['ID'], 'blogs_comments']);
        while ($attachmentslist = $attachmentsdata->fetch()) {
          
          db::get_set("DELETE FROM `ATTACHMENTS` WHERE `ID` = ? AND `TYPE_POST` = ?", [$attachmentslist['ID'], 'blogs_comments']);
        
        }
      
      }
      
      $likedata = db::get_string_all("SELECT * FROM `LIKES` WHERE `OBJECT_ID` = ?", $list['ID']); 
      while ($likelist = $likedata->fetch()) {
        
        db::get_set("DELETE FROM `LIKES` WHERE `ID` = ? AND `OBJECT_TYPE` = ?", [$likelist['ID'], 'blogs']);
      
      }
      
      $eyedata = db::get_string_all("SELECT * FROM `EYE` WHERE `OBJECT_ID` = ? AND `TYPE` = ?", [$list['ID'], 'blogs']);
      while ($eyelist = $eyedata->fetch()) {
        
        db::get_set("DELETE FROM `EYE` WHERE `ID` = ? AND `TYPE` = ?", [$eyelist['ID'], 'blogs']);
      
      }
      
      db::get_set("DELETE FROM `BLOGS` WHERE `ID` = ?", $list['ID']);
    
    }
    
    $messdata = db::get_string_all("SELECT * FROM `COMMENTS` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ?", [$comm['ID'], 'comm_chat_comments']);
    while ($messlist = $messdata->fetch()) {
      
      db::get_set("DELETE FROM `COMMENTS` WHERE `ID` = ? AND `OBJECT_TYPE` = ?", [$messlist['ID'], 'comm_chat_comments']);
      
      $attachmentsdata = db::get_string_all("SELECT * FROM `ATTACHMENTS` WHERE `ID_POST` = ? AND `TYPE_POST` = ?", [$messlist['ID'], 'comm_chat_comments']);
      while ($attachmentslist = $attachmentsdata->fetch()) {
        
        db::get_set("DELETE FROM `ATTACHMENTS` WHERE `ID` = ? AND `TYPE_POST` = ?", [$attachmentslist['ID'], 'comm_chat_comments']);
      
      }
      
      $likedata = db::get_string_all("SELECT * FROM `LIKES` WHERE `OBJECT_ID` = ?", $messlist['ID']); 
      while ($likelist = $likedata->fetch()) {
        
        db::get_set("DELETE FROM `LIKES` WHERE `ID` = ? AND `OBJECT_TYPE` = ?", [$likelist['ID'], 'comm_chat_comments']);
      
      }	
    
    }
    
    db::get_set("DELETE FROM `COMMUNITIES_JURNAL` WHERE `COMMUNITY_ID` = ?", $comm['ID']);
    db::get_set("DELETE FROM `COMMUNITIES_BAN` WHERE `COMMUNITY_ID` = ?", $comm['ID']);
	db::get_set("DELETE FROM `COMMUNITIES_PAR` WHERE `COMMUNITY_ID` = ?", $comm['ID']);
	db::get_set("DELETE FROM `COMMUNITIES` WHERE `ID` = ?", $comm['ID']); 
    
    success('Удаление прошло успешно');
    redirect('/m/communities/users/?id='.user('ID'));
  
  }
  
  ?>    
  <div class='list'>
  <form method='post' class='ajax-form' action='/m/communities/delete/?id=<?=$comm['ID']?>&<?=TOKEN_URL?>'>
  <?=lg('Вы действительно хотите удалить сообщество %s? Отменить действие будет невозможно.', '<b>'.communities::name($comm['ID']).'</b>')?><br /><br />
  <b><?=lg('Записей в блоге')?>:</b> <span class='count'><?=db::get_column("SELECT COUNT(`ID`) FROM `BLOGS` WHERE `COMMUNITY` = ?", [$comm['ID']])?></span><br />
  <b><?=lg('Сообщений в чате')?>:</b> <span class='count'><?=db::get_column("SELECT COUNT(`ID`) FROM `COMMENTS` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ?", [$comm['ID'], 'comm_chat_comments'])?></span><br />
  <b><?=lg('Тем на форуме')?>:</b> <span class='count'><?=db::get_column("SELECT COUNT(`ID`) FROM `COMMUNITIES_FORUM_THEM` WHERE `COMMUNITY_ID` = ?", [$comm['ID']])?></span><br />
  <b><?=lg('Участников')?>:</b> <span class='count'><?=db::get_column("SELECT COUNT(`ID`) FROM `COMMUNITIES_PAR` WHERE `COMMUNITY_ID` = ? AND `ACT` = ?", [$comm['ID'], 1])?></span><br /><br />
  <?=html::button('button ajax-button', 'ok_delete_comm', 'trash', 'Удалить')?>
  <a class='button-o' href='/m/communities/edit/?id=<?=$comm['ID']?>'><?=lg('Отмена')?></a>
  </form>
  </div>
  <?
    
}else{
  
  error('Нет прав');
  redirect('/m/communities/');
  
}

back('/public/'.$comm['URL']);
acms_footer();