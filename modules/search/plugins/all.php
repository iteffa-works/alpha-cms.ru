<?php
  
require (ROOT.'/modules/search/plugins/form/main.php');

if (str(SEARCH) > 0){
  
  ?>
  <div class='list-body'>
  <div class='list-menu'> 
  <?=lg('Результаты поиска по запросу %s', '"<b>'.SEARCH.'</b>"')?>:
  </div>
  <?=direct::components(ROOT.'/modules/search/components/', 0)?> 
  </div>        
  <?
  
}else{
  
  html::empty('Нет результатов', 'search');
  
}