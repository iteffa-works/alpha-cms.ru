<?php
  
/*
----------------
Удаление смайлов
----------------
*/

if (get('delete_smile')){

  get_check_valid();
  
  $smile = db::get_string("SELECT `ID`,`EXT` FROM `SMILES` WHERE `ID` = ? LIMIT 1", [intval(get('delete_smile'))]);
  
  if (isset($smile['ID'])){
    
    @unlink(ROOT.'/files/upload/smiles/'.$smile['ID'].'.'.$smile['EXT']);  
    db::get_set("DELETE FROM `SMILES` WHERE `ID` = ? LIMIT 1", [$smile['ID']]);
    
  }
  
}
  
/*
------------------
Добавление смайлов
------------------
*/
  
if (get('add_smiles')){
  
  $dir = db::get_string("SELECT * FROM `SMILES_DIR` WHERE `ID` = ? LIMIT 1", [intval(get('add_smiles'))]);
  
  if (!isset($dir['ID'])){
    
    error('Неизвестная ошибка');
    redirect('/admin/site/modules/?mod=smiles');
    
  }
  
  ?>
  <div class='list-body'>
    
  <div class='list-menu list-title'>
  <?=tabs($dir['NAME'])?>: <?=lg('добавление смайлов')?>
  </div>
    
  <div class='list-menu'>    
  <?=attachments_result()?>
  <?php $link = '/admin/site/modules/?mod=smiles&add_smiles='.$dir['ID']; ?>
  <a ajax="no" id="modal_bottom_open_set" onclick="upload('/system/AJAX/php/smiles_upload.php?id_dir=<?=$dir['ID']?>&url=<?=base64_encode($link)?>', 'attachments_upload')" class="button3"><?=icons('upload', 15, 'fa-fw')?> <?=lg('Загрузить')?></a> 
  </div>
    
  <div id='upload-smiles'>
  <?
    
  $column = db::get_column("SELECT COUNT(*) FROM `SMILES` WHERE `ID_DIR` = ? AND `ACT` = ?", [$dir['ID'], 0]);
  $spage = spage($column, PAGE_SETTINGS);
  $page = page($spage);
  $limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;
    
  if ($column == 0){ 
    
    html::empty('Пока ничего не загружено');
  
  }else{
    
    ?>
    <div class='list-menu'>    
    <?=lg('Настройте и сохраните эти смайлы для категории')?> "<?=tabs($dir['NAME'])?>":
    </div>
    <?
    
  }
  
  $data = db::get_string_all("SELECT * FROM `SMILES` WHERE `ID_DIR` = ? AND `ACT` = ? ORDER BY `ID` DESC LIMIT ".$limit.", ".PAGE_SETTINGS, [$dir['ID'], 0]);  
  while ($list = $data->fetch()){
    
    ?>
    <div class='list-menu'>
    <img src='/files/upload/smiles/<?=$list['ID']?>.<?=$list['EXT']?>'>
    <div class='button-optimize-div'></div>
    <button onclick="request('/admin/site/modules/?mod=smiles&add_smiles=<?=$dir['ID']?>&delete_smile=<?=$list['ID']?>&<?=TOKEN_URL?>', '#upload-smiles')" class='button2 button-optimize'><?=ICONS('trash', 15, 'fa-fw')?></button><br /><br />
      
    <?php
    if (get('save') == $list['ID']){  
      
      if (post('ok_save_smile'.$list['ID'])){
        
        valid::create(array(
          
          'SMILE_NAME' => ['name'.$list['ID'], 'text', [1,50], 'Псевдоним']
        
        ));
        
        if (db::get_column("SELECT COUNT(*) FROM `SMILES` WHERE `NAME` = ? LIMIT 1", [SMILE_NAME]) > 0){
          
          error('Смайл с таким псевдонимом уже существует');
        
        }
        
        if (strpos(SMILE_NAME, " ")){
          
          error('В названии смайла не могут быть пробелы');
        
        }
        
        if (ERROR_LOG == 1){
          
          redirect('/admin/site/modules/?mod=smiles&add_smiles='.$dir['ID']);
        
        }
        
        db::get_set("UPDATE `SMILES` SET `NAME` = ?, `ACT` = ? WHERE `ID` = ? LIMIT 1", [SMILE_NAME, 1, $list['ID']]);
        
        success('Смайл успешно сохранен');
        redirect('/admin/site/modules/?mod=smiles&add_smiles='.$dir['ID']);
        
      }
    
    }      
    ?>
    
    <form method='post' class='ajax-form<?=$list['ID']?>' action='/admin/site/modules/?mod=smiles&add_smiles=<?=$dir['ID']?>&save=<?=$list['ID']?>'>
    <?=html::input('name'.$list['ID'], 'Придумайте псевдоним (Например: .sm1.)', null, null, null, 'form-control-100', 'text', null, 'smile-o')?> 
    <?=html::button('button3 ajax-button', 'ok_save_smile'.$list['ID'], 'save', 'Сохранить смайл', $list['ID'])?>    
    </form> 
      
    </div>
    <?
  
  }
  
  get_page('/admin/site/modules/?mod=smiles&add_smiles='.$dir['ID'].'&', $spage, $page, 'list-menu');
    
  ?></div></div><?
  
  back('/admin/site/modules/?mod=smiles&id_dir='.$dir['ID']);
  acms_footer();
  
}

