<?php
html::title('Центр обновлений');
acms_header();
access('management');

?>
<div class='navigation'>
<a href='/admin/desktop/'><?=icons('home', 25)?></a>
<?=icons('angle-right', 25, 'fa-fw')?>
<?=lg('Центр обновлений')?>
</div>

<div class='list-body6'>  

<div class='list-menu list-title'>  
<?=lg('Доступные обновления')?>
</div> 
  
<?=html::empty('Нет доступных обновлений')?>  
</div>
  
<div class='list-body6'>
  
<div class='list-menu list-title'>  
<?=lg('Установленные обновления')?>
</div>
  
<?php
$count = db::get_column("SELECT COUNT(*) FROM `PANEL_ALPHA_INSTALL` WHERE `UPDATE` = '1'");
if ($count == 0){ 
  
  html::empty('Нет установленных обновлений');

}else{
  
  ?><div class='alpha-installer-container'><?
  
}

$data = db::get_string_all("SELECT * FROM `PANEL_ALPHA_INSTALL` WHERE `UPDATE` = '1' ORDER BY `TIME` DESC");
while ($list = $data->fetch()){
  
  ?>    
  <div class='list-menu'>      
  <div class='alpha-installer-icons'>
  <?=file::ext('acms')?>
  </div>  
  <div class='alpha-installer-info'>
  <div><b><?=tabs($list['NAME'])?></b></div>
  <?=icons('clock-o', 15, 'fa-fw')?>
  <?=ftime($list['TIME'])?>
  </div>   
  </div>
  <?
  
}

if ($count > 0){ 
  
  ?></div><?

}

?></div><?

back('/admin/desktop/');
acms_footer();