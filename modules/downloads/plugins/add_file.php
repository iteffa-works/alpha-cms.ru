<?php
  
/*
----------------------------
Добавление файлов в загрузки
----------------------------
*/ 
  
$id_file = intval(get('id_file'));
$type = tabs(get('type'));
$add_url = null;

if ($id_file > 0 && str($type) > 0) {
  
  get_check_valid();
  
  $file = db::get_string("SELECT * FROM `".strtoupper($type)."` WHERE `ID` = ? LIMIT 1", [$id_file]);
  $ext = explode(";", strtolower(preg_replace('/\s+/', '', $dir['EXT'])));
  $add_url = '&id_file='.$file['ID'].'&type='.$type.'&'.TOKEN_URL;
  
  if ($type == 'music') {
    
    $name = tabs($file['FACT_NAME']).'.'.tabs($file['EXT']);
  
  }else{
    
    $name = tabs($file['NAME']).'.'.tabs($file['EXT']);
  
  }
  
  if (!isset($file['ID'])) {
    
    error('Файл не найден');
    redirect('/m/downloads/');
  
  }
  
  if (db::get_column("SELECT COUNT(*) FROM `".strtoupper($type)."_DIR` WHERE `PRIVATE` != ? AND `ID` = ? LIMIT 1", [0, $file['ID_DIR']]) > 0){    
    
    error('Этот файл нельзя добавить в загрузки из-за настроек приватности');
    redirect('/m/downloads/');
  
  }
  
  if (access('downloads', null) == false && $file['USER_ID'] != user('ID')){
    
    error('Неверная директива');
    redirect('/');
  
  }
  
  ?><div class='list'><?
    
  if (!get('get')) {
    
    ?><b><?=lg('Выберите категорию для добавления файла')?>:</b><?
    
  }      
  
  if (in_array($file['EXT'], $ext)) {
    
    if (get('get') == 'delete_file') {
      
      db::get_set("DELETE FROM `DOWNLOADS` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ?", [$id_file, $type]);
      
      success('Файл успешно удален из загрузок');
      redirect('/m/'.$type.'/show/?id='.$id_file);
      
    }
    
    if (get('get') == 'add_file_ok') {
      
      if (db::get_column("SELECT COUNT(*) FROM `DOWNLOADS` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ? LIMIT 1", [$id_file, $type]) == 0){
        
        success('Файл успешно добавлен в загрузки');
        
      }else{
        
        db::get_set("DELETE FROM `DOWNLOADS` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ?", [$id_file, $type]);
        
        success('Файл успешно перемещен в загрузках');
        
      }
      
      db::get_set("INSERT INTO `DOWNLOADS` (`NAME`, `USER_ID`, `OBJECT_ID`, `OBJECT_TYPE`, `ID_DIR`, `ID_DIR_O`, `TIME`) VALUES (?, ?, ?, ?, ?, ?, ?)", [$file['NAME'], $file['USER_ID'], $id_file, $type, $id, $dir['ID_DIR_O'], TM]);
      
      redirect('/m/'.$type.'/show/?id='.$id_file);
    
    }
    
    if (get('get') == 'add_file') {
      
      ?>
      <?=lg('Вы действительно хотите добавить файл %s в категорию %s?', '<b>'.$name.'</b>', '<b>'.tabs($dir['NAME']).'</b>')?><br /><br />
      <?=lg('Внимание! Перед добавлением файла в данную категорию загрузок убедитесь, что он соответствует тематике категории. За добавление файла в категорию с неправильной тематикой, ваш аккаунт может быть заблокирован временно или навсегда.')?><br /><br /> 
      <a href='/m/downloads/?id=<?=$id?><?=$add_url?>&get=add_file_ok' class='button'><?=lg('Добавить')?></a>
      <a href='/m/downloads/?id=<?=$id?><?=$add_url?>' class='button-o'><?=lg('Отмена')?></a>  
      <?
      
    }else{
      
      ?>
      <br /><br />
      <a href='/m/downloads/?id=<?=$id?><?=$add_url?>&get=add_file' class='btn'><?=icons('plus', 15, 'fa-fw')?> <?=lg('Добавить файл сюда')?></a>
      <?
      
    }
  
  }elseif (!in_array($file['EXT'], $ext) && $id > 0 && str($dir['EXT']) > 0){
    
    ?>
    <br /><br />
    <font color='#DC466E'><?=lg('Данная категория не подходит для файла %s, выберите другую категорию', '<b>'.$name.'</b>')?></font>
    <?
    
  }
  
  ?></div><?

}