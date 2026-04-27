<?php  
require ($_SERVER['DOCUMENT_ROOT'].'/system/connections/core.php');

if (ajax() == true){
  
  get_check_valid();
  
  $type = esc(tabs(get('type')));
  $action = base64_decode(tabs(get('action')));
  $action2 = base64_decode(tabs(get('action')));
  $author = intval(get('author'));
  $o_id = intval(get('o_id'));
  $countView = intval(post('count_add'));
  $startIndex = intval(post('count_show'));
  $ajn = intval(get('ajn'));
  $ajn2 = $ajn;
  $notification = intval(get('notif'));
  
  if ($ajn == 1) {
    
    $ajn = 'ajax="no"';
    
  }else{
    
    $ajn = null;
    
  }
    
  $data = db::get_string_all("SELECT * FROM `COMMENTS` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ? AND `REPLY` = '0' ORDER BY `ID` DESC LIMIT ".$startIndex.", ".$countView, [$o_id, $type]);
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