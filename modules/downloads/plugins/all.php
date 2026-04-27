<?php

//Добавление файлов в загрузки
require (ROOT.'/modules/downloads/plugins/add_file.php');

if ($id > 0) {
  
  ?>
  <div class='list'>
  <?=lg('Категория')?> <font color='#D9D273'><?=icons('folder', 17, 'fa-fw')?></font> "<b><?=lg(tabs($dir['NAME']))?></b>"
  </div>
  <?
  
}

if (user('ID') > 0){
  
  if (access('downloads', null) == false && $id > 0 || access('downloads', null) == true){
    
    ?><div class='list'><?
      
  }
  
  if (str($dir['EXT']) > 0){
    
    ?>
    <a href='/m/downloads/add_file/?id=<?=$id?>&<?=TOKEN_URL?>' class='btn'><?=icons('plus', 15, 'fa-fw')?> <?=lg('Добавить файл')?></a>
    <?
      
  }
  
  if (access('downloads', null) == true){
    
    require (ROOT.'/modules/downloads/plugins/delete_dir.php');
    
    ?><a href='/m/downloads/add_folder/?id=<?=$id?>&<?=TOKEN_URL?>' class='btn'><?=icons('plus', 15, 'fa-fw')?> <?=lg('Добавить категорию')?></a><?
      
    if ($id > 0) {
      
      ?>
      <a href='/m/downloads/edit_folder/?id=<?=$id?>&<?=TOKEN_URL?>' class='btn'><?=icons('pencil', 15, 'fa-fw')?> <?=lg('Редактировать категорию')?></a>
      <a href='/m/downloads/?id=<?=$id?>&get=delete_dir&<?=TOKEN_URL?>' class='btn'><?=icons('trash', 15, 'fa-fw')?> <?=lg('Удалить категорию')?></a>
      <?
      
    }
    
  }
  
  if (access('downloads', null) == false && $id > 0 || access('downloads', null) == true){
    
    ?></div><?
      
  }
  
}

//Настройки приватности
require (ROOT.'/modules/downloads/plugins/private_dir.php');

$array = array();
$data = db::get_string_all("SELECT * FROM `DOWNLOADS_DIR` WHERE `ID_DIR` = ? ORDER BY `ID` DESC", [$id]);
while ($list = $data->fetch()) {

  $array[] = array('dir' => 1, 'list' => $list);

}

$data = db::get_string_all("SELECT * FROM `DOWNLOADS` WHERE `ID_DIR` = ? ORDER BY `TIME` DESC", [$id]);
while ($list = $data->fetch()) {

  $array[] = array('dir' => 0, 'list' => $list);

}

$column = sizeof($array);
$spage = spage($column, PAGE_SETTINGS);
$page = page($spage);
$limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;

if ($column == 0){ 
  
  html::empty('Пока пусто');
  
}else{
  
  ?><div class='list-body'><?
  
}

