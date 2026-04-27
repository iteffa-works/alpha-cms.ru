<?php 
$dir = db::get_string("SELECT * FROM `DOWNLOADS_DIR` WHERE `ID` = ? LIMIT 1", [intval(get('id'))]);
html::title(lg('Редактировать категорию %s', tabs($dir['NAME'])));
acms_header(); 
get_check_valid();
access('users');

if (config('PRIVATE_DOWNLOADS') == 0){
  
  error('Модуль отключен администратором');
  redirect('/');
  
}

if (!isset($dir['ID'])) {
  
  error('Неверная директива');
  redirect('/m/downloads/');

}

if (post('ok_edit_downloads_dir')){
  
  valid::create(array(
    
    'DL_NAME' => ['name', 'text', [2, 120], 'Название', 0],
    'DL_RATING' => ['rating', 'number', [0, 999999], 'Рейтинг', 0],
    'DL_PRIVATE' => ['private', 'number', [0, 5], 'Приватность'],
    'DL_EXT' => ['ext', 'text', [0, 2000], 'Допустимые форматы', 0],
    'DL_ID_DIR' => ['id_dir', 'number', [0, 999999], 'Категория']
  
  ));
  
  if (DL_NAME != $dir['NAME'] && db::get_column("SELECT COUNT(*) FROM `DOWNLOADS_DIR` WHERE `NAME` = ? AND `ID_DIR` = ? LIMIT 1", [DL_NAME, $dir['ID']]) == 1){
    
    error('Категория с таким названием уже существует в этой директории');
    redirect('/m/downloads/edit_folder/?id='.$dir['ID'].'&'.TOKEN_URL);
    
  }
  
  if (ERROR_LOG == 1){
    
    redirect('/m/downloads/edit_folder/?id='.$dir['ID'].'&'.TOKEN_URL);
  
  }
  
  $dir2 = db::get_string("SELECT `ID_DIR_O`,`ID` FROM `DOWNLOADS_DIR` WHERE `ID` = ? LIMIT 1", [DL_ID_DIR]);
  
  if (intval($dir2['ID_DIR_O']) > 0) {
    
    $id_dir_o = $dir2['ID_DIR_O'];
    
  }else{
    
    $id_dir_o = DL_ID_DIR;
  
  }
  
  db::get_set("UPDATE `DOWNLOADS` SET `ID_DIR_O` = ? WHERE `ID_DIR` = ?", [$id_dir_o, $dir['ID']]);
  db::get_set("UPDATE `DOWNLOADS_DIR` SET `NAME` = ?, `PRIVATE` = ?, `ID_DIR` = ?, `EXT` = ?, `RATING` = ?, `ID_DIR_O` = ? WHERE `ID` = ? LIMIT 1", [DL_NAME, DL_PRIVATE, DL_ID_DIR, DL_EXT, DL_RATING, $id_dir_o, $dir['ID']]);
  
  success('Изменения успешно приняты');
  redirect('/m/downloads/?id='.$dir['ID']);
  
}

?>    
<div class='list'>
<form method='post' class='ajax-form' action='/m/downloads/edit_folder/?id=<?=$dir['ID']?>&<?=TOKEN_URL?>'>
<?=html::input('name', 'Введите название', null, null, tabs($dir['NAME']), 'form-control-100', 'text', null, 'folder')?>
<?=html::select('private', array(
  0 => ['Всем', ($dir['PRIVATE'] == 0 ? "selected" : null)], 
  1 => ['Только администрации', ($dir['PRIVATE'] == 1 ? "selected" : null)]
), 'Доступ', 'form-control-100-modify-select', 'lock')?>    
<?=html::select('rating', array(
  0 => ['0', ($dir['RATING'] == 0 ? "selected" : null)], 
  5 => ['5', ($dir['RATING'] == 5 ? "selected" : null)], 
  10 => ['10', ($dir['RATING'] == 10 ? "selected" : null)], 
  15 => ['15', ($dir['RATING'] == 15 ? "selected" : null)], 
  20 => ['20', ($dir['RATING'] == 20 ? "selected" : null)], 
  25 => ['25', ($dir['RATING'] == 25 ? "selected" : null)], 
  30 => ['30', ($dir['RATING'] == 30 ? "selected" : null)], 
  35 => ['35', ($dir['RATING'] == 35 ? "selected" : null)], 
  40 => ['40', ($dir['RATING'] == 40 ? "selected" : null)], 
  45 => ['45', ($dir['RATING'] == 45 ? "selected" : null)], 
  50 => ['50', ($dir['RATING'] == 50 ? "selected" : null)], 
  60 => ['60', ($dir['RATING'] == 60 ? "selected" : null)], 
  70 => ['70', ($dir['RATING'] == 70 ? "selected" : null)], 
  80 => ['80', ($dir['RATING'] == 80 ? "selected" : null)], 
  90 => ['90', ($dir['RATING'] == 90 ? "selected" : null)], 
  100 => ['100', ($dir['RATING'] == 100 ? "selected" : null)], 
  120 => ['120', ($dir['RATING'] == 120 ? "selected" : null)], 
  140 => ['140', ($dir['RATING'] == 140 ? "selected" : null)], 
  160 => ['160', ($dir['RATING'] == 160 ? "selected" : null)], 
  180 => ['180', ($dir['RATING'] == 180 ? "selected" : null)], 
  200 => ['200', ($dir['RATING'] == 200 ? "selected" : null)], 
  250 => ['250', ($dir['RATING'] == 250 ? "selected" : null)], 
  300 => ['300', ($dir['RATING'] == 300 ? "selected" : null)], 
  350 => ['350', ($dir['RATING'] == 350 ? "selected" : null)], 
  400 => ['400', ($dir['RATING'] == 400 ? "selected" : null)], 
  450 => ['450', ($dir['RATING'] == 450 ? "selected" : null)], 
  500 => ['500', ($dir['RATING'] == 500 ? "selected" : null)], 
  1000 => ['1000', ($dir['RATING'] == 1000 ? "selected" : null)], 
  2000 => ['2000', ($dir['RATING'] == 2000 ? "selected" : null)], 
  3000 => ['3000', ($dir['RATING'] == 3000 ? "selected" : null)]
), 'Уровень рейтинга для доступа', 'form-control-100-modify-select', 'line-chart')?>
<?php
$array = array();
$array[0] = ['Корневая директория', ($dir['ID_DIR'] == 0 ? "selected" : null)];
$data = db::get_string_all("SELECT * FROM `DOWNLOADS_DIR` WHERE `ID` != ? ORDER BY `ID` DESC", [$dir['ID']]);  
while ($list = $data->fetch()){
  
  $array[$list['ID']] = [$list['NAME'], ($dir['ID_DIR'] == $list['ID'] ? "selected" : null)];

}
html::select('id_dir', $array, 'Категория', 'form-control-100-modify-select', 'folder');
?>
<?=html::input('ext', 'Перечислите форматы через ; (jpg;png...)', null, null, tabs($dir['EXT']), 'form-control-100', 'text', null, 'file')?>
  
* <?=lg('если допустимые форматы файлов не указаны, то данная директория по умолчанию будет закрыта для добавления файлов')?><br /><br /> 
  
<?=html::button('button ajax-button', 'ok_edit_downloads_dir', 'save', 'Сохранить')?>  
<a class='button-o' href='/m/downloads/?id=<?=$dir['ID']?>'><?=lg('Отмена')?></a>
<form>
</div>
<?
  
back('/m/downloads/?id='.$dir['ID']);  
acms_footer();