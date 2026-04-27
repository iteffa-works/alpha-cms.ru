<?php

if (get('details')){
  
  $archive = db::get_string("SELECT * FROM `PANEL_ALPHA_INSTALL` WHERE `ACT` = '1' AND `ID` = ? LIMIT 1", [intval(get('details'))]);
  
  if (!isset($archive['ID'])){
    
    error('Неверная директива');
    redirect('/admin/system/alpha_installer/');
  
  }
  
  ?>
  <div class='list-body6'>
  <div class='list-menu list-title'><?=lg('Установленные файлы компонента')?></div>      
  <div class='list-menu'>      
  <div class='alpha-installer-icons'>
  <?=file::ext('acms')?>
  </div>  
  <div class='alpha-installer-info'>
  <div><b><?=tabs($archive['NAME'])?></b></div>
  <?=icons('clock-o', 15, 'fa-fw')?>
  <?=ftime($archive['TIME'])?>
  </div>
  </div>
    
  <div class='list-menu'>  
  <div class='alpha-installer-container' style='height: 0px; hpadding: 10px; min-height: 200px; max-height: 400px; color: #1A7198;'>
  <?php
  $s = 0;  
  $data = db::get_string_all("SELECT `DIR` FROM `PANEL_ALPHA_INSTALL_DATA` WHERE `ID_AI` = ?", [$archive['ID']]);  
  while ($list = $data->fetch()){
    
    $s = 1;
    
    ?>
    /<?=tabs($list['DIR'])?><br />
    <?
  
  }
  
  if ($s == 0){
    
    echo lg('Нет установленных файлов');
    
  }
  ?>
  </div>
  </div>    
  </div><br />
  <?
  
  back('/admin/system/alpha_installer/');
  acms_footer();
  
}