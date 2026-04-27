<?php
  
if (config('PRIVATE_FILES') == 1) {
  
  $count = db::get_column("SELECT COUNT(DISTINCT `FILES`.`ID`) AS `count_files` FROM `FILES` LEFT JOIN `FILES_DIR` ON (`FILES_DIR`.`ID` = `FILES`.`ID_DIR` OR `FILES_DIR`.`ID_DIR` = `FILES`.`ID_DIR`) WHERE `FILES_DIR`.`PRIVATE` != '3' AND `FILES`.`USER_ID` = ?", [user('ID')]);
  
  ?>
  <a class='menu-container_item' href='/m/files/users/?id=<?=user('ID')?>'><?=b_icons('file', $count, 30, '#5C6D74', '#9EC2D2')?><span><?=lg('Файлы')?></span></a>
  <?
  
}