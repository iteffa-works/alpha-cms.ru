<?php  
acms_header('Темы оформления', 'management');
  
?>
<div class='navigation'>
<a href='/admin/desktop/'><?=icons('home', 25)?></a>
<?=icons('angle-right', 25, 'fa-fw')?>
<a href='/admin/site/'><?=lg('Настройки сайта')?></a>
<?=icons('angle-right', 25, 'fa-fw')?>
<?=lg('Темы оформления')?>
</div>
<?
  
/*
-------------------------
Сделать тему приоритетной
-------------------------
*/

if (get('get') == "prioritet_touch"){
  
  get_check_valid();
  
  $theme = db::get_string("SELECT `ID`,`ACT` FROM `PANEL_THEMES` WHERE `ID` = ? LIMIT 1", [intval(get('act'))]);
  
  if (!isset($theme['ID'])){
    
    error('Такой темы не существует');
    redirect('/admin/site/themes/');
    
  }
  
  db::get_set("UPDATE `PANEL_THEMES` SET `PRIORITET_TOUCH` = '0' WHERE `PRIORITET_TOUCH` = '1'");
  db::get_set("UPDATE `PANEL_THEMES` SET `PRIORITET_TOUCH` = '1' WHERE `ID` = ? LIMIT 1", [$theme['ID']]);
  
  success('Изменения успешно приняты');
  redirect('/admin/site/themes/');
  
}

if (get('get') == "prioritet_web"){
  
  get_check_valid();
  
  $theme = db::get_string("SELECT `ID`,`ACT` FROM `PANEL_THEMES` WHERE `ID` = ? LIMIT 1", [intval(get('act'))]);
  
  if (!isset($theme['ID'])){
    
    error('Такой темы не существует');
    redirect('/admin/site/themes/');
    
  }
  
  db::get_set("UPDATE `PANEL_THEMES` SET `PRIORITET_WEB` = '0' WHERE `PRIORITET_WEB` = '1'");
  db::get_set("UPDATE `PANEL_THEMES` SET `PRIORITET_WEB` = '1' WHERE `ID` = ? LIMIT 1", [$theme['ID']]);
  
  success('Изменения успешно приняты');
  redirect('/admin/site/themes/');
  
}

/*
------------------------
Включение/отключение тем
------------------------
*/

if (get('get') == "off"){
  
  get_check_valid();
  
  $theme = db::get_string("SELECT `ID`,`ACT` FROM `PANEL_THEMES` WHERE `ID` = ? LIMIT 1", [intval(get('act'))]);
  
  if (!isset($theme['ID'])){
    
    error('Такой темы не существует');
    redirect('/admin/site/themes/');
    
  }
  
  if ($theme['PRIORITET_WEB'] == 1 || $theme['PRIORITET_TOUCH'] == 1){
    
    error('Неизвестная ошибка');
    redirect('/admin/site/themes/');
    
  }
  
  db::get_set("UPDATE `PANEL_THEMES` SET `ACT` = '0' WHERE `ID` = ? LIMIT 1", [$theme['ID']]);
  
  success('Тема больше не активна');
  redirect('/admin/site/themes/');
  
}

if (get('get') == "on"){
  
  get_check_valid();
  
  $theme = db::get_string("SELECT `ID`,`ACT` FROM `PANEL_THEMES` WHERE `ID` = ? LIMIT 1", [intval(get('act'))]);
  
  if (!isset($theme['ID'])){
    
    error('Такой темы не существует');
    redirect('/admin/site/themes/');
    
  }
  
  if ($theme['ACT'] == 2){
    
    error('Неизвестная ошибка');
    redirect('/admin/site/themes/');
    
  }
  
  db::get_set("UPDATE `PANEL_THEMES` SET `ACT` = '1' WHERE `ID` = ? LIMIT 1", [$theme['ID']]);
  
  success('Тема теперь активна');
  redirect('/admin/site/themes/');
  
}

/*
------------
Редактор тем
------------
*/

