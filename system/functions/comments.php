<?php 
  
/*
---------------------------
Ответ на сообщение
Отображение над полем ввода
---------------------------
*/
  
function reply($action, $o_id) {
  
  ?><div id='reply'><?
    
  $comments = db::get_string("SELECT `ID`,`USER_ID` FROM `COMMENTS` WHERE `ID` = ? LIMIT 1", [intval(session('REPLY_ID'.$o_id))]);
  $user = db::get_string("SELECT `LOGIN` FROM `USERS` WHERE `ID` = ? LIMIT 1", [$comments['USER_ID']]);
  if (isset($comments['ID']) && $comments['USER_ID'] != user('ID')){  
    
    ?>
    <div class='reply'>
    <?=lg('Ответ')?> <b><a href='/id<?=$comments['USER_ID']?>'><?=$user['LOGIN']?></a></b>
    <span class='reply-close' onclick="request('<?=url_request_get($action)?>reply_no=<?=$comments['ID']?>&<?=TOKEN_URL?>', '#reply')"><?=icons('times', 17)?></a>
    </div>
    <?
    
  }
  
  ?></div><?
  
}
  
/*
------------------------
Счетчик лайков/дислайков
------------------------
*/  
  
function likescount($id, $type, $action){
  
  $likes = db::get_column("SELECT COUNT(`ID`) FROM `LIKES` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ? AND `TYPE` = ? LIMIT 1", [$id, $type, 'like']);
  $dislikes = db::get_column("SELECT COUNT(`ID`) FROM `LIKES` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ? AND `TYPE` = ? LIMIT 1", [$id, $type, 'dislike']);
  
  if ($likes < $dislikes){
    
    $l = "<font color='#FB6083'>".($likes - $dislikes)."</font>";
  
  }elseif ($likes > $dislikes){
    
    $l = "<font color='#3DC999'>".($likes - $dislikes)."</font>";
  
  }else{
    
    $l = 0;
  
  }
  
  $ulikes = db::get_column("SELECT COUNT(`ID`) FROM `LIKES` WHERE `OBJECT_ID` = ? AND `USER_ID` = ? AND `OBJECT_TYPE` = ? AND `TYPE` = ? LIMIT 1", [$id, user('ID'), $type, 'like']);
  $udislikes = db::get_column("SELECT COUNT(`ID`) FROM `LIKES` WHERE `OBJECT_ID` = ? AND `USER_ID` = ? AND `OBJECT_TYPE` = ? AND `TYPE` = ? LIMIT 1", [$id, user('ID'), $type, 'dislike']);
  
  if ($ulikes > 0){
    
    $cl = "color: #3DC999";
  
  }else{
    
    $cl = null;
  
  }
  
  if ($udislikes > 0){
    
    $cd = "color: #FB6083";
  
  }else{
    
    $cd = null;
  
  }
  
  return '<span style="'.$cl.'" class="comments-list-like-b" onclick="request(\''.url_request_get($action).'like='.$id.'&'.TOKEN_URL.'\', \'#like'.$id.'\')">'.icons('thumbs-up', 18).'</span>
'.$l.'
<span style="'.$cd.'" class="comments-list-like-b" onclick="request(\''.url_request_get($action).'dislike='.$id.'&'.TOKEN_URL.'\', \'#like'.$id.'\')">'.icons('thumbs-down', 18).'</span>';
  
}

/*
-----------------
Добавление лайков
-----------------
*/

