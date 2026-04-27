<script type="text/javascript" src="/system/AJAX/jquery/jquery-3.7.0.min.js"></script> 
  
<?php

ajax_interval();
js_check();

direct::components(ROOT.'/system/connections/cfooter/data/', 0);

//Подгрузка JS компонентов из папки /system/AJAX/
$result = scandir(ROOT.'/system/AJAX/', SCANDIR_SORT_ASCENDING);
for ($i = 0; $i < count($result); $i++){
  
  if (preg_match('#\.js$#i',$result[$i])){
    
    ?><script type="text/javascript" src="/system/AJAX/<?=$result[$i]?>?v=<?=front_hash()?>"></script><?
  
  }

}