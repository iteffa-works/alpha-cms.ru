<?php  
html::title('Пользовательские права');
acms_header();
access('management');
  
?>
<div class='navigation'>
<a href='/admin/desktop/'><?=icons('home', 25)?></a>
<?=icons('angle-right', 25, 'fa-fw')?>
<a href='/admin/site/'><?=lg('Настройки сайта')?></a>
<?=icons('angle-right', 25, 'fa-fw')?>
<?=lg('Пользовательские права')?>
</div>
<?
  
/*
--------------
Настройка прав
--------------
*/
  
if (get('access_set')){
  
  $access = db::get_string("SELECT * FROM `PANEL_ACCESS_USER` WHERE `ID` = ? LIMIT 1", [intval(get('access_set'))]);
  
  if (!isset($access['ID']) && $access['ACCESS'] == 1 || $access['ACCESS'] >= 98){
    
    error('Неизвестная ошибка');
    redirect('/admin/site/access/');
    
  }
  
  if (post('ok')){
    
    db::get_set("DELETE FROM `PANEL_ACCESS_USER_LIST` WHERE `ID_ACCESS` = ?", [$access['ACCESS']]);
    
    $data = db::get_string_all("SELECT * FROM `PANEL_ACCESS_LIST`");    
    while ($list = $data->fetch()){
      
      $name = $list['NAME'];
      
      if (post($name) == 1){
        
        db::get_add("INSERT INTO `PANEL_ACCESS_USER_LIST` (`ID_ACCESS`, `ID_ACCESS_LIST`) VALUES (?, ?)", [$access['ACCESS'], $list['ID']]);    
        
      }
      
    }
    
    success('Изменения успешно приняты');
    redirect('/admin/site/access/?access_set='.$access['ID']);
    
  }
  
  $count = db::get_column("SELECT COUNT(*) FROM `PANEL_ACCESS_LIST`");
  
  ?>
  <div class='list-body'>
    
  <div class='list-menu list-title'>
  <?=lg(tabs($access['NAME']))?> - <?=lg('Права')?> <span class='count'><?=$count?></span>
  </div>

  <form method='post' class='ajax-form' action='/admin/site/access/?access_set=<?=$access['ID']?>'>
  <?
  
  $data = db::get_string_all("SELECT * FROM `PANEL_ACCESS_LIST` ORDER BY `ID` DESC");  
  while ($list = $data->fetch()){
    
    ?>
    <div class='list-menu'>      
    <?=html::checkbox(tabs($list['NAME']), '<b>'.lg(tabs($list['FACT_NAME'])).'</b>', 1, (db::get_column("SELECT COUNT(*) FROM `PANEL_ACCESS_USER_LIST` WHERE `ID_ACCESS` = ? AND `ID_ACCESS_LIST` = ? LIMIT 1", [$access['ACCESS'], $list['ID']]) == 1 ? 1 : 0))?> 
    <br /><br />
    <small><?=tabs($list['NAME'])?></small>
    </div>
    <?
  
  }
  
  ?>
  <div class='list-menu'>  
  <?=html::button('ajax-button button', 'ok', 'save', 'Сохранить изменения')?>  
  </div>
  
  </form>
  </div>
  <?
  
  back('/admin/site/access/');
  acms_footer();
  
}
  
/*
------------------
Удалить привилегию
------------------
*/
  
if (get('delete')){
  
  get_check_valid();
  
  $access = db::get_string("SELECT * FROM `PANEL_ACCESS_USER` WHERE `ID` = ? LIMIT 1", [intval(get('delete'))]);
  
  if (!isset($access['ID']) && $access['ACCESS'] == 1 || $access['ACCESS'] >= 98){
    
    error('Неизвестная ошибка');
    redirect('/admin/site/access/');
    
  }
  
  if (get('get') == "delete"){
    
    db::get_set("DELETE FROM `PANEL_ACCESS_USER_LIST` WHERE `ID_ACCESS` = ? LIMIT 1", [$access['ID']]);    
    db::get_set("DELETE FROM `PANEL_ACCESS_USER` WHERE `ID` = ? LIMIT 1", [$access['ID']]);
    
    success('Успешно', 'session');
    redirect('/admin/site/access/');
    
  }
  
  ?>
  <div class='list'>
  <?=lg('Вы действительно хотите удалить права')?> #<?=$access['ACCESS']?>?<br /><br />
  <a href='/admin/site/access/?delete=<?=$access['ID']?>&get=delete&<?=TOKEN_URL?>' class='button'><?=icons('trash', 15, 'fa-fw')?> <?=lg('Удалить')?></a>
  <a href='/admin/site/access/' class='button-o'><?=lg('Отменить')?></a>
  </div>
  <?
    
  back('/admin/site/access/');
  acms_footer();
  
}
  
