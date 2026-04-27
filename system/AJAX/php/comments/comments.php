<?php  
require ($_SERVER['DOCUMENT_ROOT'].'/system/connections/core.php');

if (ajax() == true){
  
  get_check_valid();
  
  $count_add = intval(post('count_add'));
  $action = tabs(post('action'));
  $action2 = tabs(post('action'));
  $author = intval(post('author'));
  $type = esc(tabs(post('type')));
  $o_id = intval(post('o_id'));
  $ajn = intval(post('ajn'));
  $ajn2 = $ajn;
  $html = null;
  $id = 0;  
  $r = intval(get('r'));
    
  if ($ajn == 1) {
    
    $ajn = 'ajax="no"';
    
  }else{
    
    $ajn = null;
    
  }
    
  $data = db::get_string_all("SELECT * FROM `COMMENTS` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ? AND `ID` > ? ".($r == 0 ? "AND `REPLY` = '0'" : null)." ORDER BY `ID` DESC", [$o_id, $type, $count_add]);
  while ($list = $data->fetch()){

    require (ROOT.'/system/connections/comments.php');    
    $html .= $mess;
    $id = $list['ID'];
  
  }
  
  $array = array(

    'html' => $html,
    'count_add' => $id
  
  );
  
  echo json_encode($array);
  
}else{
  
  echo lg('Не удалось установить соединение');
  
}