function comments_likes_ajax($type, $notification = 1){
  
  if (get('like') && user('ID') > 0) {
    
    get_check_valid();
    
    if (db::get_column("SELECT COUNT(`ID`) FROM `LIKES` WHERE `USER_ID` = ? AND `OBJECT_ID` = ? AND `OBJECT_TYPE` = ? AND `TYPE` = ? LIMIT 1", [user('ID'), intval(get('like')), $type, 'like']) == 0) {
      
      db::get_add("INSERT INTO `LIKES` (`USER_ID`, `TIME`, `OBJECT_ID`, `OBJECT_TYPE`, `TYPE`) VALUES (?, ?, ?, ?, ?)", [user('ID'), TM, intval(get('like')), $type, 'like']);
      db::get_set("DELETE FROM `LIKES` WHERE `USER_ID` = ? AND `OBJECT_ID` = ? AND `OBJECT_TYPE` = ? AND `TYPE` = ? LIMIT 1", [user('ID'), intval(get('like')), $type, 'dislike']);
      db::get_set("UPDATE `COMMENTS` SET `COUNT` = `COUNT` + '1' WHERE `ID` = ? LIMIT 1", [intval(get('like'))]);
      
      /*
      --------------------
      Уведомления в журнал
      --------------------
      */
      
      if ($notification == 1) {
        
        $lk = db::get_string("SELECT `ID`,`USER_ID` FROM `COMMENTS` WHERE `ID` = ? AND `USER_ID` != ? LIMIT 1", [intval(get('like')), user('ID')]);
        
        if (isset($lk['ID'])){
          
          if (db::get_column("SELECT COUNT(*) FROM `NOTIFICATIONS_SETTINGS` WHERE `USER_ID` = ? AND `LIKES` = ? LIMIT 1", [$lk['USER_ID'], 1]) == 1){ 
            
            db::get_add("INSERT INTO `NOTIFICATIONS` (`USER_ID`, `OBJECT_ID`, `OBJECT_ID_LIST`, `TIME`, `TYPE`) VALUES (?, ?, ?, ?, ?)", [$lk['USER_ID'], user('ID'), $lk['ID'], TM, $type.'_like']);
          
          }
        
        }
      
      }
    
    }else{
      
      db::get_set("DELETE FROM `LIKES` WHERE `USER_ID` = ? AND `OBJECT_ID` = ? AND `OBJECT_TYPE` = ? AND `TYPE` = ? LIMIT 1", [user('ID'), intval(get('like')), $type, 'like']);
      db::get_set("UPDATE `COMMENTS` SET `COUNT` = `COUNT` - '1' WHERE `ID` = ? LIMIT 1", [intval(get('like'))]);
    
    }
  
  }
  
}

/*
--------------------
Добавление дислайков
--------------------
*/

function comments_dislikes_ajax($type){
  
  if (get('dislike') && user('ID') > 0) {
    
    get_check_valid();
    
    if (db::get_column("SELECT COUNT(`ID`) FROM `LIKES` WHERE `USER_ID` = ? AND `OBJECT_ID` = ? AND `OBJECT_TYPE` = ? AND `TYPE` = ? LIMIT 1", [user('ID'), intval(get('dislike')), $type, 'dislike']) == 0) {
      
      db::get_add("INSERT INTO `LIKES` (`USER_ID`, `TIME`, `OBJECT_ID`, `OBJECT_TYPE`, `TYPE`) VALUES (?, ?, ?, ?, ?)", [user('ID'), TM, intval(get('dislike')), $type, 'dislike']);
      db::get_set("DELETE FROM `LIKES` WHERE `USER_ID` = ? AND `OBJECT_ID` = ? AND `OBJECT_TYPE` = ? AND `TYPE` = ? LIMIT 1", [user('ID'), intval(get('dislike')), $type, 'like']);
      db::get_set("UPDATE `COMMENTS` SET `COUNT` = `COUNT` + '1' WHERE `ID` = ? LIMIT 1", [intval(get('dislike'))]);
    
    }else{
      
      db::get_set("DELETE FROM `LIKES` WHERE `USER_ID` = ? AND `OBJECT_ID` = ? AND `OBJECT_TYPE` = ? AND `TYPE` = ? LIMIT 1", [user('ID'), intval(get('dislike')), $type, 'dislike']);
      db::get_set("UPDATE `COMMENTS` SET `COUNT` = `COUNT` - '1' WHERE `ID` = ? LIMIT 1", [intval(get('dislike'))]);
    
    }
  
  }
  
}

/*
------------------
Удаление сообщений
------------------
*/

