<?php
$account = db::get_string("SELECT `ID`,`LOGIN` FROM `USERS` WHERE `ID` = ? LIMIT 1", [intval(get('id'))]);  
  
html::title(lg('Фотографии %s', $account['LOGIN']));
acms_header();  

if (!isset($account['ID'])){
  
  error('Такого пользователя не существует');
  redirect('/');

}

if (config('PRIVATE_PHOTOS') == 0){
  
  error('Модуль отключен администратором');
  redirect('/');
  
}

if ($account['ID'] != user('ID') && MANAGEMENT == 0){
  
  $private_show = " AND `PRIVATE` != '3'";

}else{
  
  $private_show = null;
  
}

?> 
<div class='menu-nav-content'>
  
<a class='menu-nav' href='/m/photos/?'>
<?=lg('Все')?>
</a>
    
<a class='menu-nav' href='/m/photos/?get=rating'>
<?=lg('ТОП')?>
</a>
    
<a class='menu-nav' href='/m/photos/?get=new'>
<?=lg('Новые')?>
</a>
  
<a class='menu-nav h' href='/m/photos/users/?id=<?=$account['ID']?>'>
<?=$account['LOGIN']?>
</a>
  
</div>
<?

$id_dir = intval(get('dir'));

if (get('dir') && db::get_column("SELECT COUNT(*) FROM `PHOTOS_DIR` WHERE `ID` = ? AND `USER_ID` = ? LIMIT 1", [$id_dir, $account['ID']]) > 0){
  
  $dir = db::get_string("SELECT * FROM `PHOTOS_DIR` WHERE `USER_ID` = ? AND `ID` = ? LIMIT 1", [$account['ID'], $id_dir]);
  $private = $dir['PRIVATE'];

}else{
  
  $id_dir = 0;
  $private = 0;

}

if ($id_dir > 0){
  
  ?>
  <div class='list'>
  <?=lg('Альбом')?> "<b><?=lg(tabs($dir['NAME']))?></b>"
  </div>
  <?
    
  if (access('photos', null) == false){
    
    require_once (ROOT.'/modules/photos/plugins/private.php');
  
  }  
    
}

$add_dl = intval(get('add_dl'));

if ($add_dl > 0) {
  
  $add_dl_url = '&add_dl='.$add_dl;
  
}else{
  
  $add_dl_url = null;
  
}

if ($add_dl > 0 && config('PRIVATE_DOWNLOADS') == 1) {
  
  ?>
  <div class='list'>
  <b><?=lg('Выберите файл и нажмите на него')?>:</b>
  </div>
  <?
  
}

if (access('photos', null) == true && $add_dl == 0 || $account['ID'] == user('ID') && $add_dl == 0){
  
  if ($id_dir > 0 && $private != 3){
    
    require_once (ROOT.'/modules/photos/plugins/delete_dir.php');
    
  }
  
  if ($id_dir > 0 && $private != 3 && $account['ID'] == user('ID') || $id_dir > 0 && $private != 3 && access('photos', null) == true || $id_dir == 0 && $account['ID'] == user('ID')){
    
    ?><div class='list'><?
      
  }
    
  if ($private != 3 && $account['ID'] == user('ID')){
    
    ?>
    <a href='/m/photos/add_dir/?dir=<?=$id_dir?>&<?=TOKEN_URL?>' class='btn'><?=icons('plus', 15, 'fa-fw')?> <?=lg('Создать альбом')?></a>
    <a ajax="no" id="modal_bottom_open_set" onclick="upload('/system/AJAX/php/photos_upload.php?dir=<?=$id_dir?>', 'attachments_upload')" class="btn"><?=icons('upload', 15, 'fa-fw')?> <?=lg('Добавить фото')?></a>  
    <?
    
  }
  
  if ($id_dir > 0 && $private != 3){
    
    ?>
    <a href='/m/photos/edit_dir/?dir=<?=$id_dir?>&<?=TOKEN_URL?>' class='btn'><?=icons('edit', 15, 'fa-fw')?> <?=lg('Редактировать альбом')?></a>
    <a href='/m/photos/users/?id=<?=$account['ID']?>&dir=<?=$id_dir?>&get=delete_dir&<?=TOKEN_URL?>' class='btn'><?=icons('times', 15, 'fa-fw')?> <?=lg('Удалить альбом')?></a>
    <?
    
  }
  
  if ($id_dir > 0 && $private != 3 && $account['ID'] == user('ID') || $id_dir > 0 && $private != 3 && access('photos', null) == true || $id_dir == 0 && $account['ID'] == user('ID')){
    
    ?></div><?
      
  }
    
  attachments_result();
    
}

