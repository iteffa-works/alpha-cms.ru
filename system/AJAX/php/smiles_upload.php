<?php  
require ($_SERVER['DOCUMENT_ROOT'].'/system/connections/core.php');
access('management');

if (ajax() == true){
  
  $dir = db::get_string("SELECT `ID` FROM `SMILES_DIR` WHERE `ID` = ? LIMIT 1", [intval(get('id_dir'))]);
  $url = tabs(get('url'));
  
  if (isset($dir['ID'])){
    
    ?>
    <div class="modal_bottom_title2">
    <?=lg('Загрузить новые смайлы')?>
    </div>
    
    <div class="modal-bottom-container">      
    <?=file::upload('/files/receiver/smiles.php?id_dir='.$dir['ID'].'&url='.$url)?>
    </div>
    
    <div class="modal_bottom_foot">
    <button onclick='modal_bottom_close()' class='modal-bottom-button2'><?=lg('Отменить')?></button>
    </div>
    <?
      
  }else{
    
    echo lg('Неизвестная ошибка');
    
  }
  
}else{
  
  echo lg('Не удалось установить соединение');
  
}