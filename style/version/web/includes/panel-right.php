<!-- Правая панель --!>
  
<div class="right">
  
<div class="right-container">  

<div class='list-menu right-list-title'>
<?=lg('Пользователи онлайн')?>
</div>
  
<?php
$rur = 0;
$data = db::get_string_all("SELECT `ID`,`DATE_VISIT` FROM `USERS` WHERE `DATE_VISIT` > ? ORDER BY `DATE_VISIT` DESC LIMIT 3", [(TM - config('ONLINE_TIME_USERS'))]);
while ($list = $data->fetch()) {
  
  $dop = '<br /><span class="time">'.ftime($list['DATE_VISIT']).'</span>';
  $rur = 1;
  $list['USER_ID'] = $list['ID'];
  require (ROOT.'/modules/users/plugins/list-mini.php');
  echo $list_mini;
  
}

if ($rur == 0) {
  
  ?><div class='list-menu right-list-empty'><?
  html::empty('Пока пусто');
  ?></div><?
  
}
?>

<a href='/m/users/?get=online'> 
<div class='list-menu hover right-list-a'>
<b><?=lg('Все')?></b> <span style='float: right;'><?=icons('chevron-right', 15)?></span>
</div>
</a>  
  
</div>
  
<?php
if (config('PRIVATE_BLOGS') == 1) {
  
  ?>
  <div class="right-container">
  <div class='list-menu right-list-title'>
  <?=lg('Интересные блоги')?>
  </div>
  <?
    
  $rbr = 0;  
  $data = db::get_string_all("SELECT `ID`,`NAME`,`PRIVATE`,`ID_CATEGORY`,`COMMUNITY`,`TIME`,`USER_ID` FROM `BLOGS` WHERE `PRIVATE` = '0' AND `SHARE` = '0'ORDER BY `TIME` DESC LIMIT 3");
  while ($list = $data->fetch()) {
    
    $rbr = 1;
    require (ROOT.'/modules/blogs/plugins/list_mini.php');
    echo $blogs_list_mini;
  
  }
  
  if ($rbr == 0) {
    
    ?><div class='list-menu right-list-empty'><?
    html::empty('Пока пусто');
    ?></div><?
    
  }

  ?> 
  <a href='/m/blogs/?get=new'> 
  <div class='list-menu hover right-list-a'>
  <b><?=lg('Все')?></b> <span style='float: right;'><?=icons('chevron-right', 15)?></span>
  </div>
  </a>  
  </div> 
  <?
  
}
 
?>
</div>