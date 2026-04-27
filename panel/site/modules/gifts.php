<?php
  
/*
-----------------
Удаление подарков
-----------------
*/

if (get('delete_gift')){

  get_check_valid();
  
  $gift = db::get_string("SELECT `ID`,`EXT` FROM `GIFTS` WHERE `ID` = ? LIMIT 1", [intval(get('delete_gift'))]);
  
  if (isset($gift['ID'])){
    
    @unlink(ROOT.'/files/upload/gifts/'.$gift['ID'].'.'.$gift['EXT']);  
    db::get_set("DELETE FROM `GIFTS` WHERE `ID` = ? LIMIT 1", [$gift['ID']]);
    
  }
  
}
  
/*
-------------------
Добавление подарков
-------------------
*/
  
if (get('add_gifts')){
  
  $dir = db::get_string("SELECT * FROM `GIFTS_DIR` WHERE `ID` = ? LIMIT 1", [intval(get('add_gifts'))]);
  
  if (!isset($dir['ID'])){
    
    error('Неизвестная ошибка');
    redirect('/admin/site/modules/?mod=gifts');
    
  }
  
  ?>
  <div class='list-body'>
    
  <div class='list-menu list-title'>
  <?=tabs($dir['NAME'])?>: <?=lg('добавление подарков')?>
  </div>
    
  <div class='list-menu'>    
  <?=attachments_result()?>
  <?php $link = '/admin/site/modules/?mod=gifts&add_gifts='.$dir['ID']; ?>  
  <a ajax="no" id="modal_bottom_open_set" onclick="upload('/system/AJAX/php/gifts.php?id_dir=<?=$dir['ID']?>&url=<?=base64_encode($link)?>', 'attachments_upload')" class="button3"><?=icons('upload', 15, 'fa-fw')?> <?=lg('Загрузить')?></a> 
  </div>
    
  <div id='upload-gifts'>
  <?
    
  $column = db::get_column("SELECT COUNT(*) FROM `GIFTS` WHERE `ID_DIR` = ? AND `ACT` = ?", [$dir['ID'], 0]);
  $spage = spage($column, PAGE_SETTINGS);
  $page = page($spage);
  $limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;
    
  if ($column == 0){ 
    
    html::empty('Пока ничего не загружено');
  
  }else{
    
    ?>
    <div class='list-menu'>    
    <?=lg('Настройте и сохраните эти подарки для категории')?> "<?=tabs($dir['NAME'])?>":
    </div>
    <?
    
  }
  
  $data = db::get_string_all("SELECT * FROM `GIFTS` WHERE `ID_DIR` = ? AND `ACT` = ? ORDER BY `ID` DESC LIMIT ".$limit.", ".PAGE_SETTINGS, [$dir['ID'], 0]);  
  while ($list = $data->fetch()){
    
    ?>
    <div class='list-menu'>
    <img src='/files/upload/gifts/<?=$list['ID']?>.<?=$list['EXT']?>' style='max-width: 120px;'>
    <div class='button-optimize-div'></div>
    <button onclick="request('/admin/site/modules/?mod=gifts&add_gifts=<?=$dir['ID']?>&delete_gift=<?=$list['ID']?>&<?=TOKEN_URL?>', '#upload-gifts')" class='button2 button-optimize'><?=ICONS('trash', 15, 'fa-fw')?></button><br /><br />
      
    <?php
    if (post('ok_save_gift'.$list['ID'])){
      
      valid::create(array(
        
        'GIFT_NAME' => ['name'.$list['ID'], 'text', [1,120], 'Имя'],
        'GIFT_SUM' => ['sum'.$list['ID'], 'number_abs', [0,10000], 'Цена']
      
      ));
      
      if (db::get_column("SELECT COUNT(*) FROM `GIFTS` WHERE `NAME` = ? LIMIT 1", [GIFT_NAME]) > 0){
        
        error('Подарок с таким именем уже существует');
        
      }
      
      if (ERROR_LOG == 1){
        
        redirect('/admin/site/modules/?mod=gifts&add_gifts='.$dir['ID']);
      
      }
      
      db::get_set("UPDATE `GIFTS` SET `NAME` = ?, `SUM` = ?, `ACT` = ? WHERE `ID` = ? LIMIT 1", [GIFT_NAME, GIFT_SUM, 1, $list['ID']]);
      
      success('Подарок успешно сохранен');
      redirect('/admin/site/modules/?mod=gifts&add_gifts='.$dir['ID']);
    
    }      
    ?>
    
    <form method='post' class='ajax-form<?=$list['ID']?>' action='/admin/site/modules/?mod=gifts&add_gifts=<?=$dir['ID']?>'>
    <?=html::input('name'.$list['ID'], 'Придумайте название', null, null, null, 'form-control-100', 'text', null, 'gift')?> 
    <?=html::input('sum'.$list['ID'], 'Цена', null, null, null, 'form-control-20', 'text', null, 'money')?>
    <?=html::button('button3 ajax-button', 'ok_save_gift'.$list['ID'], 'save', 'Сохранить подарок', $list['ID'])?>    
    </form> 
      
    </div>
    <?
  
  }
  
  get_page('/admin/site/modules/?mod=gifts&add_gifts='.$dir['ID'].'&', $spage, $page, 'list-menu');
    
  ?></div></div><?
  
  back('/admin/site/modules/?mod=gifts&id_dir='.$dir['ID']);
  acms_footer();
  
}

