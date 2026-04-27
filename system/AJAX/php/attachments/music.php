<?php  
require ($_SERVER['DOCUMENT_ROOT'].'/system/connections/core.php');
access('users');
get_check_valid();

if (ajax() == true){

  $type = tabs(get('type'));
  $url = tabs(get('url'));
  $id = intval(get('id'));
  
  ?><div class="modal_bottom_title"><?
  
  if (config('PRIVATE_PHOTOS')){
    
    ?>
    <div id="modal_bottom_open_set" onclick="upload('/system/AJAX/php/attachments/photos.php?type=<?=$type?>&id=<?=$id?>&url=<?=$url?>&show=<?=intval(get('show'))?>&<?=TOKEN_URL?>', 'attachments_upload')" class="modal_bottom_icons_optimize"><span class="modal_bottom_icons_circle"><?=icons('image', 20)?></span><span class='modal_bottom_title_text'><?=lg('Фото')?></span></div>
    <?
    
  }
  
  if (config('PRIVATE_VIDEOS')){
    
    ?>
    <div id="modal_bottom_open_set" onclick="upload('/system/AJAX/php/attachments/videos.php?type=<?=$type?>&id=<?=$id?>&url=<?=$url?>&show=<?=intval(get('show'))?>&<?=TOKEN_URL?>', 'attachments_upload')" class="modal_bottom_icons_optimize"><span class="modal_bottom_icons_circle"><?=icons('film', 20)?></span><span class="modal_bottom_title_text"><?=lg('Видео')?></span></div>
    <?
    
  }
  
  if (config('PRIVATE_MUSIC')){
    
    ?>
    <div class="modal_bottom_icons_optimize modal_bottom_title_active"><span class="modal_bottom_icons_circle"><?=icons('music', 20)?></span><span class="modal_bottom_title_text"><?=lg('Музыка')?></span></div>
    <?
    
  }
  
  if (config('PRIVATE_FILES')){
    
    ?>
    <div id="modal_bottom_open_set" onclick="upload('/system/AJAX/php/attachments/files.php?type=<?=$type?>&id=<?=$id?>&url=<?=$url?>&show=<?=intval(get('show'))?>&<?=TOKEN_URL?>', 'attachments_upload')" class="modal_bottom_icons_optimize"><span class="modal_bottom_icons_circle"><?=icons('file', 20)?></span><span class="modal_bottom_title_text"><?=lg('Файлы')?></span></div>
    <?
    
  }
  
  hooks::challenge('attachments_ajax', 'attachments_ajax');
  hooks::run('attachments_ajax');
  
  ?></div><? 
  
  if (config('PRIVATE_MUSIC')){
    
    ?><div class="modal-bottom-container"><?
      
    file::upload('/files/receiver/attachments_music.php?type='.$type.'&id='.$id.'&url='.$url.'&show='.intval(get('show')));
    
    ?><div id="music_list"><?                                  
      
    $data = db::get_string_all("SELECT `ID`,`NAME`,`EXT`,`ARTIST` FROM `MUSIC` WHERE `USER_ID` = ? ORDER BY `TIME` DESC LIMIT 24", [user('ID')]);
    while ($list = $data->fetch()){
      
      if (config('MUSIC_SCREEN') == 1){
        
        if (is_file(ROOT.'/files/upload/music/screen/120x120/'.$list['ID'].'.jpg')){
          
          $img = '<img src="/music/'.$list['ID'].'/?type=screen" class="attachments-files-img">';
          
        }else{
          
          $img = file::ext($list['EXT']);

        }

      }else{
        
        $img = file::ext($list['EXT']);
        
      }
      
      ?><label onclick="checkbox(<?=$list['ID']?>)" class="checkbox-optimize2"><div class="list-menu"><span class="checkbox<?=$list['ID']?> checkbox-op-file check-close"><?=icons('check', 20)?></span><input type='checkbox' class='attachments-photos-checkbox' name='music' value='<?=$list['ID']?>' id='chset<?=$list['ID']?>'><?=$img?> <span class='attachments-files-name'><?=crop_text(tabs($list['ARTIST'].' - '.$list['NAME']), 0, 28)?></span></div></label><?
      
    }
    
    ?></div><?
    
    if (db::get_column("SELECT COUNT(*) FROM `MUSIC` WHERE `USER_ID` = ? LIMIT 1", [user('ID')]) > 24) {
      
      ?><br /><center><span onclick="show_more('/system/AJAX/php/attachments/music_list.php', '#show_more', '#music_list', 24, 'append')" class="button" id="show_more" count_show="24" count_add="24" name_show="<?=lg('Показать еще')?>" name_hide="<?=lg('Конец')?>"><?=lg('Показать еще')?></span></center><br /><? 
      
    }
    
    ?>
    </div>
      
    <center><span onclick='modal_bottom_close()' class='modal-bottom-button'><?=lg('Отменить')?></span> <span class="bclose modal-bottom-button-at" id="get_check" type="music" idt="<?=$id?>" ptype="<?=$type?>" action="<?=base64_decode($url)?>"><?=lg('Прикрепить')?></span></center>
      
    <script type="text/javascript" src="/system/AJAX/attachments/checkbox.js?version=<?=config('ACMS_VERSION')?>"></script> 
    <?
      
  }else{
    
    ?><div class='list-menu'><?
    echo lg('Модуль отключен администратором');
    ?></div><?
    
  }
  
}else{
  
  echo lg('Не удалось установить соединение');
  
}