<?php
acms_header('Администрация');

?> 
<div class='menu-nav-content'>  
<a class='menu-nav h' href='/m/list_administration/'>
<?=lg('Администрация')?>
</a>    
<a class='menu-nav' href='/m/list_administration/assistants/'>
<?=lg('Онлайн помощники')?>
</a>  
</div>
<?

$column = db::get_column("SELECT COUNT(*) FROM `USERS` WHERE `ACCESS` > '1'");

if ($column == 0){ 
  
  html::empty('Пока пусто');
  
}else{
  
  ?>
  <div class='list-body'>
  <div class='list-menu'><?=lg('Список членов администрации')?>:</div>
  <? 
  
}

$data = db::get_string_all("SELECT * FROM `USERS` WHERE `ACCESS` > '1' ORDER BY `DATE_VISIT` DESC");
while ($list = $data->fetch()) {
  
  $access = db::get_string("SELECT `NAME` FROM `PANEL_ACCESS_USER` WHERE `ACCESS` = ? LIMIT 1", [$list['ACCESS']]);  
  $list['USER_ID'] = $list['ID'];
  
  $dop = '<br /><font color="green">'.lg(tabs($access['NAME'])).'</font>';
  
  require (ROOT.'/modules/users/plugins/list-mini.php');
  echo $list_mini;

}

if ($column > 0){

  ?></div><?
  
}

back('/', 'На главную');
acms_footer();