<?php  
require ($_SERVER['DOCUMENT_ROOT'].'/system/connections/core.php');
access('users');
$id = intval(get('id'));

if (session('smile_save_limit') < TM) { session('smile_save_limit', (TM + 120)); }else{ exit; }

if (ajax() == true && $id > 0) {
  
  if (db::get_column("SELECT COUNT(*) FROM `SMILES` WHERE `ID` = ? AND `ACT` = ? LIMIT 1", [$id, 1]) > 0) {
    
    if (db::get_column("SELECT COUNT(*) FROM `SMILES_SAVE` WHERE `USER_ID` = ? AND `SMILE_ID` = ? LIMIT 1", [user('ID'), $id]) == 0) {
      
      db::get_add("INSERT INTO `SMILES_SAVE` (`USER_ID`, `SMILE_ID`, `CLICK`, `TIME`) VALUES (?, ?, ?, ?)", [user('ID'), $id, 1, TM]);
      
    }else{
      
      db::get_set("UPDATE `SMILES_SAVE` SET `CLICK` = `CLICK` + ? WHERE `USER_ID` = ? AND `SMILE_ID` = ? LIMIT 1", [1, user('ID'), $id]);
      
    }
    
  }
  
}