<?php
  
/*
-------------
Вывод медалей
-------------
*/
  
$data = db::get_string_all("SELECT * FROM `RATING_MEDAL`");
while ($list = $data->fetch()) {
  
  if ($account['RATING'] >= $list['FROM'] && $account['RATING'] <= $list['BEFORE']) {
    
    $upd2 .= " <img src='/files/upload/medal/".$list['ID'].".".$list['EXT']."' style='max-width: 40px'>";
    
  }
  
}