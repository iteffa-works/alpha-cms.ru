<div class='desktop-vidget'>  
<div class='desktop-vidget-title'><?=icons('lock', 25, 'fa-fw')?> <?=lg('Доступ к панели')?></div>
  
<div class='list-menu'>
  
<?php    
$count = db::get_column("SELECT COUNT(*) FROM `USERS` WHERE `ACCESS` > '1'");

if ($count == 0){ 
  
  echo lg('никто');

}

$s = 0;
$data = db::get_string_all("SELECT * FROM `USERS` WHERE `ACCESS` > '1' ORDER BY `DATE_VISIT` DESC");
while ($list = $data->fetch()){
  
  $s++;
  
  if ($s == 1){
    
    echo "<a ajax='no' href='/id".$list['ID']."'>".$list['LOGIN']."</a>";
    
  }
  
  if ($s >= 2 && $s <= 6){
    
    echo ", <a ajax='no' href='/id".$list['ID']."'>".$list['LOGIN']."</a>";
    
  }
  
}

if ($s >= 0 && $s <= 7){
  
  echo "<br /><br />";
    
}

if ($s >= 7 && $s <= 8){

  echo "<a href='/admin/site/users/' class='button'>".icons('navicon', 15, 'fa-fw')." ".lg('Весь список')."</a> ";
    
}
?>

<?php if (access('management', null) == true){ ?>
<a href='/admin/site/users/?get=add' class='button'><?=icons('plus', 15, 'fa-fw')?> <?=lg('Добавить')?></a>
<?php } ?> 

</div>

<div class='list-menu'>
<font size='+1'><b><?=lg('Режим доступа')?></b></font>
<br /><br />
<?

if (str(config('PASSWORD')) > 0){
  
  ?>
  <font color='#FAC3BE'><?=icons('lock', 15)?> <b><?=lg('Установлен пароль')?></b></font><br /><br />
  <?
  
}

if (config('ACCESS') == 1){
  
  $access = lg('Свободный');
  
}else{
  
  $access = lg('Проверка IP');

}

?>

<b><?=lg('Доступ')?>:</b> <?=$access?><br />
<?php if (config('ACCESS') == 2){ echo "<b>".lg('Ваш текущий IP').":</b> ".IP; } ?>
  
<?php if (access('management', null) == true){ ?>
<br />  
<a href='/admin/system/security/' class='button'>
<?=icons('gear', 15, 'fa-fw')?> <?=lg('Настройки')?></a> 
<br /> 
<?php } ?>  
  
</div>

</div>