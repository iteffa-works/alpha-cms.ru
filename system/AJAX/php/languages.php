<?php  
require ($_SERVER['DOCUMENT_ROOT'].'/system/connections/core.php');

if (ajax() == true){
  
  ?>
  <div class="modal_bottom_title2">
  <?=lg('Язык')?> <button onclick="modal_center('languages', 'close')"><?=icons('times', 20)?></button>
  </div>
    
  <div class="modal-container">
    
  <a href='/languages/?lang=RU' ajax='no'>
  <div class='list-menu hover'>
  Русский (RU) <?php if (LANGUAGE == 'RU'){ ?><font color='green'><?=icons('check', 15, 'fa-fw')?></font><? } ?>
  </div>
  </a>
      
  <?php      
  $data = db::get_string_all("SELECT * FROM `LANGUAGES` WHERE `ACT` = '1'");
  while ($list = $data->fetch()){
    
    ?>
    <a href='/languages/?lang=<?=tabs($list['FACT_NAME'])?>' ajax='no'>
    <div class='list-menu hover'>
    <?=tabs($list['NAME'])?> (<?=tabs($list['FACT_NAME'])?>) <?php if (LANGUAGE == tabs($list['FACT_NAME'])){ ?><font color='green'><?=icons('check', 15, 'fa-fw')?></font><? } ?>
    </div>
    </a>  
    <?
    
  } 
  
  ?></div><?
  
}else{
  
  echo lg('Не удалось установить соединение');
  
}