/*
------------------------
Редактировать привилегию
------------------------
*/
  
if (get('edit_access')){
  
  $access = db::get_string("SELECT * FROM `PANEL_ACCESS_USER` WHERE `ID` = ? LIMIT 1", [intval(get('edit_access'))]);
  
  if (!isset($access['ID']) && $access['ACCESS'] == 1 || $access['ACCESS'] >= 98){
    
    error('Неизвестная ошибка');
    redirect('/admin/site/access/');
    
  }
  
  if (post('ok_edit_access')){
    
    valid::create(array(
      
      'NAME' => ['name', 'text', [1, 100], 'Имя'],
      'ACCESS' => ['access', 'number', [1, 99], 'Код прав доступа']
    
    ));
    
    if (ACCESS != $access['ACCESS'] && db::get_column("SELECT COUNT(`ACCESS`) FROM `PANEL_ACCESS_USER` WHERE `ACCESS` = ? LIMIT 1", [ACCESS]) == 1){
      
      error('Такие права уже есть');
      redirect('/admin/site/access/?edit='.$access['ID']);
      
    }
    
    if (NAME != $access['NAME'] && db::get_column("SELECT COUNT(`ACCESS`) FROM `PANEL_ACCESS_USER` WHERE `NAME` = ? LIMIT 1", [NAME]) == 1){

      error('Такое имя уже есть');
      redirect('/admin/site/access/?edit='.$access['ID']);
      
    }
    
    if (ACCESS > 99){
      
      error('Код прав доступа не может быть больше #99');
      redirect('/admin/site/access/?edit='.$access['ID']);
      
    }
    
    if (ACCESS < 1){
      
      error('Код прав доступа не может быть меньше #1');
      redirect('/admin/site/access/?edit='.$access['ID']);
      
    }
    
    if (ERROR_LOG == 1){
      
      redirect('/admin/site/access/?edit='.$access['ID']);
    
    }
    
    db::get_set("UPDATE `PANEL_ACCESS_USER` SET `ACCESS` = ?, `NAME` = ? WHERE `ID` = ? LIMIT 1", [ACCESS, NAME, $access['ID']]);
    
    success('Права успешно отредактированы');
    redirect('/admin/site/access/');
    
  }
  
  ?>
  <div class='list-body6'>
  <div class='list-menu list-title'>
  <?=lg('Редактировать права')?> - <?=tabs($access['NAME'])?>
  </div>
  <div class='list-menu'>
  <form method='post' class='ajax-form' action='/admin/site/access/?edit_access=<?=$access['ID']?>'>    
  <?=html::input('name', null, 'Имя:', null, tabs($access['NAME']), 'form-control-100', 'text')?>
  <?=html::input('access', 0, 'Права:', null, $access['ACCESS'], 'form-control-30', 'text', null, 'lock')?> 
  <?=html::button('ajax-button button', 'ok_edit_access', 'plus', 'Добавить')?>
  <a class='button-o' href='/admin/site/access/'><?=lg('Отмена')?></a>
  </form>
  </div>
  </div>
  <br />
  <?
    
  back('/admin/site/access/');
  acms_footer();
  
}  
  
/*
-------------------
Добавить привилегию
-------------------
*/
  