$music_count = -1;
$msr = null;
for ($i = $limit; $i < $column && $i < PAGE_SETTINGS * $page; $i++){
  
  $list = $array[$i]['list'];
  
  /*
  ---------
  Категории
  ---------
  */
  
  if ($array[$i]['dir'] == 1) {
    
    $count_files = 0;
    $count_files_new = 0; 
    $count_files = $count_files + db::get_column("SELECT COUNT(*) FROM `DOWNLOADS` WHERE `ID_DIR` = ?", [$list['ID']]);
    $count_files_new = $count_files_new + db::get_column("SELECT COUNT(*) FROM `DOWNLOADS` WHERE `ID_DIR` = ? AND `TIME` > ?", [$list['ID'], (TM-86400)]);
    
    $data2 = db::get_string_all("SELECT `ID`,`NAME` FROM `DOWNLOADS_DIR` WHERE `ID_DIR_O` > '0' AND (`ID_DIR` = ? OR `ID_DIR_O` = ?)", [$list['ID'], $list['ID']]);
    while ($list2 = $data2->fetch()) {
      
      $count_files = $count_files + db::get_column("SELECT COUNT(*) FROM `DOWNLOADS` WHERE `ID_DIR` = ?", [$list2['ID']]);
      $count_files_new = $count_files_new + db::get_column("SELECT COUNT(*) FROM `DOWNLOADS` WHERE `ID_DIR` = ? AND `TIME` > ?", [$list2['ID'], (TM-86400)]);
      
      if ($list['ID_DIR_O'] > 0) {
        
        $data3 = db::get_string_all("SELECT `ID`,`NAME` FROM `DOWNLOADS_DIR` WHERE `ID_DIR_O` > '0' AND `ID_DIR` = ?", [$list2['ID']]);
        while ($list3 = $data3->fetch()) {       
          
          $count_files = $count_files + db::get_column("SELECT COUNT(*) FROM `DOWNLOADS` WHERE `ID_DIR` = ?", [$list3['ID']]);
          $count_files_new = $count_files_new + db::get_column("SELECT COUNT(*) FROM `DOWNLOADS` WHERE `ID_DIR` = ? AND `TIME` > ?", [$list3['ID'], (TM-86400)]);
        
        }
        
      }
    
    }
    
    if ($count_files_new > 0) {
      
      $count_files_new = " <span class='count' style='background-color: #FF687D'>+".$count_files_new."</span>";
      
    }else{
      
      $count_files_new = null;
      
    }
    
    ?>
    <a href='/m/downloads/?id=<?=$list['ID']?><?=$add_url?>'>
    <div class='list-menu hover'>
    <font color='#D9D273'><?=icons('folder', 20, 'fa-fw')?></font> <?=lg(tabs($list['NAME']))?> <span class='count'><?=$count_files?></span> <?=$count_files_new?>
    </div>
    </a>
    <?
    
  }else{
    
    /*
    -----
    Файлы
    -----
    */
    
    $file = db::get_string("SELECT * FROM `".strtoupper(esc($list['OBJECT_TYPE']))."` WHERE `ID` = ? LIMIT 1", [$list['OBJECT_ID']]);
    
    if ($list['OBJECT_TYPE'] == 'music') {
      
      $music_count++;
      $msr .= $file['ID'].",";
      $id_post = 0;
      
      ?><div class='list-menu'><?
      echo music_player($file['ID'], $file['EXT'], $file['ARTIST'], $file['NAME'], $file['DURATION'], $music_count, $id_post);
      ?></div><?
      
    }
    
    if ($list['OBJECT_TYPE'] == 'photos') {
      
      $list['ID'] = $file['ID'];
      $list['EXT'] = $file['EXT'];
      $list['NAME'] = $file['NAME'];
      $list['SHIF'] = $file['SHIF'];
      
      require (ROOT.'/modules/photos/plugins/list.php');
      echo $photos_list;
      
    }
    
    if ($list['OBJECT_TYPE'] == 'videos') {
      
      $list['ID'] = $file['ID'];
      $list['EXT'] = $file['EXT'];
      $list['NAME'] = $file['NAME'];
      $list['DURATION'] = $file['DURATION'];
      
      require (ROOT.'/modules/videos/plugins/list-mini.php');
      echo $video_list_mini;
      
    }
    
    if ($list['OBJECT_TYPE'] == 'files') {
      
      $list['ID'] = $file['ID'];
      $list['EXT'] = $file['EXT'];
      $list['NAME'] = $file['NAME'];
      
      require (ROOT.'/modules/files/plugins/list-mini.php');
      echo $files_list_mini;
      
    }
    
  }
  
}

if (str($msr) > 0) {
  
  ?><span class="music_post<?=$id_post?>" array="<?=$msr?>"></span><?
    
}

if ($column > 0){ 
  
  ?></div><?
  
}

get_page('/m/downloads/?id='.$id.$add_url.'&', $spage, $page, 'list');

if ($id > 0) {
  
  back('/m/downloads/?id='.$id_dir.$add_url);
  
}else{
  
  back('/');
  
}