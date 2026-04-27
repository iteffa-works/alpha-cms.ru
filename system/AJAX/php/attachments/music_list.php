<?php  
require ($_SERVER['DOCUMENT_ROOT'].'/system/connections/core.php');
access('users');

if (ajax() == true){

  $countView = intval(post('count_add'));
  $startIndex = intval(post('count_show'));                                 
  
  $data = db::get_string_all("SELECT `ID`,`NAME`,`EXT`,`ARTIST` FROM `MUSIC` WHERE `USER_ID` = ? ORDER BY `TIME` DESC LIMIT ".$startIndex.", ".$countView, [user('ID')]);  
  while ($list = $data->fetch()){
    
    $music[] = $list;
    
  }
  
  if (empty($music)){
    
    echo json_encode(array(
      
      'result' => 'finish'
    
    ));
  
  }else{
    
    $html = null;    
    foreach ($music AS $oneMusic){
      
      if (config('MUSIC_SCREEN') == 1){
        
        if (is_file(ROOT.'/files/upload/music/screen/120x120/'.$oneMusic['ID'].'.jpg')){
          
          $img = '<img src="/music/'.$oneMusic['ID'].'/?type=screen" class="attachments-files-img">';
          
        }else{
          
          $img = file::ext($oneMusic['EXT']);

        }

      }else{
        
        $img = file::ext($oneMusic['EXT']);
        
      }
      
      $html .= '<label onclick=\'checkbox('.$oneMusic['ID'].')\' class="checkbox-optimize2"><div class="list-menu"> 
      <span class="checkbox'.$oneMusic['ID'].' checkbox-op-file check-close">'.icons('check', 20).'</span> 
      <input type="checkbox" class="attachments-photos-checkbox" name="music" value="'.$oneMusic['ID'].'" id="chset'.$oneMusic['ID'].'"> 
      '.$img.' <span class="attachments-files-name">'.crop_text(tabs($oneMusic['ARTIST'].' - '.$oneMusic['NAME']), 0, 28).'</span> 
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