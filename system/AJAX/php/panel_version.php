<?php  
require ($_SERVER['DOCUMENT_ROOT'].'/system/connections/core.php');
access('administration_show');

if (cookie('PANEL_VERSION') == 'web' || cookie('PANEL_VERSION') == 'touch'){
  
  define('VERSION2', filter_cookie('PANEL_VERSION'));

}else{
  
  if (type_version()){
    
    define('VERSION2', 'touch');
  
  }else{
    
    define('VERSION2', 'web');
  
  }

}

if (ajax() == true){
    
    ?>
    <div class="modal_bottom_title2">
    <?=lg('Версия')?> <button onclick="modal_center('version', 'close')"><?=icons('times', 20)?></button>
    </div>
    
    <div class="modal-container">
      
    <a href='/admin/version/?version=touch' ajax='no'>
    <div class='list-menu hover'>
    Touch <?php if (VERSION2 == 'touch'){ ?><font color='green'><?=icons('check', 15, 'fa-fw')?></font><? } ?>
    </div>
    </a>
    
    <a href='/admin/version/?version=web' ajax='no'>
    <div class='list-menu hover'>
    web <?php if (VERSION2 == 'web'){ ?><font color='green'><?=icons('check', 15, 'fa-fw')?></font><? } ?>
    </div>
    </a>
      
    </div>
    <?
  
}else{
  
  echo lg('Не удалось установить соединение');
  
}