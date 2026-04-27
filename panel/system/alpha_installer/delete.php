<?php
  
/*
-------------------------------------
Удаление не установленных компонентов
-------------------------------------
*/  
  
if (get('delete')){
  
  get_check_valid();
  
  $archive = db::get_string("SELECT * FROM `PANEL_ALPHA_INSTALL` WHERE `ID` = ? AND `ACT` = '0' LIMIT 1", [intval(get('delete'))]);
  
  if (isset($archive['ID'])){
    
    @unlink(ROOT.'/files/upload/alpha_installer/'.$archive['FACT_NAME'].'.'.$archive['EXT']);
    db::get_set("DELETE FROM `PANEL_ALPHA_INSTALL` WHERE `ID` = ? LIMIT 1", [$archive['ID']]);
    
  }
  
}