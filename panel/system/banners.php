<?php
acms_header('Баннеры', 'management');
  
?>
<div class='navigation'>
<a href='/admin/desktop/'><?=icons('home', 25)?></a>
<?=icons('angle-right', 25, 'fa-fw')?>
<a href='/admin/system/'><?=lg('Настройки системы')?></a>
<?=icons('angle-right', 25, 'fa-fw')?>
<?=lg('Баннеры')?>
</div>  
<?

/*
-------------------------
Включить/отключить баннер
-------------------------
*/

if (get('on_banner')){
  
  $on_banner = db::get_string("SELECT * FROM `BANNERS` WHERE `ID` = ? AND `ACT` = '0' LIMIT 1", [intval(get('on_banner'))]);
  
  if (isset($on_banner['ID'])){
    
    db::get_set("UPDATE `BANNERS` SET `ACT` = '1' WHERE `ID` = ? AND `ACT` = '0' LIMIT 1", [$on_banner['ID']]);
    
    success('Баннер успешно включен');
    redirect('/admin/system/banners/');
    
  }
  
}

if (get('off_banner')){
  
  $off_banner = db::get_string("SELECT * FROM `BANNERS` WHERE `ID` = ? AND `ACT` = '1' LIMIT 1", [intval(get('off_banner'))]);
  
  if (isset($off_banner['ID'])){
    
    db::get_set("UPDATE `BANNERS` SET `ACT` = '0' WHERE `ID` = ? AND `ACT` = '1' LIMIT 1", [$off_banner['ID']]);
    
    success('Баннер успешно отключен');
    redirect('/admin/system/banners/');
    
  }
  
}

/*
--------------
Удалить баннер
--------------
*/

if (get('delete_banner')){
  
  $delete = db::get_string("SELECT * FROM `BANNERS` WHERE `ID` = ? LIMIT 1", [intval(get('delete_banner'))]);
  
  if (isset($delete['ID'])){
    
    if (get('get') == 'delete_ok'){
      
      get_check_valid();
      
      db::get_set("DELETE FROM `BANNERS` WHERE `ID` = ? LIMIT 1", [$delete['ID']]);
      
      success('Удаление прошло успешно');
      redirect('/admin/system/banners/');
    
    }
    
    ?>
    <div class='list'>
    <?=lg('Вы действительно хотите удалить данный баннер? Отменить действие будет невозможно.')?>
    <br /><br />
    <a href='/admin/system/banners/?delete_banner=<?=$delete['ID']?>&get=delete_ok&<?=TOKEN_URL?>' class='button2'><?=lg('Удалить')?></a>
    </div>
    <?
      
    back('/admin/system/banners/');
    acms_footer();
    
  }
  
}

/*
--------------------
Редактировать баннер
--------------------
*/

if (get('edit_banner')){
  
  $edit = db::get_string("SELECT * FROM `BANNERS` WHERE `ID` = ? LIMIT 1", [intval(get('edit_banner'))]);
  
  if (isset($edit['ID'])){
    
    if (post('ok_edit_banner')){
      
      $code = post('code', 1);
      $type = intval(post('type'));
      
      if (str($code) < 1){
        
        error('Код не может быть меньше 1 символа');
        redirect('/admin/system/banners/?edit_banner='.$edit['ID']);
      
      }
      
      if (str($code) > 1500){
        
        error('Код не может быть больше 1500 символов');
        redirect('/admin/system/banners/?edit_banner='.$edit['ID']);
      
      }
      
      db::get_set("UPDATE `BANNERS` SET `CODE` = ?, `TYPE` = ? WHERE `ID` = ? LIMIT 1", [$code, $type, $edit['ID']]);
      
      success('Баннер успешно отредактирован');
      redirect('/admin/system/banners/');
      
    }
    
  }
  
  ?>
  <div class='list-body6'>
  <div class='list-menu'>
  <form method='post' class='ajax-form' action='/admin/system/banners/?edit_banner=<?=$edit['ID']?>'>
  <?=html::textarea(tabs($edit['CODE']), 'code', null, 'Введите код баннера', 'form-control-textarea', 5, 0)?><br /><br />
  <?=html::select('type', array(
  1 => ['Низ (главная страница)', ($edit['TYPE'] == 1 ? "selected" : null)], 
  2 => ['Низ (остальные страницы)', ($edit['TYPE'] == 2 ? "selected" : null)]
  ), 'Позиция', 'form-control-100-modify-select', 'arrows')?> 
  <?=html::button('button ajax-button', 'ok_edit_banner', 'save', 'Сохранить изменения')?>
  </form>
  </div>
  </div>
  <br />
  <?
  
  back('/admin/system/banners/');
  acms_footer();
  
}

