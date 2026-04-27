<?php  
require ($_SERVER['DOCUMENT_ROOT'].'/system/connections/core.php');
access('users');

if (ajax() == true){
  
  get_check_valid();
  
  $id = intval(get('id'));
  define('ACCOUNT_ID', $id);
  $countView = intval(post('count_add'));
  $startIndex = intval(post('count_show'));
  
  $data = db::get_string_all("SELECT * FROM (SELECT * FROM `MAIL_MESSAGE` WHERE (`USER_ID` = ? OR `MY_ID` = ?) AND (`USER_ID` = ? OR `MY_ID` = ?) AND `USER` = ? ORDER BY `TIME` DESC LIMIT ".$startIndex.", ".$countView.") A ORDER BY `TIME`", [user('ID'), user('ID'), $id, $id, user('ID')]);
while ($list = $data->fetch()){
    
    $mess[] = $list;
  
  }
  
  if (empty($mess)){
    
    echo json_encode(array(
      
      'result' => 'finish'
    
    ));
  
  }else{
    
    session('COUNT_MESS', $startIndex + 30);
    
    $html = null;    
    foreach ($mess AS $list){
      
      require(ROOT.'/users/account/mail/plugins/list.php');   
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