/*
----------------------
Редактирование смайлов
----------------------
*/

if (get('edit_smile')){
  
  $smile = db::get_string("SELECT * FROM `SMILES` WHERE `ID` = ? LIMIT 1", [intval(get('edit_smile'))]);
  
  if (!isset($smile['ID'])){
    
    error('Неизвестная ошибка');
    redirect('/admin/site/modules/?mod=smiles');
    
  }
  
  if (post('ok_edit_smile')){
    
    valid::create(array(
      
      'SMILE_NAME' => ['name', 'text', [1,100], 'Псевдоним']
    
    ));
    
    if (SMILE_NAME != $smile['NAME'] && db::get_column("SELECT COUNT(*) FROM `SMILES` WHERE `NAME` = ? LIMIT 1", [SMILE_NAME]) > 0){
      
      error('Смайл с таким псевдонимом уже существует');
    
    }
    
    if (strpos(SMILE_NAME, " ")){
      
      error('В названии смайла не могут быть пробелы');
    
    }
    
    $dir = intval(post('id_dir'));
    
    if (ERROR_LOG == 1){
      
      redirect('/admin/site/modules/?mod=smiles&edit_smile='.$smile['ID']);
    
    }
    
    db::get_set("UPDATE `SMILES` SET `NAME` = ?, `ID_DIR` = ? WHERE `ID` = ? LIMIT 1", [SMILE_NAME, $dir, $smile['ID']]);
    
    success('Изменения успешно приняты');
    redirect('/admin/site/modules/?mod=smiles&id_dir='.$dir);
    
  }
  
  ?>
  <div class='list'>
  <form method='post' class='ajax-form' action='/admin/site/modules/?mod=smiles&edit_smile=<?=$smile['ID']?>'>
  <?
  
  html::input('name', null, 'Придумайте псевдоним (Например: .sm1.)', null, tabs($smile['NAME']), 'form-control-50', null, null, 'smile-o');  
  
  $array = array();
  $data = db::get_string_all("SELECT * FROM `SMILES_DIR` ORDER BY `ID` DESC");  
  while ($list = $data->fetch()){
 
    $array[$list['ID']] = [$list['NAME'], ($smile['ID_DIR'] == $list['ID'] ? "selected" : null)];
  
  }
  
  html::select('id_dir', $array, 'Категория', 'form-control-50-modify-select', 'folder-open');  
  html::button('button ajax-button', 'ok_edit_smile', 'save', 'Сохранить изменения');
  
  ?>
  </form>
  </div>
  <?
  
  back('/admin/site/modules/?mod=smiles&id_dir='.$smile['ID_DIR']);
  acms_footer();
  
}

/*
-------------------------------
Список смайлов и управление ими
-------------------------------
*/

