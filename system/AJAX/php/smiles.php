<?php  
require ($_SERVER['DOCUMENT_ROOT'].'/system/connections/core.php');
access('users');

if (ajax() == true) {
  
  $dir = db::get_string("SELECT * FROM `SMILES_DIR` WHERE `ID` = ? LIMIT 1", [intval(get('id'))]);
  
  if (isset($dir['ID'])) {
    
    ?>
    <div class='smiles_info'>
    <?=tabs($dir['NAME'])?>
    </div>      
    <div class='smiles_list'>
    <?php $data = db::get_string_all("SELECT * FROM `SMILES` WHERE `ID_DIR` = ? AND `ACT` = '1' ORDER BY `ID` ASC", [$dir['ID']]); ?>
    <?php while ($list = $data->fetch()) : ?>
    <span class='bbs smile' style='cursor: pointer' onclick="smile_save('<?=$list['ID']?>')" alt="<?=tabs($list['NAME'])?>"><img src="/files/upload/smiles/<?=$list['ID']?>.<?=$list['EXT']?>"></span>
    <?php endwhile ?>
    </div>
    <?
      
  }else{
    
    ?>
    <div class='smiles_info'>
    <?=lg('Часто используемые')?>
    </div>      
    <div class='smiles_list'>
    <?php $s = 0; ?>
    <?php $data = db::get_string_all("SELECT * FROM `SMILES_SAVE` WHERE `USER_ID` = ? ORDER BY `CLICK` DESC LIMIT 20", [user('ID')]); ?>
    <?php while ($list = $data->fetch()) : ?>
    <?php $smile = db::get_string("SELECT * FROM `SMILES` WHERE `ID` = ? AND `ACT` = '1' LIMIT 1", [$list['SMILE_ID']]); ?>
    <?php if (isset($smile['ID'])) : ?>
    <?php $s = 1; ?>
    <span class='bbs smile' style='cursor: pointer' onclick="smile_save('<?=$smile['ID']?>')" alt="<?=tabs($smile['NAME'])?>"><img src="/files/upload/smiles/<?=$smile['ID']?>.<?=$smile['EXT']?>"></span>
    <?php endif ?>
    <?php endwhile ?>
    <?php if ($s == 0) : ?>
    <center><?=lg('В истории пока нет часто используемых смайлов')?></center>
    <?php endif ?>
    </div>
    <?
    
  }
  
}else{
  
  echo lg('Не удалось установить соединение');
  
}