function delete_comments_ajax($type, $author, $o_id){
  
  if (get('delete_comments')){
    
    get_check_valid();
    
    $comments = db::get_string("SELECT `ID`,`USER_ID` FROM `COMMENTS` WHERE `ID` = ? AND `OBJECT_ID` = ? AND `OBJECT_TYPE` = ? LIMIT 1", [intval(get('delete_comments')), $o_id, $type]);                                        
    if (isset($comments['ID'])){
      
      if ($comments['USER_ID'] == user('ID') || $author == user('ID') && user('ID') > 0 || access('comments', null) == true) {
        
        $data = db::get_string_all("SELECT * FROM `COMMENTS` WHERE (`REPLY_USER_ID` = ? OR `REPLY` = ?)", [$comments['ID'], $comments['ID']]);
        while($list = $data->fetch()) {
          
          db::get_set("DELETE FROM `LIKES` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ?", [$list['ID'], $type]);
          db::get_set("DELETE FROM `ATTACHMENTS` WHERE `ID_POST` = ? AND `TYPE_POST` = ?", [$list['ID'], $type]);
          db::get_set("DELETE FROM `COMMENTS` WHERE `ID` = ? LIMIT 1", [$list['ID']]);
        
        }
        
        db::get_set("DELETE FROM `LIKES` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ?", [$comments['ID'], $type]);
        db::get_set("DELETE FROM `ATTACHMENTS` WHERE `ID_POST` = ? AND `TYPE_POST` = ?", [$comments['ID'], $type]);
        db::get_set("DELETE FROM `COMMENTS` WHERE `ID` = ? LIMIT 1", [$comments['ID']]);
        
        if ($type == 'forum_comments' || $type == 'blogs_comments' || $type == 'guestbook_comments' || $type == 'videos_comments' || $type == 'music_comments' || $type == 'photos_comments' || $type == 'files_comments') {
          
          balls_add(strtoupper($type), $comments['USER_ID']);
          rating_add(strtoupper($type), $comments['USER_ID']);
          
        }
        
        if (access('comments', null) == true){
          
          logs('Комментарии - удаление комментария', user('ID'));
        
        }
        
      }  
      
    }                                                                                                
    
  }
  
}
  
/*
------------------
Ответ на сообщения
------------------
*/

function reply_add($o_id) {
  
  if (get('reply') && user('ID') > 0){
    
    get_check_valid();
    
    $comments = db::get_string("SELECT `ID`,`USER_ID`,`REPLY_USER_ID` FROM `COMMENTS` WHERE `ID` = ? LIMIT 1", [intval(get('reply'))]); 
    if (isset($comments['ID']) && $comments['USER_ID'] != user('ID')){
      
      session('REPLY_ID'.$o_id, $comments['ID']);
      
      if ($comments['REPLY_USER_ID'] == 0) {
        
        session('REPLY_USER_ID'.$o_id, $comments['ID']);
        
      }else{
        
        session('REPLY_USER_ID'.$o_id, $comments['REPLY_USER_ID']);
        
      }

      session('REPLY_USER'.$o_id, $comments['USER_ID']);
      
    }
    
  }
  
  if (get('reply_no') && user('ID') > 0){
    
    get_check_valid();
    
    session('REPLY_ID'.$o_id, null);
    session('REPLY_USER_ID'.$o_id, null);
    session('REPLY_USER'.$o_id, null);
    
  }
  
}
  
/*
-------------------
Модуль комментариев
-------------------
*/
  
