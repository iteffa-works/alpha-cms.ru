<?php
  
/*
----------------------
Вывод окна со смайлами
----------------------
*/
  
function smiles_show() {
  
  ?>          
  <div id='smiles' class='smiles-show' style='display: none;'>
  <div class='smiles-show-title'>    
  <a class='smiles-show-title-ob ssop' id='bbs_back' data-factor='-1' ajax='no' style='left: 0; cursor: pointer'>  
  <?=icons('chevron-left', 25)?>
  </a>    
  <a class='smiles-show-title-ob ssop' id='bbs_for' data-factor='1' ajax='no' style='right: 50px; cursor: pointer'>  
  <?=icons('chevron-right', 25)?>
  </a>    
  <a class='smiles-show-title-ob ssop' ajax='no' style='right: 0; cursor: pointer' onclick="open_or_close('smiles')">  
  <?=icons('times', 28)?>
  </a>    
  <div class='bbs_op smiles-show-title-ob-op'> 
  <a class='smiles-show-title-ob' style='cursor: pointer' ajax='no' onclick="smiles_up('/system/AJAX/php/smiles.php')">  
  <?=icons('clock-o', 23)?>
  </a>
  <?php $data = db::get_string_all("SELECT `ID` FROM `SMILES_DIR` ORDER BY `ID` DESC"); ?>
  <?php while ($list = $data->fetch()) : ?>
  <?php $smile = db::get_string("SELECT `ID`,`EXT` FROM `SMILES` WHERE `ID_DIR` = ? AND `ACT` = '1' ORDER BY `ID` ASC LIMIT 1", [$list['ID']]); ?>
  <?php if (isset($smile['ID'])) : ?>
  <a class='smiles-show-title-ob' style='cursor: pointer' ajax='no' onclick="smiles_up('/system/AJAX/php/smiles.php?id=<?=$list['ID']?>')">  
  <img src='/files/upload/smiles/<?=$smile['ID']?>.<?=$smile['EXT']?>'>
  </a>
  <?php else : ?>
  <a class='smiles-show-title-ob' style='cursor: pointer' ajax='no' onclick="smiles_up('/system/AJAX/php/smiles.php?id=<?=$list['ID']?>')">
  <?=icons('smile-o', 32)?>
  </a>
  <?php endif ?>
  <?php endwhile ?>  
  </div>
  </div>    
  <div id='smiles_up'></div>    
  </div>
  <?
  
}
  
/*
-------------------------------
Функция вывода смайлов в тексте
-------------------------------
*/
  
function smiles($msg, $param = 1) {
  
  $data = db::get_string_all("SELECT `ID`,`NAME`,`EXT` FROM `SMILES`");  
  while ($list = $data->fetch()) {
    
    $sm = explode("|", tabs($list['NAME']));
    
    for ($i = 0; $i < count($sm); $i++){
      
      $msg = str_replace($sm[$i], ($param == 1 ? " <img src='/files/upload/smiles/".$list['ID'].".".$list['EXT']."' style='max-width: 100px'> " : null), $msg);
    
    }
  
  }
  
  return $msg;

}