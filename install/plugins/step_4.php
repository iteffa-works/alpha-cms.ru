<?php
  
function DELETE_INSTALL($dir) {
  
  $includes = GLOB($dir.'/{,.}*', GLOB_BRACE);
  $systemDots = PREG_GREP('/\.+$/', $includes);
  
  FOREACH ($systemDots AS $index => $dot) {
    
    UNSET($includes[$index]);
  
  }
  
  FOREACH ($includes AS $include) {
    
    IF (IS_DIR($include) && !IS_LINK($include)) {
      
      DELETE_INSTALL($include);
    
    }ELSE{
      
      UNLINK($include);
    
    }
  
  }
  
  RMDIR($dir);

}
  
ECHO "<div class='list'>"; 

if (get('get_s') == 'end'){
  
  DELETE_INSTALL(ROOT.'/install/');
  session('no_install', null);
  
  redirect('/');
  
}

ECHO "<center><font size='+1'>".lg('Шаг')." 4 ".lg('из')." 4</font><br /><br />";
ECHO "<b><font size='+1'>".lg('Завершение установки')."</font></b></center><br />";
ECHO "Для завершения установки необходимо удалить папку <b>/install/</b> на сервере. Это важно.<br /><br />";
ECHO "<a class='btn' href='/install/?get=4&get_s=end'>".icons('times', 15, 'fa-fw')." ".lg('Удалить и завершить установку')."</a><br /><br />";
ECHO "</div>";