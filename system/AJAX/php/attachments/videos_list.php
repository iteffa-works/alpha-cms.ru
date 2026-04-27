<?php  
require ($_SERVER['DOCUMENT_ROOT'].'/system/connections/core.php');
access('users');

if (ajax() == true){

  $countView = intval(post('count_add'));
  $startIndex = intval(post('count_show'));                                 
  
  $data = db::get_string_all("SELECT `ID`,`NAME` FROM `VIDEOS` WHERE `USER_ID` = ? ORDER BY `TIME` DESC LIMIT ".$startIndex.", ".$countView, [user('ID')]);  
  while ($list = $data->fetch()){
    
    $video[] = $list;
    
  }
  
  if (empty($video)){
    
    echo json_encode(array(
      
      'result' => 'finish'
    
    ));
  
  }else{
    
    $html = null;    
    foreach ($video AS $oneVideo){
      
      if (config('VIDEO_SCREEN') == 1){
        
        $img = '/video/'.$oneVideo['ID'].'/?type=screen';

      }else{
        
        $img = '/video/'.$oneVideo['ID'].'/?type=no_screen';
        
      }
      
      $html .= '<label onclick=\'checkbox('.$oneVideo['ID'].')\' class="checkbox-optimize2"><div class="list-menu"> 
      <span class="checkbox'.$oneVideo['ID'].' checkbox-op-file check-close">'.icons('check', 20).'</span> 
      <input type="checkbox" class="attachments-photos-checkbox" name="videos" value="'.$oneVideo['ID'].'" id="chset'.$oneVideo['ID'].'"> 
      <img src="'.$img.'" class="attachments-files-img"> <span class="attachments-files-name">'.crop_text(tabs($oneVideo['NAME']), 0, 28).'</span> 
      </div></label>';
    
    }
    
    echo json_encode(array(
      
      'result' => 'success',
      'html' => $html
    
    ));
    
  }
  
}else{
  
  echo lg('Не удалось установить соединение');
  
}