if (get('id_dir')){
  
  $dir = db::get_string("SELECT * FROM `SMILES_DIR` WHERE `ID` = ? LIMIT 1", [intval(get('id_dir'))]);
  
  if (!isset($dir['ID'])){
    
    error('Неизвестная ошибка');
    redirect('/admin/site/modules/?mod=smiles');
    
  }
  
  $column = db::get_column("SELECT COUNT(*) FROM `SMILES` WHERE `ID_DIR` = ? AND `ACT` = ?", [$dir['ID'], 1]);
  $spage = spage($column, PAGE_SETTINGS);
  $page = page($spage);
  $limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;
  
  ?>
  <div class='list-body'>
    
  <div class='list-menu'>
  <a href='/admin/site/modules/?mod=smiles&add_smiles=<?=$dir['ID']?>' class='button'><?=icons('plus', 15, 'fa-fw')?> <?=lg('Добавить смайлы')?></a>
  </div> 
    
  <div id='smiles'>
      
  <div class='list-menu list-title'>
  <?=tabs($dir['NAME'])?>: <?=lg('список смайлов')?> <span class='count'><?=$column?></span>
  </div>
  <?
    
  if ($column == 0){ 
    
    html::empty('Пока нет смайлов');
  
  }
  
  $data = db::get_string_all("SELECT * FROM `SMILES` WHERE `ID_DIR` = ? AND `ACT` = ? ORDER BY `ID` DESC LIMIT ".$limit.", ".PAGE_SETTINGS, [$dir['ID'], 1]);  
  while ($list = $data->fetch()){
    
    ?>
    <div class='list-menu'>
    <img src='/files/upload/smiles/<?=$list['ID']?>.<?=$list['EXT']?>'><br />
    <?=tabs($list['NAME'])?>
    <div class='button-optimize-div'></div>
    <a href='/admin/site/modules/?mod=smiles&edit_smile=<?=$list['ID']?>' class='button3 button-optimize'><?=ICONS('pencil', 15, 'fa-fw')?></a>
    <button onclick="request('/admin/site/modules/?mod=smiles&id_dir=<?=$dir['ID']?>&delete_smile=<?=$list['ID']?>&<?=TOKEN_URL?>', '#smiles')" class='button2 button-optimize'><?=ICONS('trash', 15, 'fa-fw')?></button>
    </div>
    <?
  
  }
  
  get_page('/admin/site/modules/?mod=smiles&id_dir='.$dir['ID'].'&', $spage, $page, 'list-menu');
  
  ?></div></div><?
  
  back('/admin/site/modules/?mod=smiles');
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
    
    if (ERROR_LOG == 1){
      
      redirect('/admin/site/modules/?mod=smiles&get=add_dir');
    
    }
    
    db::get_add("INSERT INTO `SMILES_DIR` (`NAME`) VALUES (?)", [DIR_NAME]); 
    
    success('Категория успешно добавлен');
    redirect('/admin/site/modules/?mod=smiles');
    
  }
  
  ?>
  <div class='list'>
  <form method='post' class='ajax-form' action='/admin/site/modules/?mod=smiles&get=add_dir'>
  <?
  
  html::input('name', null, 'Название категории:', null, null, 'form-control-100', null, null, 'folder-open');  
  html::button('button ajax-button', 'ok_add_dir', 'plus', 'Добавить');
  
  ?>
  <a href='/admin/site/modules/?mod=smiles' class='button-o'><?=lg('Отмена')?></a>
  </form>
  </div>
  <?
    
  back('/admin/site/modules/?mod=smiles');
  acms_footer();
  
}

/*
------------------------
Редактирование категории
------------------------
*/