/*
-----------------------
Редактирование подарков
-----------------------
*/

if (get('edit_gift')){
  
  $gift = db::get_string("SELECT * FROM `GIFTS` WHERE `ID` = ? LIMIT 1", [intval(get('edit_gift'))]);
  
  if (!isset($gift['ID'])){
    
    error('Неизвестная ошибка');
    redirect('/admin/site/modules/?mod=gifts');
    
  }
  
  if (post('ok_edit_gift')){
    
    valid::create(array(
      
      'GIFT_NAME' => ['name', 'text', [1,100], 'Имя'],
      'GIFT_SUM' => ['sum', 'number_abs', [0,10000], 'Цена']
    
    ));
    
    if (GIFT_NAME != $gift['NAME'] && db::get_column("SELECT COUNT(*) FROM `GIFTS` WHERE `NAME` = ? LIMIT 1", [GIFT_NAME]) > 0){
      
      error('Подарок с таким именем уже существует');
    
    }
    
    $dir = intval(post('id_dir'));
    
    if (ERROR_LOG == 1){
      
      redirect('/admin/site/modules/?mod=gifts&edit_gift='.$gift['ID']);
    
    }
    
    db::get_set("UPDATE `GIFTS` SET `NAME` = ?, `ID_DIR` = ?, `SUM` = ? WHERE `ID` = ? LIMIT 1", [GIFT_NAME, $dir, GIFT_SUM, $gift['ID']]);
    
    success('Изменения успешно приняты');
    redirect('/admin/site/modules/?mod=gifts&id_dir='.$dir);
    
  }
  
  ?>
  <div class='list'>
  <form method='post' class='ajax-form' action='/admin/site/modules/?mod=gifts&edit_gift=<?=$gift['ID']?>'>
  <?
  
  html::input('name', null, 'Название', null, tabs($gift['NAME']), 'form-control-50', null, null, 'gift');  
  html::input('sum', null, 'Цена', null, $gift['SUM'], 'form-control-30', null, null, 'money');
  
  $array = array();
  $data = db::get_string_all("SELECT * FROM `GIFTS_DIR` ORDER BY `ID` DESC");  
  while ($list = $data->fetch()){
 
    $array[$list['ID']] = [$list['NAME'], ($gift['ID_DIR'] == $list['ID'] ? "selected" : null)];
  
  }
  
  html::select('id_dir', $array, 'Категория', 'form-control-50-modify-select', 'folder-open');  
  html::button('button ajax-button', 'ok_edit_gift', 'save', 'Сохранить изменения');
  
  ?>
  </form>
  </div>
  <?
  
  back('/admin/site/modules/?mod=gifts&id_dir='.$gift['ID_DIR']);
  acms_footer();
  
}

/*
--------------------------------
Список подарков и управление ими
--------------------------------
*/

