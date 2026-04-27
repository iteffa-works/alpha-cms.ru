<?php
  
if (get('install') == 'ok'){  
  
  require (ROOT.'/files/upload/alpha_installer/set/set.php');
  
  //Если есть файл /files/upload/alpha_installer/sql/db.sql, то идентифицируем его
  zip::rename_file(ROOT.'/files/upload/alpha_installer/'.$archive['ID'].'.'.$archive['EXT'], 'files/upload/alpha_installer/sql/db.sql', 'files/upload/alpha_installer/sql/'.$archive['FACT_NAME'].'.sql');
  
  //Если есть файл /files/upload/alpha_install/sql/delete/db.sql, то идентифицируем его
  zip::rename_file(ROOT.'/files/upload/alpha_installer/'.$archive['ID'].'.'.$archive['EXT'], 'files/upload/alpha_installer/sql/delete/delete_db.sql', 'files/upload/alpha_installer/sql/delete/delete_'.$archive['FACT_NAME'].'.sql');
  
  //Если есть файл /files/upload/alpha_install/php/config.php, то идентифицируем его
  zip::rename_file(ROOT.'/files/upload/alpha_installer/'.$archive['ID'].'.'.$archive['EXT'], 'files/upload/alpha_installer/php/config.php', 'files/upload/alpha_installer/php/'.$archive['FACT_NAME'].'.php');
  
  //Распаковываем архив
  zip::unpack(ROOT.'/files/upload/alpha_installer/'.$archive['ID'].'.'.$archive['EXT'], ROOT.'/');
  
  //Выполняем запросы в базу данных, если есть
  if (is_file(ROOT.'/files/upload/alpha_installer/sql/'.$archive['FACT_NAME'].'.sql')){
    
    if (db::get_sql_file(ROOT.'/files/upload/alpha_installer/sql/'.$archive['FACT_NAME'].'.sql') == 0) {
      
      error('Установка не завершена: не удалось выполнить запрос(-ы) в базу данных');
      redirect('/admin/system/alpha_installer/?id='.$archive['ID']);
    
    }
    
    @unlink(ROOT.'/files/upload/alpha_installer/sql/'.$archive['FACT_NAME'].'.sql');
    
  }
  
  //Выполняем команды из конфигурационного файла, если есть
  if (is_file(ROOT.'/files/upload/alpha_installer/php/'.$archive['FACT_NAME'].'.php')){
    
    require (ROOT.'/files/upload/alpha_installer/php/'.$archive['FACT_NAME'].'.php');
    
  }
  
  //Извлечение файлов из архива и запись в базу
  $zip = new ZipArchive();
  
  $zip->open(ROOT.'/files/upload/alpha_installer/'.$archive['ID'].'.'.$archive['EXT']);
  $i = 0;  
  while ($name = $zip->getNameIndex($i)) {
    
    $i++;
    
    if (is_dir(ROOT.'/'.$name)) {
      
      @chmod(ROOT.'/'.$name, 0755);
      
    }
    
    if (is_file(ROOT.'/'.$name)) {
      
      db::get_add("INSERT INTO `PANEL_ALPHA_INSTALL_DATA` (`ID_AI`, `DIR`) VALUES (?, ?)", [$archive['ID'], esc($name)]);
      
    }
  
  }
  
  $zip->close();
  
  //Удаляем файлы установки, так как они уже не нужны
  @unlink(ROOT.'/files/upload/alpha_installer/'.$archive['ID'].'.'.$archive['EXT']);
  @unlink(ROOT.'/files/upload/alpha_installer/php/'.$archive['FACT_NAME'].'.php');
  
  //Помечаем компонент как успешно установленный
  db::get_set("UPDATE `PANEL_ALPHA_INSTALL` SET `ACT` = ?, `SYSTEM` = ?, `TIME` = ?, `UPDATE` = ?, `NAME` = ? WHERE `ID` = ? LIMIT 1", [1, INSTALL_CHECK_FILES_DOUBLE, TM, INSTALL_CHECK_UPDATE, esc($archive['NAME']), $archive['ID']]);
  
  success('Компонент успешно установлен');
  redirect('/admin/system/alpha_installer/');
  
}