if (get('edit_dir')){
  
  $dir = db::get_string("SELECT * FROM `SMILES_DIR` WHERE `ID` = ? LIMIT 1", [intval(get('edit_dir'))]);
  
  if (!isset($dir['ID'])){
    
    error('Неизвестная ошибка');
    redirect('/admin/site/modules/?mod=smiles');
    
  }
  
  if (post('ok_edit_dir')){
    
    valid::create(array(
      
      'DIR_NAME' => ['name', 'text', [1,250], 'Имя'],
    
    ));
    
    if (ERROR_LOG == 1){
      
      redirect('/admin/site/modules/?mod=smiles&edit_dir='.$dir['ID']);
    
    }
    
    db::get_set("UPDATE `SMILES_DIR` SET `NAME` = ? WHERE `ID` = ? LIMIT 1", [DIR_NAME, $dir['ID']]);
    
    success('Изменения успешно приняты');
    redirect('/admin/site/modules/?mod=smiles');
    
  }
  
  ?>
  <div class='list'>
  <form method='post' class='ajax-form' action='/admin/site/modules/?mod=smiles&edit_dir=<?=$dir['ID']?>'>
  <?
  
  html::input('name', null, 'Введите название:', null, tabs($dir['NAME']), 'form-control-100', null, null, 'folder-open');  
  html::button('button ajax-button', 'ok_edit_dir', 'save', 'Сохранить изменения');
  
  ?>
  <a href='/admin/site/modules/?mod=smiles' class='button-o'><?=lg('Отмена')?></a>
  </form>  
  </div>
  <?
    
  back('/admin/site/modules/?mod=smiles');
  acms_footer();
  
}

/*
------------------
Удаление категории
------------------
*/

if (get('delete_dir')){
  
  get_check_valid();
  
  $dir = db::get_string("SELECT * FROM `SMILES_DIR` WHERE `ID` = ? LIMIT 1", [intval(get('delete_dir'))]);
  
  if (!isset($dir['ID'])){
    
    error('Неизвестная ошибка');
    redirect('/admin/site/modules/?mod=smiles');
    
  }
  
  if (get('delete') == 'ok'){
    
    $data = db::get_string_all("SELECT * FROM `SMILES` WHERE `ID_DIR` = ?", [$dir['ID']]);  
    while ($list = $data->fetch()){
      
      @unlink(ROOT.'/files/upload/smiles/'.$list['ID'].'.'.$list['EXT']);    
      db::get_set("DELETE FROM `SMILES` WHERE `ID` = ? LIMIT 1", [$list['ID']]);
    
    }
    
    db::get_set("DELETE FROM `SMILES_DIR` WHERE `ID` = ? LIMIT 1", [$dir['ID']]);
    
    success('Удаление прошло успешно');  
    redirect('/admin/site/modules/?mod=smiles');
  
  }
  
  ?>
  <div class='list'>
  <?=lg('Вы действительно хотите удалить категорию')?> "<b><?=tabs($dir['NAME'])?></b>"?<br /><br />
  <a href='/admin/site/modules/?mod=smiles&delete_dir=<?=$dir['ID']?>&delete=ok&<?=TOKEN_URL?>' class='button2'><?=icons('trash', 16, 'fa-fw')?> <?=lg('Удалить')?></a>
  <a href='/admin/site/modules/?mod=smiles' class='button-o'><?=lg('Отмена')?></a>
  </div>
  <?
    
  back('/admin/site/modules/?mod=smiles');
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
<a href='/admin/site/modules/?mod=smiles&get=add_dir' class='button'><?=icons('plus', 15, 'fa-fw')?> <?=lg('Добавить категорию')?></a>
</div>
<?

$column = db::get_column("SELECT COUNT(*) FROM `SMILES_DIR`");
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

$data = db::get_string_all("SELECT * FROM `SMILES_DIR` ORDER BY `ID` DESC LIMIT ".$limit.", ".PAGE_SETTINGS);
while ($list = $data->fetch()){
  
  $count = db::get_column("SELECT COUNT(*) FROM `SMILES` WHERE `ID_DIR` = ? AND `ACT` = ?", [$list['ID'], 1]);
  
  ?>
  <div class='list-menu'>
  <?=icons('smile-o', '18', 'fa-fw')?> <a href='/admin/site/modules/?mod=smiles&id_dir=<?=$list['ID']?>'><?=tabs($list['NAME'])?> <span class='count'><?=$count?></span></a><div class='button-optimize-div'></div>
  <a href='/admin/site/modules/?mod=smiles&edit_dir=<?=$list['ID']?>' class='button3 button-optimize'><?=icons('pencil', 15)?></a>
  <a href='/admin/site/modules/?mod=smiles&delete_dir=<?=$list['ID']?>&<?=TOKEN_URL?>' class='button2 button-optimize'><?=icons('trash', 15)?></a>
  </div>
  <?
  
}

get_page('/admin/site/modules/?mod=smiles&', $spage, $page, 'list-menu');

?></div><?