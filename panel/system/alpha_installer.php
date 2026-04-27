<?php  
html::title('Альфа установщик');
acms_header();
access('management'); 
?>

<div class='navigation'>
<a href='/admin/desktop/'><?=icons('home', 25)?></a>
<?=icons('angle-right', 25, 'fa-fw')?>
<a href='/admin/system/'><?=lg('Настройки системы')?></a>
<?=icons('angle-right', 25, 'fa-fw')?>
<?=lg('Альфа установщик')?>
</div>
  
<?php
//Установка компонента
require ROOT.'/panel/system/alpha_installer/install.php';  
  
//Удаление компонента
require ROOT.'/panel/system/alpha_installer/delete_components.php';

//Удаление zip архива
require ROOT.'/panel/system/alpha_installer/delete.php';

//Показ деталей
require ROOT.'/panel/system/alpha_installer/details.php';
?>
  
<div class='list-body6'>
<div class='list-menu list-title'>
<?=lg('Не установленные компоненты')?>
</div>

<div class='list-menu'>  
<?=attachments_result()?>
<a ajax="no" id="modal_bottom_open_set" onclick="upload('/system/AJAX/php/alpha_installer.php', 'attachments_upload')" class="button3"><?=icons('upload', 15, 'fa-fw')?> <?=lg('Загрузить zip архив')?></a> 
</div> 
  
<div id='upload-zip'>
  
<?php
$count = db::get_column("SELECT COUNT(*) FROM `PANEL_ALPHA_INSTALL` WHERE `ACT` = '0'");
if ($count == 0){ 
  
  html::empty('Пока ничего не загружено');

}else{
  
  ?><div class='alpha-installer-container'><?
  
}

$data = db::get_string_all("SELECT * FROM `PANEL_ALPHA_INSTALL` WHERE `ACT` = '0' ORDER BY `TIME` DESC");
while ($list = $data->fetch()){
  
  ?>
  <div class='list-menu'>      
  <div class='alpha-installer-icons'>
  <?=file::ext($list['EXT'])?>
  </div>  
  <div class='alpha-installer-info'>
  <div><b><?=tabs($list['NAME'])?></b></div>
  <?=icons('clock-o', 15, 'fa-fw')?>
  <?=ftime($list['TIME'])?>
  <span class='count'><?=size_file($list['SIZE'])?></span>
  </div><br /><br />  
  <a class='button3' href='/admin/system/alpha_installer/?id=<?=$list['ID']?>' class='panel-button'><?=icons('gear', 15, 'fa-fw')?> <?=lg('Установить')?></a>
  <button onclick="request('/admin/system/alpha_installer/?delete=<?=$list['ID']?>&<?=TOKEN_URL?>', '#upload-zip')" class='button2'><?=ICONS('trash', 15, 'fa-fw')?></button>    
  </div>
  <?
  
}

if ($count > 0){ 
  
  ?></div><?

}

?></div></div>
  
<div class='list-body6'>
<div class='list-menu list-title'>
<?=lg('Установленные компоненты')?>
</div>   
  
<?php
$count = db::get_column("SELECT COUNT(*) FROM `PANEL_ALPHA_INSTALL` WHERE `ACT` = '1'");
if ($count == 0){ 
  
  html::empty('Пока ничего не установлено');

}else{
  
  ?><div class='alpha-installer-container'><?
  
}

$data = db::get_string_all("SELECT * FROM `PANEL_ALPHA_INSTALL` WHERE `ACT` = '1' ORDER BY `TIME` DESC");
while ($list = $data->fetch()){
  
  ?>
  <div class='list-menu'>      
  <div class='alpha-installer-icons'>
  <?=file::ext('acms')?>
  </div>  
  <div class='alpha-installer-info'>
  <div>
    
  <?php
  if ($list['SYSTEM'] == 0){
    
    echo icons('lock', 16, 'fa-fw');
    
  }
  ?> 
    
  <b><?=tabs($list['NAME'])?></b></div>
  <?=icons('clock-o', 15, 'fa-fw')?>
  <?=ftime($list['TIME'])?>
  </div><br /><br />  
  <a class='button3' href='/admin/system/alpha_installer/?details=<?=$list['ID']?>' class='panel-button'><?=icons('list', 15, 'fa-fw')?> <?=lg('Детали')?></a>
    
  <?php
  if ($list['SYSTEM'] == 0){
    
    ?>
    <a class='button2' href='/admin/system/alpha_installer/?delete_components=<?=$list['ID']?>' class='panel-button'><?=icons('trash', 15, 'fa-fw')?></a>
    <?
    
  }
  ?>  
  
  </div>
  <?
  
}

if ($count > 0){ 
  
  ?></div><?

}

?></div><br /><?

back('/admin/system/');
acms_footer();