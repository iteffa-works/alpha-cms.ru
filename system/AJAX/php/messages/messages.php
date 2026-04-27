<?php  
require ($_SERVER['DOCUMENT_ROOT'].'/system/connections/core.php');

if (ajax() == true){
  
  get_check_valid();
  
  $count_add = intval(post('count_add'));
  $user_id = intval(get('id'));
  define('ACCOUNT_ID', $user_id);
  $html = null;
  $id = 0;
  $eye = db::get_column("SELECT COUNT(*) FROM `MAIL_MESSAGE` WHERE `USER_ID` = ? AND `MY_ID` = ? AND `READ` = '0' LIMIT 1", [$user_id, user('ID')]);
  
  messages::read(user('ID'), $user_id);
  
  $data = db::get_string_all("SELECT * FROM (SELECT * FROM `MAIL_MESSAGE` WHERE (`USER_ID` = ? OR `MY_ID` = ?) AND (`USER_ID` = ? OR `MY_ID` = ?) AND `USER` = ? AND `ID` > ? ORDER BY `TIME` DESC LIMIT 30) A ORDER BY `TIME`", [user('ID'), user('ID'), $user_id, $user_id, user('ID'), $count_add]);
  while ($list = $data->fetch()){
    
    require (ROOT.'/users/account/mail/plugins/list.php');
    
    $html .= $mess;
    $id = $list['ID'];
  
  }
  
  $array = array(

    'eye' => $eye,
    'html' => $html,
    'count_add' => $id
  
  );
  
  echo json_encode($array);
  
}else{
  
  echo lg('Не удалось установить соединение');
  
}