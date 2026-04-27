<?php  
require ($_SERVER['DOCUMENT_ROOT'].'/system/connections/core.php');
access('users');

if (ajax() == true){

  $countView = intval(post('count_add'));
  $startIndex = intval(post('count_show'));                                 
  
  $data = db::get_string_all("SELECT `ID`,`SHIF`,`EXT` FROM `PHOTOS` WHERE `USER_ID` = ? ORDER BY `TIME` DESC LIMIT ".$startIndex.", ".$countView, [user('ID')]);  
  while ($list = $data->fetch()){
    
    $photo[] = $list;
    
  }
  
  if (empty($photo)){
    
    echo json_encode(array(
      
      'result' => 'finish'
    
    ));
  
  }else{
    
    $html = null;    
    foreach ($photo AS $onePhoto){
      
      $html .= '<a href="/id'.user('ID').'?avatar_upgrade='.$onePhoto['ID'].'&'.TOKEN_URL.'" class="img-avatar-optimize"><img src="/files/upload/photos/240x240/'.$onePhoto['SHIF'].'.jpg" class="attachments-photos-img"></a>';
    
    }
    
    echo json_encode(array(
      
      'result' => 'success',
      'html' => $html
    
    ));
    
  }
  
}else{
  
  echo lg('Не удалось установить соединение');
  
}