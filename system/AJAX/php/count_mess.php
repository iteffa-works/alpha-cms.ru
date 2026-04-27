<?php  
require ($_SERVER['DOCUMENT_ROOT'].'/system/connections/core.php');
access('users');

if (ajax() == true){
  
  $mess = db::get_column("SELECT COUNT(*) FROM `MAIL_MESSAGE` WHERE `USER_ID` = ? AND `USER` = ? AND `READ` = '0'", [user('ID'), user('ID')]);
  
  if ($mess > 99){
    
    $c_mess = "<small class='count-mess'>99+</small>";
  
  }elseif ($mess > 0){
    
    $c_mess = "<small class='count-mess'>".$mess."</small>";
  
  }else{
    
    $c_mess = null;
  
  }
  
  $notif = db::get_column("SELECT COUNT(*) FROM `NOTIFICATIONS` WHERE `USER_ID` = ? AND `READ` = '1'", [user('ID')]);
  
  if ($notif > 99){
    
    $c_notif = "<small class='count-mess'>99+</small>";
  
  }elseif ($notif > 0){
    
    $c_notif = "<small class='count-mess'>".$notif."</small>";
  
  }else{
    
    $c_notif = null;
  
  }
  
  $ta = db::get_column("SELECT COUNT(*) FROM `TAPE` WHERE `USER_ID` = ? AND `READ` = '1'", [user('ID')]);
  
  if ($ta > 99){
    
    $c_ta = "<small class='count-mess'>99+</small>";
  
  }elseif ($ta > 0){
    
    $c_ta = "<small class='count-mess'>".$ta."</small>";
  
  }else{
    
    $c_ta = null;
  
  }
  
  hooks::challenge('count_mess', 'count_mess');
  hooks::run('count_mess');
  
  echo json_encode(array(

    'count_mail' => $c_mess,
    'count_notif' => $c_notif,
    'count_tape' => $c_ta
  
  ));
  
}else{
  
  echo lg('Не удалось установить соединение');
  
}