if (get('id_dir')){
  
  $dir = db::get_string("SELECT * FROM `GIFTS_DIR` WHERE `ID` = ? LIMIT 1", [intval(get('id_dir'))]);
  
  if (!isset($dir['ID'])){
    
    error('Неизвестная ошибка');
    redirect('/admin/site/modules/?mod=gifts');
    
  }
  
  $column = db::get_column("SELECT COUNT(*) FROM `GIFTS` WHERE `ID_DIR` = ? AND `ACT` = ?", [$dir['ID'], 1]);
  $spage = spage($column, PAGE_SETTINGS);
  $page = page($spage);
  $limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;
  
  ?>
  <div class='list-body'>
    
  <div class='list-menu'>
  <a href='/admin/site/modules/?mod=gifts&add_gifts=<?=$dir['ID']?>' class='button'><?=icons('plus', 15, 'fa-fw')?> <?=lg('Добавить подарки')?></a>
  </div> 
    
  <div id='gifts'>
      
  <div class='list-menu list-title'>
  <?=tabs($dir['NAME'])?>: <?=lg('список подарков')?> <span class='count'><?=$column?></span>
  </div>
  <?
    
  if ($column == 0){ 
    
    html::empty('Пока нет подарков');
  
  }
  
  $data = db::get_string_all("SELECT * FROM `GIFTS` WHERE `ID_DIR` = ? AND `ACT` = ? ORDER BY `ID` DESC LIMIT ".$limit.", ".PAGE_SETTINGS, [$dir['ID'], 1]);  
  while ($list = $data->fetch()){
    
    ?>
    <div class='list-menu'>
    <img src='/files/upload/gifts/<?=$list['ID']?>.<?=$list['EXT']?>' style='max-width: 120px;'><br />
    <?=tabs($list['NAME'])?><br /><br />
    <span class='count'><?=money($list['SUM'], 3)?></span>
    <div class='button-optimize-div'></div>
    <a href='/admin/site/modules/?mod=gifts&edit_gift=<?=$list['ID']?>' class='button3 button-optimize'><?=icons('pencil', 15, 'fa-fw')?></a>
    <button onclick="request('/admin/site/modules/?mod=gifts&id_dir=<?=$dir['ID']?>&delete_gift=<?=$list['ID']?>&<?=TOKEN_URL?>', '#gifts')" class='button2 button-optimize'><?=icons('trash', 15, 'fa-fw')?></button>
    </div>
    <?
  
  }
  
  get_page('/admin/site/modules/?mod=gifts&id_dir='.$dir['ID'].'&', $spage, $page, 'list-menu');
  
  ?></div></div><?
  
  back('/admin/site/modules/?mod=gifts');
  acms_footer();
  
}

/*
---------------
Новая категория
---------------
*/

if (get('get') == "add_dir"){
  
  if (post('ok_add_dir')){
    
    valid::create(array(
      
      'DIR_NAME' => ['name', 'text', [1,250], 'Имя'],
    
    ));
    
    if (db::get_column("SELECT COUNT(*) FROM `GIFTS_DIR` WHERE `NAME` = ? LIMIT 1", [DIR_NAME]) > 0){
      
      error('Категория с таким именем уже существует');
    
    }
    
    if (ERROR_LOG == 1){
      
      redirect('/admin/site/modules/?mod=gifts&get=add_dir');
    
    }
    
    db::get_add("INSERT INTO `GIFTS_DIR` (`NAME`) VALUES (?)", [DIR_NAME]); 
    
    success('Категория успешно добавлен');
    redirect('/admin/site/modules/?mod=gifts');
    
  }
  
  ?>
  <div class='list'>
  <form method='post' class='ajax-form' action='/admin/site/modules/?mod=gifts&get=add_dir'>
  <?
  
  html::input('name', null, 'Название категории:', null, null, 'form-control-100', null, null, 'folder-open');  
  html::button('button ajax-button', 'ok_add_dir', 'plus', 'Добавить');
  
  ?>
  <a href='/admin/site/modules/?mod=gifts' class='button-o'><?=lg('Отмена')?></a>
  </form>
  </div>
  <?
    
  back('/admin/site/modules/?mod=gifts');
  acms_footer();
  
}

/*
------------------------
Редактирование категории
------------------------
*/

if (get('edit_dir')){
  
  $dir = db::get_string("SELECT * FROM `GIFTS_DIR` WHERE `ID` = ? LIMIT 1", [intval(get('edit_dir'))]);
  
  if (!isset($dir['ID'])){
    
    error('Неизвестная ошибка');
    redirect('/admin/site/modules/?mod=gifts');
    
  }
  
  if (post('ok_edit_dir')){
    
    valid::create(array(
      
      'DIR_NAME' => ['name', 'text', [1,250], 'Имя'],
    
    ));
    
    if (DIR_NAME != $dir['NAME'] && db::get_column("SELECT COUNT(*) FROM `GIFTS_DIR` WHERE `NAME` = ? LIMIT 1", [DIR_NAME]) > 0){
      
      error('Категория с таким именем уже существует');
    
    }
    
    if (ERROR_LOG == 1){
      
      redirect('/admin/site/modules/?mod=gifts&edit_dir='.$dir['ID']);
    
    }
    
    db::get_set("UPDATE `GIFTS_DIR` SET `NAME` = ? WHERE `ID` = ? LIMIT 1", [DIR_NAME, $dir['ID']]);
    
    success('Изменения успешно приняты');
    redirect('/admin/site/modules/?mod=gifts');
    
  }
  
  ?>
  <div class='list'>
  <form method='post' class='ajax-form' action='/admin/site/modules/?mod=gifts&edit_dir=<?=$dir['ID']?>'>
  <?
  
  html::input('name', null, 'Введите название:', null, tabs($dir['NAME']), 'form-control-100', null, null, 'folder-open');  
  html::button('button ajax-button', 'ok_edit_dir', 'save', 'Сохранить изменения');
  
  ?>
  <a href='/admin/site/modules/?mod=gifts' class='button-o'><?=lg('Отмена')?></a>
  </form>  
  </div>
  <?
    
  back('/admin/site/modules/?mod=gifts');
  acms_footer();
  
}

