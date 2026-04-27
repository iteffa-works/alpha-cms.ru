<?php 
$id_dir = intval(get('id'));  
html::title('Добавить категорию');
acms_header(); 
get_check_valid();
access('users');

if (config('PRIVATE_DOWNLOADS') == 0){
  
  error('Модуль отключен администратором');
  redirect('/');
  
}

if (post('ok_downloads_dir')){
  
  valid::create(array(
    
    'DL_NAME' => ['name', 'text', [2, 120], 'Название', 0],
    'DL_RATING' => ['rating', 'number', [0, 999999], 'Рейтинг', 0],
    'DL_PRIVATE' => ['private', 'number', [0, 5], 'Приватность'],
    'DL_EXT' => ['ext', 'text', [0, 2000], 'Допустимые форматы', 0],
    'DL_ID_DIR' => ['id_dir', 'number', [0, 999999], 'Категория']
  
  ));
  
  if (db::get_column("SELECT COUNT(*) FROM `DOWNLOADS_DIR` WHERE `NAME` = ? AND `ID_DIR` = ? LIMIT 1", [DL_NAME, $id_dir]) == 1){
    
    error('Категория с таким названием уже существует в этой директории');
    redirect('/m/downloads/add_folder/?id='.$id_dir.'&'.TOKEN_URL);
    
  }
  
  if (ERROR_LOG == 1){
    
    redirect('/m/downloads/add_folder/?id='.$id_dir.'&'.TOKEN_URL);
  
  }
  
  $dir = db::get_string("SELECT `ID_DIR_O`,`ID` FROM `DOWNLOADS_DIR` WHERE `ID` = ? LIMIT 1", [DL_ID_DIR]);
  
  if (intval($dir['ID_DIR_O']) > 0) {
    
    $id_dir_o = $dir['ID_DIR_O'];
    
  }else{
    
    $id_dir_o = DL_ID_DIR;
  
  }
  
  db::get_add("INSERT INTO `DOWNLOADS_DIR` (`NAME`, `PRIVATE`, `ID_DIR`, `EXT`, `RATING`, `ID_DIR_O`) VALUES (?, ?, ?, ?, ?, ?)", [DL_NAME, DL_PRIVATE, DL_ID_DIR, DL_EXT, DL_RATING, $id_dir_o]);
  
  success('Категория успешно создана');
  redirect('/m/downloads/?id='.$id_dir);
  
}

?>    
<div class='list'>
<form method='post' class='ajax-form' action='/m/downloads/add_folder/?id=<?=$id_dir?>&<?=TOKEN_URL?>'>
<?=html::input('name', 'Введите название', null, null, null, 'form-control-100', 'text', null, 'folder')?>
<?=html::select('private', array(
  0 => ['Всем', 0], 
  1 => ['Только администрации', 1]
), 'Доступ', 'form-control-100-modify-select', 'lock')?>    
<?=html::select('rating', array(
  0 => ['0', 0], 
  5 => ['5', 5], 
  10 => ['10', 10], 
  15 => ['15', 15], 
  20 => ['20', 20], 
  25 => ['25', 25], 
  30 => ['30', 30], 
  35 => ['35', 35], 
  40 => ['40', 40], 
  45 => ['45', 45], 
  50 => ['50', 50], 
  60 => ['60', 60], 
  70 => ['70', 70], 
  80 => ['80', 80], 
  90 => ['90', 90], 
  100 => ['100', 100], 
  120 => ['120', 120], 
  140 => ['140', 140], 
  160 => ['160', 160], 
  180 => ['180', 180], 
  200 => ['200', 200], 
  250 => ['250', 250], 
  300 => ['300', 300], 
  350 => ['350', 350], 
  400 => ['400', 400], 
  450 => ['450', 450], 
  500 => ['500', 500], 
  1000 => ['1000', 1000], 
  2000 => ['2000', 2000], 
  3000 => ['3000', 3000]
), 'Уровень рейтинга для доступа', 'form-control-100-modify-select', 'line-chart')?>
<?php
$array = array();
$array[0] = ['Корневая директория', ($id_dir == 0 ? "selected" : null)];
$data = db::get_string_all("SELECT * FROM `DOWNLOADS_DIR` ORDER BY `ID` DESC");  
while ($list = $data->fetch()){
  
  $array[$list['ID']] = [$list['NAME'], ($id_dir == $list['ID'] ? "selected" : null)];

}
html::select('id_dir', $array, 'Категория', 'form-control-100-modify-select', 'folder');
?>
<?=html::input('ext', 'Перечислите форматы через ; (jpg;png...)', null, null, null, 'form-control-100', 'text', null, 'file')?>
  
* <?=lg('если допустимые форматы файлов не указаны, то данная директория по умолчанию будет закрыта для добавления файлов')?><br /><br /> 
  
<?=html::button('button ajax-button', 'ok_downloads_dir', 'plus', 'Добавить')?>  
<a class='button-o' href='/m/downloads/?id=<?=$id_dir?>'><?=lg('Отмена')?></a>
<form>
</div>
<?
  
back('/m/downloads/?id='.$id_dir);  
acms_footer();