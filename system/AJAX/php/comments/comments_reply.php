<?php  
require ($_SERVER['DOCUMENT_ROOT'].'/system/connections/core.php');

if (ajax() == true){
  
  $author = intval(get('author'));
  $type = esc(tabs(get('type')));
  $o_id = intval(get('o_id'));
  $ajn = intval(get('ajn'));
  $ajn2 = $ajn;
  $id = intval(get('id'));
  $notification = intval(get('notif'));
  $action = '/system/AJAX/php/comments/comments_reply.php?id='.$id.'&notif='.$notification.'&ajn='.$ajn.'&o_id='.$o_id.'&type='.$type.'&author='.$author.'&'.TOKEN_URL;
  $action2 = tabs(base64_decode(get('action')));
    
  if ($ajn == 1) {
    
    $ajn = 'ajax="no"';
    
  }else{
    
    $ajn = null;
    
  }
  
  get_check_valid();
  delete_comments_ajax($type, $author, $o_id);
  comments_likes_ajax($type, $notification);
  comments_dislikes_ajax($type);
  reply_add($o_id);
  
  $comments = db::get_string("SELECT `ID`,`USER_ID` FROM `COMMENTS` WHERE `ID` = ? LIMIT 1", [$id]);
  $reply_count = db::get_column("SELECT COUNT(*) FROM `COMMENTS` WHERE `REPLY_USER_ID` = ?", [$comments['ID']]);
  
  ?>
  <div class='list-menu'>
  <font size='+1'><?=lg('Все ответы')?> <span class='count'><?=$reply_count?></span></font>
  <span style='float: right;' onclick='modal_comments_close()'><?=icons('times', 25)?></span>
  </div>
  <?
  
  if (isset($comments['ID'])){
    
    ?>      
    <div class='modal_comments_optimize' style='height: <?=(type_version() ? 365 : 395)?>px'>
    <div id='comments_reply_list'>  
    <?php      
    $data = db::get_string_all("SELECT * FROM `COMMENTS` WHERE `REPLY_USER_ID` = ? ORDER BY `ID` DESC LIMIT 30", [$comments['ID']]);
    while ($list = $data->fetch()){
      
      require (ROOT.'/system/connections/comments.php');
      echo $mess;
      
    }
    
    ?></div><?
    
    if ($reply_count > 30){
      
      ?><div class='list-menu'><div class='comments-ended'><button count_show="<?=(intval(session('COUNT_SHOW'.$o_id)) != 0 ? intval(session('COUNT_SHOW'.$o_id)) : 30)?>" count_add="30" class="button" onclick="show_more('/system/AJAX/php/comments/comments_reply_list.php?type=<?=$type?>&ajn=<?=$ajn?>&id=<?=$comments['ID']?>&o_id=<?=$o_id?>&author=<?=$author?>&action=<?=base64_encode($action2)?>&notif=<?=$notification?>&<?=TOKEN_URL?>', '#show_more_r', '#comments_reply_list', 30, 'append')" name_show="<?=lg('Показать ещё')?> <?=icons('spinner fa-spin', 17)?>" name_finish="<?=lg('Показать ещё')?> <?=icons('angle-down', 17)?>" id="show_more_r" name_hide="<?=lg('Конец')?> <?=icons('times', 17)?>"><?=lg('Показать ещё')?> <?=icons('angle-down', 17)?></button></div></div><?
      
    }
    
    ?>
    </div>
    <?
    
  }else{
    
    ?><font color='red'><?=lg('Комментарий удален')?></font><?
    
  }
  
}else{
  
  echo lg('Не удалось установить соединение');
  
}