if (get('them_edit')){
  
  $them = db::get_string("SELECT * FROM `PANEL_THEMES` WHERE `ID` = ? LIMIT 1", [intval(get('them_edit'))]);
  
  if (!isset($them['ID'])){
    
    error('Такой темы не существует');
    redirect('/admin/site/themes/');
    
  }
  
  ?>
  <div class='list-body6'>
  <div class='list-menu'>  
  <div style='display: inline-block; vertical-align: top; width: 70px;'>
  <?=file::ext('themes')?>
  </div>  
  <div style='display: inline-block; vertical-align: top; width: 70%; margin-top: 3px;'>
  <?=($them['ACT'] == 0 ? icons('power-off', 15, 'fa-fw').' ' : null)?>
  <?=($them['ACT'] == 2 ? icons('lock', 15, 'fa-fw').' ' : null)?>  
  <b><?=tabs($them['NAME'])?></b><small><br />
  <p>/style/version/<?=tabs($them['DIR'])?>/</p>
  </small>
  </div>
  </div>
  <?
  
  //Редактирование
  if (post('ok_edit_them')){
    
    $size_logo = intval(post('size_logo'));
    $name = esc(post('name'));
    
    if ($size_logo < 20) {
      
      error('Размер логотипа не может быть меньше 20');
      redirect('/admin/site/themes/?them_edit='.$them['ID']);
    
    }
    
    if ($size_logo > 250) {
      
      error('Размер логотипа не может быть больше 250');
      redirect('/admin/site/themes/?them_edit='.$them['ID']);
    
    }
    
    if ($them['NAME'] != $name && $them['ACT'] == 2){
      
      error('Имя для этой темы не может быть изменено', 'session');
      redirect('/admin/site/themes/?them_edit='.$them['ID']);
      
    }
    
    if (str($name) < 1){
      
      error('Имя не может быть меньше 1 символа');
      redirect('/admin/site/themes/?them_edit='.$them['ID']);
      
    }
    
    if (str($name) > 30){
      
      error('Имя не может быть больше 30 символов');
      redirect('/admin/site/themes/?them_edit='.$them['ID']);
      
    }
    
    db::get_set("UPDATE `PANEL_THEMES` SET `NAME` = ?, `LOGO_MAX` = ? WHERE `ID` = ? LIMIT 1", [$name, $size_logo, $them['ID']]);
    
    success('Изменения успешно приняты');
    redirect('/admin/site/themes/?them_edit='.$them['ID']);
    
  }
  
  ?>
  <div class='list-menu'>
  <form method='post' class='ajax-form' action='/admin/site/themes/?them_edit=<?=$them['ID']?>'>  
  <?=html::input('name', null, 'Имя:', null, tabs($them['NAME']), 'form-control-100', 'text')?>
  <b><?=lg('Логотип')?>:</b><br />
  <div id='logo' style='margin: 15px; background: black; padding: 10px;'><img src='/style/version/<?=$them['DIR']?>/logo/<?=$them['LOGO']?>' style='max-width: <?=$them['LOGO_MAX']?>px;'></div>
  <?=attachments_result()?>
  <a ajax="no" id="modal_bottom_open_set" onclick="upload('/system/AJAX/php/them_logo.php?id=<?=$them['ID']?>', 'attachments_upload')" class="button3"><?=icons('upload', 15, 'fa-fw')?> <?=lg('Загрузить новый логотип')?></a>
  <br /><br />    
  <div id='logo_max'>
  <?=html::input('size_logo', null, 'Размер логотипа:', null, $them['LOGO_MAX'], 'form-control-30', 'text')?>
  </div>
  <?=html::button('button ajax-button', 'ok_edit_them', 'save', 'Сохранить изменения')?>
  </form>
  </div> 
  </div> 
  <br />
  <?
    
  back('/admin/site/themes/');
  acms_footer();
  
}

/*
-------
Favicon
-------
*/

if (get('them_favicon')){
  
  $them = db::get_string("SELECT * FROM `PANEL_THEMES` WHERE `ID` = ? LIMIT 1", [intval(get('them_favicon'))]);
  
  if (!isset($them['ID'])){
    
    error('Такой темы не существует');
    redirect('/admin/site/themes/');
    
  }
  
  ?>
  <div class='list-body'>
  <div class='list-menu'>
  <b><?=lg('Иконка заголовка страницы')?> (favicon):</b><br />
  <div id='favicon' style='margin: 15px;'><img src='/style/version/<?=$them['DIR']?>/favicon/<?=$them['FAVICON']?>'></div>
  <?=attachments_result()?>
  <a ajax="no" id="modal_bottom_open_set" onclick="upload('/system/AJAX/php/them_ico.php?id=<?=$them['ID']?>', 'attachments_upload')" class="button3"><?=icons('upload', 15, 'fa-fw')?> <?=lg('Загрузить новую иконку')?></a>  
  <br />* <?=lg('Иконка должна быть в формате .ico')?><br />
  </div>
  </div>
  <br />
  <?
  
  back('/admin/site/themes/');
  acms_footer();  
  
}