if (get('get') == "add_access"){
  
  if (post('ok_access')){
    
    valid::create(array(
      
      'NAME' => ['name', 'text', [1, 100], 'Имя'],
      'ACCESS' => ['access', 'number', [1, 99], 'Код прав доступа']
    
    ));
    
    if (db::get_column("SELECT COUNT(`ACCESS`) FROM `PANEL_ACCESS_USER` WHERE `ACCESS` = ? LIMIT 1", [ACCESS]) == 1){
      
      error('Такие права уже есть');
      redirect('/admin/site/access/?get=add_access');
      
    }
    
    if (db::get_column("SELECT COUNT(`ACCESS`) FROM `PANEL_ACCESS_USER` WHERE `NAME` = ? LIMIT 1", [NAME]) == 1){

      error('Такое имя уже есть');
      redirect('/admin/site/access/?get=add_access');
      
    }
    
    if (ACCESS > 99){
      
      error('Код прав доступа не может быть больше #99');
      redirect('/admin/site/access/?get=add_access');
      
    }
    
    if (ACCESS < 1){
      
      error('Код прав доступа не может быть меньше #1');
      redirect('/admin/site/access/?get=add_access');
      
    }
    
    if (ERROR_LOG == 1){
      
      redirect('/admin/site/access/?get=add_access');
    
    }
    
    db::get_add("INSERT INTO `PANEL_ACCESS_USER` (`NAME`, `ACCESS`) VALUES (?, ?)", [NAME, ACCESS]);
    
    success('Права успешно созданы');
    redirect('/admin/site/access/');
    
  }
  
  ?>
  <div class='list-body6'>
  <div class='list-menu list-title'>
  <?=lg('Создать новые права')?>
  </div>
  <div class='list-menu'>
  <form method='post' class='ajax-form' action='/admin/site/access/?get=add_access'>    
  <?=html::input('name', null, 'Имя:', null, null, 'form-control-100', 'text')?>
  <?=html::input('access', 0, 'Права:', null, null, 'form-control-30', 'text', null, 'lock')?> 
  <?=html::button('ajax-button button', 'ok_access', 'plus', 'Добавить')?>
  <a class='button-o' href='/admin/site/access/'><?=lg('Отмена')?></a>
  </form>
  </div>
  </div>
  <br />
  <?
    
  back('/admin/site/access/');
  acms_footer();
  
}
  
/*
-----------------
Список привилегий
-----------------
*/
  
$count = db::get_column("SELECT COUNT(*) FROM `PANEL_ACCESS_USER`");

?>
<div class='list-body'>
  
<div class='list-menu list-title'>
<?=lg('Список привилегий')?> <span class='count'><?=$count?></span>
</div>
  
<div class='list-menu'>
<a href='/admin/site/access/?get=add_access' class='button'><?=icons('plus', 15, 'fa-fw')?> <?=lg('Добавить привилегию')?></a>
</div>
<?

$data = db::get_string_all("SELECT * FROM `PANEL_ACCESS_USER` ORDER BY `ACCESS` DESC");
while ($list = $data->fetch()){
  
  ?><div class='list-menu'><?
  
  if ($list['ACCESS'] == 1 || $list['ACCESS'] >= 98){
    
    echo icons('lock', 15, 'fa-fw');
    
  }
  
  ?>
  <b><?=LG('Права')?> #<?=$list['ACCESS']?></b><br />
  <?=tabs($list['NAME'])?>
  <?
  
  if ($list['ACCESS'] > 1 && $list['ACCESS'] < 98){
    
    ?>
    <div class='button-optimize-div'></div>
    <a title="<?=lg('Редактировать')?>" href='/admin/site/access/?edit_access=<?=$list['ID']?>' class='button3 button-optimize'><?=icons('pencil', 15, 'fa-fw')?></a>
    <a title="<?=lg('Настройки прав')?>" href='/admin/site/access/?access_set=<?=$list['ID']?>' class='button3 button-optimize'><?=icons('gear', 15, 'fa-fw')?></a>
    <a title="<?=lg('Удалить')?>" href='/admin/site/access/?delete=<?=$list['ID']?>&<?=TOKEN_URL?>' class='button2 button-optimize'><?=icons('trash', 15, 'fa-fw')?></a>
    <?
    
  }
  
  ?></div><?
  
}

?></div><?

back('/admin/site/');
acms_footer();