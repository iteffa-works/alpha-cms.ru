<?php  
require ($_SERVER['DOCUMENT_ROOT'].'/system/connections/core.php');
access('users');

if (ajax() == true){

  $countView = intval(post('count_add'));
  $startIndex = intval(post('count_show'));                                 
  
  $data = db::get_string_all("SELECT `ID`,`NAME`,`EXT` FROM `FILES` WHERE `USER_ID` = ? ORDER BY `TIME` DESC LIMIT ".$startIndex.", ".$countView, [user('ID')]);  
  while ($list = $data->fetch()){
    
    $files[] = $list;
    
  }
  
  if (empty($files)){
    
    echo json_encode(array(
      
      'result' => 'finish'
    
    ));
  
  }else{
    
    $html = null;    
    foreach ($files AS $oneFiles){
      
      $html .= '<label onclick=\'checkbox('.$oneFiles['ID'].')\' class="checkbox-optimize2"><div class="list-menu"> 
      <span class="checkbox'.$oneFiles['ID'].' checkbox-op-file check-close">'.icons('check', 20).'</span> 
      <input type="checkbox" class="attachments-photos-checkbox" name="files" value="'.$oneFiles['ID'].'" id="chset'.$oneFiles['ID'].'"> 
      '.file::ext($oneFiles['EXT']).' <span class="attachments-files-name">'.crop_text(tabs($oneFiles['NAME']), 0, 28).'</span> 
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