/*
------------------------
Список установленных тем
------------------------
*/

$count = db::get_column("SELECT COUNT(*) FROM `PANEL_THEMES`");

?>
<div class='list'>
<?=lg('Вы можете устанавливать или удалять темы через')?> <a href='/admin/system/alpha_installer/'><?=LG('Alpha установщик')?></a>.
</div>
<div class='list-body'>
<div class='list-menu list-title'><b><?=lg('Установленные темы')?> <span class='count'><?=$count?></span></b></div>
<?

if ($count == 0){ 
  
  html::empty();
  
}

$data = db::get_string_all("SELECT * FROM `PANEL_THEMES` ORDER BY `ID` DESC");
while ($list = $data->FETCH()){
  
  ?>
  <div class='list-menu'>
  <div style='display: inline-block; vertical-align: top; width: 70px;'>
  <?=file::ext('themes')?>
  </div>  
  <div style='display: inline-block; vertical-align: top; width: 70%; margin-top: 3px;'>
  <?=($list['ACT'] == 0 ? icons('power-off', 15, 'fa-fw').' ' : null)?>  
  <b><?=tabs($list['NAME'])?></b><small><br />
  <?=($list['PRIORITET_TOUCH'] == 1 ? '<font color="#3FC6A2">'.icons('check', 12, 'fa-fw').' '.lg('Тема приоритетна для Touch').'</font><br />' : null)?>
  <?=($list['PRIORITET_WEB'] == 1 ? '<font color="#3FC6A2">'.icons('check', 12, 'fa-fw').' '.lg('Тема приоритетна для WEB').'</font><br />' : null)?>
  <p>/style/version/<?=tabs($list['DIR'])?>/</p>
  </small>
  </div> 
  <br /><br />
  <a href='/admin/site/themes/?them_edit=<?=$list['ID']?>' class='button'><?=icons('gear', 15, 'fa-fw')?> <?=lg('Редактировать')?></a>
  <a href='/admin/site/themes/?them_favicon=<?=$list['ID']?>' class='button'><?=icons('image', 15, 'fa-fw')?> <?=lg('Favicon')?></a>
  <?
    
  if ($list['PRIORITET_WEB'] == 0 && $list['PRIORITET_TOUCH'] == 0){
    
    if ($list['ACT'] == 1 || $list['ACT'] == 2){
    
      ?>
      <a href='/admin/site/themes/?act=<?=$list['ID']?>&get=off&<?=TOKEN_URL?>' class='button2'><?=icons('trash', 15, 'fa-fw')?> <?=lg('Отключить')?></a>
      <?
      
    }elseif ($list['ACT'] == 0){
      
      ?>
      <a href='/admin/site/themes/?act=<?=$list['ID']?>&get=on&<?=TOKEN_URL?>' class='button3'><?=icons('plus', 15, 'fa-fw')?> <?=lg('Включить')?></a>
      <?
        
    }
  
  }
  
  if ($list['PRIORITET_TOUCH'] == 0){

    ?>
    <a href='/admin/site/themes/?act=<?=$list['ID']?>&get=prioritet_touch&<?=TOKEN_URL?>' class='button3'><?=icons('plus', 15, 'fa-fw')?> <?=lg('Приоритет для Touch')?></a>
    <?
    
  }
  
  if ($list['PRIORITET_WEB'] == 0){
    
    ?>
    <a href='/admin/site/themes/?act=<?=$list['ID']?>&get=prioritet_web&<?=TOKEN_URL?>' class='button3'><?=icons('plus', 15, 'fa-fw')?> <?=lg('Приоритет для WEB')?></a>
    <?
  
  }
  
  ?></div><?
  
}

?></div><? 
  
acms_footer();