?><div id='photos_upgrade'><?
  
$array = array();
$data = db::get_string_all("SELECT * FROM `PHOTOS_DIR` WHERE `USER_ID` = ? AND `ID_DIR` = ? ".$private_show." ORDER BY `ID` DESC", [$account['ID'], $id_dir]);
while ($list = $data->fetch()) {

  $array[] = array('dir' => 1, 'list' => $list);

}

$data = db::get_string_all("SELECT * FROM `PHOTOS` WHERE `USER_ID` = ? AND `ID_DIR` = ? ORDER BY `TIME` DESC", [$account['ID'], $id_dir]);
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
  
$trd = 0;
for ($i = $limit; $i < $column && $i < PAGE_SETTINGS * $page; $i++){
  
  $list = $array[$i]['list'];
  
  if ($array[$i]['dir'] == 1) {
    
    /*
    -------
    Альбомы
    -------
    */
    
    $count = db::get_column("SELECT COUNT(`ID`) FROM `PHOTOS` WHERE `ID_DIR` = ? AND `USER_ID` = ? LIMIT 1", [$list['ID'], $account['ID']]);
    
    if ($list['PRIVATE'] == 0){
      
      $fstatus = null;
      $istatus = "<small>".lg('Общий доступ')."</small>";       
    
    }elseif (str($list['PASSWORD']) > 0 && $list['PRIVATE'] == 4){
      
      $fstatus = "<div class='files_folder_status'>".icons('key', 20, 'fa-fw')."</div>";
      $istatus = "<small>".lg('По паролю')."</small>";
    
    }elseif ($list['PRIVATE'] == 1){
      
      $fstatus = "<div class='files_folder_status'>".icons('users', 20, 'fa-fw')."</div>";
      $istatus = "<small>".lg('Для друзей')."</small>";
    
    }elseif ($list['PRIVATE'] == 2){
      
      $fstatus = "<div class='files_folder_status'>".icons('lock', 20, 'fa-fw')."</div>";
      $istatus = "<small>".lg('Для')." ".$account['LOGIN']."</small>";
    
    }elseif ($list['PRIVATE'] == 3){
      
      $fstatus = "<div class='files_folder_status'>".icons('lock', 20, 'fa-fw')."</div>";
      $istatus = "<small>".lg('Закрытый')."</small>";
    
    }
    
    ?>      
    <a href='/m/photos/users/?id=<?=$account['ID']?>&dir=<?=$list['ID']?><?=$add_dl_url?>'>
    <div class='list-menu hover'>
    <div class='files_folder'>
    <?=$fstatus?>
    <font color='#D9D273'><?=icons('folder', 70)?></font>
    </div>
    <div class='files_folder_info'>
    <span class='files_folder_info_text'><?=crop_text(lg(tabs($list['NAME'])), 0, 15)?> (<?=$count?> <?=lg('фото')?>)</span>
    <?=$istatus?>
    </div>
    </div>
    </a>
    <?
  
  }else{
    
    /*
    ----------
    Фотографии
    ----------
    */
    
    $trd = 1;
    
    require (ROOT.'/modules/photos/plugins/list-mini.php');
    echo $photos_list_mini;
    
  }

}

if ($trd == 1){
  
  ?><br /><br /><?
  
}

if ($column > 0){

  ?></div><?
  
}

get_page('/m/photos/users/?id='.$account['ID'].'&dir='.$id_dir.'&', $spage, $page, 'list');

?></div><?

if ($id_dir == 0){
  
  back('/id'.$account['ID'], 'К странице');
  
}else{
  
  back('/m/photos/users/?id='.$account['ID'], 'Назад');
  
}  
  
acms_footer();