<?php
  
if (config('PRIVATE_FILES') == 1) {
  
  $count_files = db::get_column("SELECT COUNT(DISTINCT `FILES`.`ID`) AS `count_files` FROM `FILES` LEFT JOIN `FILES_DIR` ON (`FILES_DIR`.`ID` = `FILES`.`ID_DIR` OR `FILES_DIR`.`ID_DIR` = `FILES`.`ID_DIR`) WHERE `FILES_DIR`.`PRIVATE` != '3' AND `FILES`.`USER_ID` = ?", [$account['ID']]); 

  ?>
  <a class='menu_user' href='/m/files/users/?id=<?=$account['ID']?>'>
  <div><?=num_format($count_files, 2)?></div>
  <span><?=num_decline($count_files, ['файл', 'файла', 'файлов'], 0)?></span>
  </a>
  <?
    
}