/*
---------------
Добавить баннер
---------------
*/

if (get('get') == 'add_banner'){
  
  if (post('ok_add_banner')){
    
    $code = post('code', 1);
    $type = intval(post('type'));
    
    if (str($code) < 1){
      
      error('Код не может быть меньше 1 символа');
      redirect('/admin/system/banners/?get=add_banner');
    
    }
    
    if (str($code) > 1500){
      
      error('Код не может быть больше 1500 символов');
      redirect('/admin/system/banners/?get=add_banner');
    
    }
    
    db::get_add("INSERT INTO `BANNERS` (`CODE`, `TYPE`) VALUES (?, ?)", [$code, $type]);
    
    success('Баннер успешно создан');
    redirect('/admin/system/banners/');
  
  }
  
  ?>
  <div class='list-body6'>
  <div class='list-menu'>
  <form method='post' class='ajax-form' action='/admin/system/banners/?get=add_banner'>
  <?=html::textarea(null, 'code', null, 'Введите код баннера', 'form-control-textarea', 8, 0)?><br /><br />
  <?=html::select('type', array(
  1 => ['Низ (главная страница)', 1], 
  2 => ['Низ (остальные страницы)', 2]
  ), 'Позиция', 'form-control-100-modify-select', 'arrows')?>  
  <?=html::button('button ajax-button', 'ok_add_banner', 'save', 'Сохранить изменения')?>
  </form>
  </div>
  </div>
  <br />
  <?
    
  back('/admin/system/banners/');
  acms_footer();
  
}

/*
---------------
Список баннеров
---------------
*/

$count = db::get_column("SELECT COUNT(*) FROM `BANNERS`");

?>
<div class='list-body'>
<div class='list-menu list-title'><b><?=lg('Список баннеров')?> <span class='count'><?=$count?></span></b></div>

<div class='list-menu'>
<a href='/admin/system/banners/?get=add_banner' class='button'><?=icons('plus', 17, 'fa-fw')?> <?=lg('Создать')?></a>
</div>
<?

$data = db::get_string_all("SELECT * FROM `BANNERS` ORDER BY `ID` DESC");
while ($list = $data->fetch()){
  
  ?>
  <div class='list-menu'>
  <?=tabs($list['CODE'])?><br /><br />
  <a class='button' href='/admin/system/banners/?edit_banner=<?=$list['ID']?>'><?=icons('pencil', 15, 'fa-fw')?> <?=lg('Редактировать')?></a>
  <a class='button2' href='/admin/system/banners/?delete_banner=<?=$list['ID']?>'><?=icons('trash', 15, 'fa-fw')?> <?=lg('Удалить')?></a>
  <?
  
  if ($list['ACT'] == 1){
    
    ?>
    <a class='button2' href='/admin/system/banners/?off_banner=<?=$list['ID']?>&<?=TOKEN_URL?>'><?=icons('minus', 15, 'fa-fw')?> <?=lg('Отключить')?></a>
    <?
    
  }else{
    
    ?>    
    <a class='button3' href='/admin/system/banners/?on_banner=<?=$list['ID']?>&<?=TOKEN_URL?>'><?=icons('plus', 15, 'fa-fw')?> <?=lg('Включить')?></a>
    <?
    
  }
    
  ?></div><?
  
}

?>
<div class='list-menu'>
<?=lg('Настоятельно рекомендуется проверять код баннера и брать их у проверенных сервисов.')?>
</div>
</div> 
<?

back('/admin/system/');
acms_footer();