/*
------------------
Удаление категории
------------------
*/

if (get('delete_dir')){
  
  get_check_valid();
  
  $dir = db::get_string("SELECT * FROM `GIFTS_DIR` WHERE `ID` = ? LIMIT 1", [intval(get('delete_dir'))]);
  
  if (!isset($dir['ID'])){
    
    error('Неизвестная ошибка');
    redirect('/admin/site/modules/?mod=gifts');
    
  }
  
  if (get('delete') == 'ok'){
    
    $data = db::get_string_all("SELECT * FROM `GIFTS` WHERE `ID_DIR` = ?", [$dir['ID']]);  
    while ($list = $data->fetch()){
      
      @unlink(ROOT.'/files/upload/gifts/'.$list['ID'].'.'.$list['EXT']);    
      db::get_set("DELETE FROM `GIFTS` WHERE `ID` = ? LIMIT 1", [$list['ID']]);
    
    }
    
    db::get_set("DELETE FROM `GIFTS_DIR` WHERE `ID` = ? LIMIT 1", [$dir['ID']]);
    
    success('Удаление прошло успешно');  
    redirect('/admin/site/modules/?mod=gifts');
  
  }
  
  ?>
  <div class='list'>
  <?=lg('Вы действительно хотите удалить категорию')?> "<b><?=tabs($dir['NAME'])?></b>"?<br /><br />
  <a href='/admin/site/modules/?mod=gifts&delete_dir=<?=$dir['ID']?>&delete=ok&<?=TOKEN_URL?>' class='button2'><?=icons('trash', 16, 'fa-fw')?> <?=lg('Удалить')?></a>
  <a href='/admin/site/modules/?mod=gifts' class='button-o'><?=lg('Отмена')?></a>
  </div>
  <?
    
  back('/admin/site/modules/?mod=gifts');
  acms_footer();
  
}

/*
----------------
Список категорий
----------------
*/

?>
<div class='list-body'> 
  
<div class='list-menu'>
<a href='/admin/site/modules/?mod=gifts&get=add_dir' class='button'><?=icons('plus', 15, 'fa-fw')?> <?=lg('Добавить категорию')?></a>
</div>
<?

$column = db::get_column("SELECT COUNT(*) FROM `GIFTS_DIR`");
$spage = spage($column, PAGE_SETTINGS);
$page = page($spage);
$limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;

?>
<div class='list-menu list-title'>
<?=lg('Список категорий')?>: <span class='count'><?=$column?></span>
</div>
<?

if ($column == 0){ 
  
  html::empty('Пока нет категорий');
  
}

$data = db::get_string_all("SELECT * FROM `GIFTS_DIR` ORDER BY `ID` DESC LIMIT ".$limit.", ".PAGE_SETTINGS);
while ($list = $data->fetch()){
  
  $count = db::get_column("SELECT COUNT(*) FROM `GIFTS` WHERE `ID_DIR` = ? AND `ACT` = ?", [$list['ID'], 1]);
  
  ?>
  <div class='list-menu'>
  <?=icons('folder-open', '18', 'fa-fw')?> <a href='/admin/site/modules/?mod=gifts&id_dir=<?=$list['ID']?>'><?=tabs($list['NAME'])?> <span class='count'><?=$count?></span></a><div class='button-optimize-div'></div>
  <a href='/admin/site/modules/?mod=gifts&edit_dir=<?=$list['ID']?>' class='button3 button-optimize'><?=icons('pencil', 15)?></a>
  <a href='/admin/site/modules/?mod=gifts&delete_dir=<?=$list['ID']?>&<?=TOKEN_URL?>' class='button2 button-optimize'><?=icons('trash', 15)?></a>
  </div>
  <?
  
}

get_page('/admin/site/modules/?mod=gifts&', $spage, $page, 'list-menu');

?></div><?