<?php  
require ($_SERVER['DOCUMENT_ROOT'].'/system/connections/core.php');

if (ajax() == true){
  
  get_check_valid();
  
  $type = esc(tabs(get('type')));
  $author = intval(get('author'));
  $id = intval(get('id'));
  $o_id = intval(get('o_id'));
  $countView = intval(post('count_add'));
  $startIndex = intval(post('count_show'));
  $ajn = intval(get('ajn'));
  $ajn2 = $ajn;
  $notification = intval(get('notif'));
  $action = '/system/AJAX/php/comments/comments_reply.php?id='.$id.'&ajn='.$ajn.'&o_id='.$o_id.'&type='.$type.'&author='.$author.'&'.TOKEN_URL;
  $action2 = tabs(base64_decode(get('action')));
  
  if ($ajn == 1) {
    
    $ajn = 'ajax="no"';
    
  }else{
    
    $ajn = null;
    
  }
    
  $data = db::get_string_all("SELECT * FROM `COMMENTS` WHERE `REPLY_USER_ID` = ? ORDER BY `ID` DESC LIMIT ".$startIndex.", ".$countView, [$id]);
  while ($list = $data->fetch()){
    
    $comment[] = $list;
  
  }
  
  if (empty($comment)){
    
    echo json_encode(array(
      
      'result' => 'finish'
    
    ));
  
  }else{
    
    session('COUNT_SHOW'.$o_id, $startIndex + 30);
    
    $html = null;    
    foreach ($comment AS $list){
      
      require (ROOT.'/system/connections/comments.php');    
      $html .= $mess;
    
    }
    
    echo json_encode(array(
      
      'result' => 'success',
      'html' => $html
    
    ));
    
  }
  
}else{
  
  echo lg('Не удалось установить соединение');
  
}