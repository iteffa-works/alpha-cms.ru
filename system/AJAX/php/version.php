<?php  
require ($_SERVER['DOCUMENT_ROOT'].'/system/connections/core.php');
require_once (ROOT.'/system/connections/version.php');

if (ajax() == true){
  
  ?>
  <div class="modal_bottom_title2">
  <?=lg('Версия')?> <button onclick="modal_center('version', 'close')"><?=icons('times', 20)?></button>
  </div>
    
  <div class="modal-container">
      
  <?php
  $data = db::get_string_all("SELECT `DIR`,`NAME` FROM `PANEL_THEMES` WHERE `ACT` >= '1' ORDER BY `ID` DESC");
  while ($list = $data->fetch()){
    
    ?>
    <a href='/version/?name=<?=tabs($list['DIR'])?>' ajax='no'>
    <div class='list-menu hover'>
    <?=tabs($list['NAME'])?> <?php if ($list['DIR'] == VERSION){ ?><font color='green'><?=icons('check', 15, 'fa-fw')?></font><? } ?>
    </div>
    </a>
    <?
      
  }
  
  ?></div><?
  
}else{
  
  echo lg('Не удалось установить соединение');
  
}