function comments($action, $type, $notification = 0, $post_name = 'message', $author = 0, $o_id = 0, $sub = 0, $r = 0){
  
  //$action - ссылка. Используется для отправки данных через post
  //$type - тип комментариев
  //$notification - если используется модуль оповещений в журнал. Если 1 - включен, если 0 - отключен
  //$post_name - post имя отправки данных
  //$author - автор поста (если комментарии присоединены к посту модуля)
  //$o_id - ID объекта
  //$r - режим комментариев
  
  global $comments_set;
  
  if (strpos($type, '_comments') === false) { $type = $type.'_comments'; }
  
  delete_comments_ajax($type, $author, $o_id);
  comments_likes_ajax($type, $notification);
  comments_dislikes_ajax($type);
  reply_add($o_id);
  
  $action2 = $action;
  
  if (url_request_validate('/admin/') == true) {
    
    $ajn = "ajax='no'";
    $ajn2 = 1;
    
  }else{
    
    $ajn = null;
    $ajn2 = 0;
    
  }
  
  /*
  ------------------
  Отправка сообщений
  ------------------
  */
  
  if (post($type)){
    
    $at = db::get_column("SELECT COUNT(*) FROM `ATTACHMENTS` WHERE `USER_ID` = ? AND `ACT` = '0' AND `TYPE_POST` = ? LIMIT 1", [user('ID'), $type]);
    
    if ($at > 0){
      
      $limit = 0;
      
    }else{
      
      $limit = 1;
      
    }
    
    $reply = intval(session('REPLY_ID'.$o_id));
    $reply_user_id = intval(session('REPLY_USER_ID'.$o_id));
    $reply_user = intval(session('REPLY_USER'.$o_id));
    
    valid::create(array(
      
      'COMMENTS' => [$post_name, 'text', [$limit, 5000], 'Сообщение', $limit]
    
    ));
    
    $comments = db::get_string("SELECT `MESSAGE` FROM `COMMENTS` WHERE `USER_ID` = ? AND `OBJECT_ID` = ? AND `OBJECT_TYPE` = ? ORDER BY `TIME` DESC LIMIT 1", [user('ID'), $type, $o_id]);
    
    if (COMMENTS == $comments['MESSAGE'] && $limit == 1){
      
      error('Такое сообщение было добавлено недавно');
      redirect($action);
    
    }
    
    if (ERROR_LOG == 1){
      
      redirect($action);
    
    }
    
    $id = db::get_add("INSERT INTO `COMMENTS` (`USER_ID`, `OBJECT_TYPE`, `OBJECT_ID`, `TIME`, `MESSAGE`, `REPLY`, `REPLY_USER_ID`, `REPLY_USER`, `SUB_OBJECT_ID`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)", [user('ID'), $type, $o_id, TM, COMMENTS, $reply, $reply_user_id, $reply_user, $sub]);
    
    if ($at > 0){
      
      db::get_set("UPDATE `ATTACHMENTS` SET `ID_POST` = ?, `ACT` = '1' WHERE `USER_ID` = ? AND `ACT` = '0' AND `TYPE_POST` = ?", [$id, user('ID'), $type]);
      
    }
    
    if ($type == 'forum_comments'){
      
      db::get_set("UPDATE `FORUM_THEM` SET `ACT_TIME` = ? WHERE `ID` = ? LIMIT 1", [TM, $o_id]);
      
    }
    
    if ($type == 'forum_comments' || $type == 'blogs_comments' || $type == 'guestbook_comments' || $type == 'videos_comments' || $type == 'music_comments' || $type == 'photos_comments' || $type == 'files_comments') {
      
      balls_add(strtoupper($type));
      rating_add(strtoupper($type));
      
    }
    
    /*
    --------------------
    Уведомления в журнал
    --------------------
    */
    
    if ($notification == 1) {
      
      if ($reply > 0) {
        
        $replyc = db::get_string("SELECT `ID`,`USER_ID` FROM `COMMENTS` WHERE `ID` = ? AND `USER_ID` != ? LIMIT 1", [$reply, user('ID')]);
        if (isset($replyc['ID'])){
          
          if ($replyc['USER_ID'] != $author && db::get_column("SELECT COUNT(*) FROM `NOTIFICATIONS_SETTINGS` WHERE `USER_ID` = ? AND `COMMENTS` = ? LIMIT 1", [$replyc['USER_ID'], 1]) == 1){ 
            
            db::get_add("INSERT INTO `NOTIFICATIONS` (`USER_ID`, `OBJECT_ID`, `OBJECT_ID_LIST`, `TIME`, `TYPE`) VALUES (?, ?, ?, ?, ?)", [$replyc['USER_ID'], user('ID'), $replyc['ID'], TM, $type.'_reply']);
          
          }
          
        }
        
      }
      
      if ($author > 0 && $author != user('ID') && db::get_column("SELECT COUNT(*) FROM `NOTIFICATIONS_SETTINGS` WHERE `USER_ID` = ? AND `COMMENTS` = ? LIMIT 1", [$author, 1]) == 1){ 
        
        db::get_add("INSERT INTO `NOTIFICATIONS` (`USER_ID`, `OBJECT_ID`, `OBJECT_ID_LIST`, `TIME`, `TYPE`) VALUES (?, ?, ?, ?, ?)", [$author, user('ID'), $o_id, TM, $type]);
      
      }
      
    }
    
    session('REPLY_ID'.$o_id, null);
    session('REPLY_USER_ID'.$o_id, null);
    session('REPLY_USER'.$o_id, null);
    redirect($action);
    
  }
  
  /*
  ----------------
  Список сообщений
  ----------------
  */
  
  ?>
  <div id='body-top-comments' id_post='<?=intval(session('REPLY_ID'.$o_id))?>' pixel='60'></div>
  <div class='modal_phone_comments modal_comments_close' id='comments_reply2' onclick="modal_comments_close()"></div>
  <div id='comments_reply' class='modal_comments modal_comments_open'>  
  <div id='mcload'></div>
  </div>
  <?
    
  $mess = db::get_string("SELECT `ID` FROM `COMMENTS` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ? AND `REPLY` = '0' ORDER BY `TIME` DESC LIMIT 1", [$o_id, $type]);
    
  if (user('ID') == 0 && $r == 0 && config('COMMENTS_SET') == 0 || user('ID') > 0 && $r == 0 && settings('COMMENTS_FORMAT') == 1) {
    
    $count = db::get_column("SELECT COUNT(*) FROM `COMMENTS` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ? AND `REPLY` = '0'", [$o_id, $type]);
    
    if ($count > 0){
      
      ?><div class='list-body'><?
      
    }
    
    ?>
    <div id='ajax_comments' action='/system/AJAX/php/comments/comments.php?<?=TOKEN_URL?>' o_id='<?=$o_id?>' ajn='<?=$ajn2?>' count_add='<?=$mess['ID']?>' actionv='<?=$action?>' author='<?=$author?>' type='<?=$type?>'></div>
    <div id='comments_list'>
    <?
      
    $data = db::get_string_all("SELECT * FROM `COMMENTS` WHERE `OBJECT_TYPE` = ? AND `OBJECT_ID` = ? AND `REPLY` = '0' ORDER BY `TIME` DESC LIMIT ".(intval(session('COUNT_SHOW'.$o_id)) != 0 ? intval(session('COUNT_SHOW'.$o_id)) : 30), [$type, $o_id]);
    while ($list = $data->fetch()){
      
      require (ROOT.'/system/connections/comments.php');
      echo $mess;
    
    }
    
    ?></div><?
      
    if ($count > 30){
      
      ?>
      <div class='list-menu'><div class='comments-ended'><button count_show="<?=(intval(session('COUNT_SHOW'.$o_id)) != 0 ? intval(session('COUNT_SHOW'.$o_id)) : 30)?>" count_add="30" class="button" onclick="show_more('/system/AJAX/php/comments/comments_list.php?type=<?=$type?>&ajn=<?=$ajn2?>&o_id=<?=$o_id?>&author=<?=$author?>&action=<?=base64_encode($action)?>&notif=<?=$notification?>&<?=TOKEN_URL?>', '#show_more_c', '#comments_list', 30, 'append')" name_show="<?=lg('Показать ещё')?> <?=icons('spinner fa-spin', 17)?>" name_finish="<?=lg('Показать ещё')?> <?=icons('angle-down', 17)?>" id="show_more_c" name_hide="<?=lg('Конец')?> <?=icons('times', 17)?>"><?=lg('Показать ещё')?> <?=icons('angle-down', 17)?></button></div></div>
      <?
      
    }
    
    if ($count == 0){
      
      html::empty('Пока нет комментариев', 'comments-o');
    
    }else{
      
      ?></div><?
      
    }
    
  }else{
    
    $column = db::get_column("SELECT COUNT(*) FROM `COMMENTS` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ?", [$o_id, $type]);
    $spage = spage($column, PAGE_SETTINGS);
    $page = page($spage);
    $limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;
    
    if ($column == 0){ 
      
      html::empty('Пока нет комментариев', 'comments-o');
    
    }else{
      
      ?><div class='list-body'><?
      
    }
    
    $mess = db::get_string("SELECT `ID` FROM `COMMENTS` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ? ORDER BY `TIME` DESC LIMIT 1", [$o_id, $type]);
    
    ?>
    <div id='ajax_comments' action='/system/AJAX/php/comments/comments.php?<?=TOKEN_URL?>&r=1' o_id='<?=$o_id?>' ajn='<?=$ajn2?>' count_add='<?=$mess['ID']?>' actionv='<?=$action?>' author='<?=$author?>' type='<?=$type?>'></div>
    <div id='comments_list'>
    <?
    
    $data = db::get_string_all("SELECT * FROM `COMMENTS` WHERE `OBJECT_TYPE` = ? AND `OBJECT_ID` = ? ORDER BY `ID` DESC LIMIT ".$limit.", ".PAGE_SETTINGS, [$type, $o_id]);
    while ($list = $data->fetch()){
      
      require (ROOT.'/system/connections/comments.php');
      echo $mess;
      
    }
    
    ?></div><?
    
    if ($column > 0){ 
      
      ?></div><?
      
    }
    
    get_page(url_request_get($action), $spage, $page, 'list');
    
  }
  
  ?><button class='mail-message-scrollheight' id='OnTop'><?=icons('angle-up', 20)?></button><?
  
  if (user('ID') > 0 && str($comments_set) == 0){
    
    html::comment($post_name, $action, null, $type, 'count_char', $o_id);
    
  }else{
    
    html::empty($comments_set, 'lock');
    
  }
  
  ?><div id='body-top-comments' id_post='0' pixel='0'></div><?
  
}