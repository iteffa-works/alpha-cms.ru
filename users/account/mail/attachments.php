<?php  
$account = db::get_string("SELECT `ID`,`SEX`,`DATE_VISIT` FROM `USERS` WHERE `ID` = ? LIMIT 1", [intval(get('id'))]);   
acms_header(lg('Вложения переписки с %s', user::login_mini($account['ID'])), 'users');
get_check_valid();

if ($account['ID'] == user('ID')) {
  
  error('Нельзя писать самому себе');
  redirect('/account/mail/');
  
}

if (!isset($account['ID'])) {
  
  error('Пользователь не найден');
  redirect('/account/mail/');
  
}

if (get('get') == 'videos') {
  
  $root = 'videos';
  
}elseif (get('get') == 'music') {
  
  $root = 'music';
  
}elseif (get('get') == 'files') {
  
  $root = 'files';
  
}else{
  
  $root = 'photos';
  
}
  
?> 
<div class='menu-nav-content'>  
<a class='menu-nav <?=($root == 'photos' ? 'h' : null)?>' href='/account/mail/attachments/?id=<?=$account['ID']?>&<?=TOKEN_URL?>'>
<?=lg('Фото')?>
</a>    
<a class='menu-nav <?=($root == 'videos' ? 'h' : null)?>' href='/account/mail/attachments/?id=<?=$account['ID']?>&get=videos&<?=TOKEN_URL?>'>
<?=lg('Видео')?>
</a>    
<a class='menu-nav <?=($root == 'music' ? 'h' : null)?>' href='/account/mail/attachments/?id=<?=$account['ID']?>&get=music&<?=TOKEN_URL?>'>
<?=lg('Музыка')?>
</a>    
<a class='menu-nav <?=($root == 'files' ? 'h' : null)?>' href='/account/mail/attachments/?id=<?=$account['ID']?>&get=files&<?=TOKEN_URL?>'>
<?=lg('Файлы')?>
</a>  
</div>
<?
  
$column = db::get_column("SELECT COUNT(DISTINCT `ATTACHMENTS`.`ID`) AS `count` FROM `MAIL_MESSAGE` LEFT JOIN `ATTACHMENTS` ON (`MAIL_MESSAGE`.`TID` = `ATTACHMENTS`.`ID_POST`) WHERE (`MAIL_MESSAGE`.`USER_ID` = ? OR `MAIL_MESSAGE`.`MY_ID` = ?) AND (`MAIL_MESSAGE`.`USER_ID` = ? OR `MAIL_MESSAGE`.`MY_ID` = ?) AND `ATTACHMENTS`.`TYPE` = ? AND `ATTACHMENTS`.`TYPE_POST` = ? AND `MAIL_MESSAGE`.`USER` = ?", [user('ID'), user('ID'), $account['ID'], $account['ID'], $root, 'ok_message', user('ID')]);
$spage = spage($column, PAGE_SETTINGS);
$page = page($spage);
$limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;

if ($column == 0){ 
  
  html::empty('Пока пусто');
  
}else{
  
  ?><div class='list-body'><?
  
}
  
$data = db::get_string_all("SELECT `ATTACHMENTS`.`TYPE`,`ATTACHMENTS`.`OBJECT_ID` FROM `MAIL_MESSAGE` LEFT JOIN `ATTACHMENTS` ON (`MAIL_MESSAGE`.`TID` = `ATTACHMENTS`.`ID_POST`) WHERE (`MAIL_MESSAGE`.`USER_ID` = ? OR `MAIL_MESSAGE`.`MY_ID` = ?) AND (`MAIL_MESSAGE`.`USER_ID` = ? OR `MAIL_MESSAGE`.`MY_ID` = ?) AND `ATTACHMENTS`.`TYPE` = ? AND `ATTACHMENTS`.`TYPE_POST` = ? AND `MAIL_MESSAGE`.`USER` = ? GROUP BY `ATTACHMENTS`.`ID` ORDER BY `MAIL_MESSAGE`.`TIME` DESC LIMIT ".$limit.", ".PAGE_SETTINGS, [user('ID'), user('ID'), $account['ID'], $account['ID'], $root, 'ok_message', user('ID')]);
while ($list = $data->fetch()){
  
  if ($list['TYPE'] == 'photos') {
    
    $photo = db::get_string("SELECT `ID`,`SHIF`,`NAME` FROM `PHOTOS` WHERE `ID` = ? LIMIT 1", [$list['OBJECT_ID']]);
    
    if (isset($photo['ID'])) {
      
      ?>
      <a href="/m/photos/show/?id=<?=$photo['ID']?>">
      <div class="list-menu hover">
      <div class="files-info-list">
      <div class="files-ext">
      <img src="/files/upload/photos/150x150/<?=$photo['SHIF']?>.jpg" style="max-width: 60px" class="img">
      </div>
      <div class="files-info">
      <b><font color="#484F54"><?=crop_text(tabs($photo['NAME']), 0, 25)?></font></b>
      </div>
      </div>
      </div>
      </a>
      <?
        
    }else{
      
      ?>
      <div class="list-menu">
      <?=lg('Файл был удален')?>
      </div>
      <?
      
    }
    
  }
  
  if ($list['TYPE'] == 'videos') {
    
    $video = db::get_string("SELECT `NAME`,`ID`,`EXT` FROM `VIDEOS` WHERE `ID` = ? LIMIT 1", [$list['OBJECT_ID']]);
    
    if (isset($video['ID'])) {
      
      ?>
      <a href="/m/videos/show/?id=<?=$video['ID']?>">
      <div class="list-menu hover">
      <div class="files-info-list">
      <div class="files-ext">
      <?=file::ext($video['EXT'])?>
      </div>
      <div class="files-info">
      <b><font color="#484F54"><?=crop_text(tabs($video['NAME']), 0, 25)?></font></b>
      </div>
      </div>
      </div>
      </a>
      <?
        
    }else{
      
      ?>
      <div class="list-menu">
      <?=lg('Файл был удален')?>
      </div>
      <?
      
    }
    
  }
  
  if ($list['TYPE'] == 'music') {
    
    $music = db::get_string("SELECT `ARTIST`,`NAME`,`EXT`,`ID` FROM `MUSIC` WHERE `ID` = ? LIMIT 1", [$list['OBJECT_ID']]);
    
    if (isset($music['ID'])) {
      
      ?>
      <a href="/m/music/show/?id=<?=$music['ID']?>">
      <div class="list-menu hover">
      <div class="files-info-list">
      <div class="files-ext">
      <?=file::ext($music['EXT'])?>
      </div>
      <div class="files-info">
      <b><font color="#484F54"><?=crop_text(tabs($music['ARTIST']), 0, 25)?> - <?=crop_text(tabs($music['NAME']), 0, 25)?></font></b>
      </div>
      </div>
      </div>
      </a>
      <?
        
    }else{
      
      ?>
      <div class="list-menu">
      <?=lg('Файл был удален')?>
      </div>
      <?
      
    }
    
  }
  
  if ($list['TYPE'] == 'files') {
    
    $files = db::get_string("SELECT `NAME`,`EXT`,`ID` FROM `FILES` WHERE `ID` = ? LIMIT 1", [$list['OBJECT_ID']]);
    
    if (isset($files['ID'])) {
      
      ?>
      <a href="/m/files/show/?id=<?=$files['ID']?>">
      <div class="list-menu hover">
      <div class="files-info-list">
      <div class="files-ext">
      <?=file::ext($files['EXT'])?>
      </div>
      <div class="files-info">
      <b><font color="#484F54"><?=crop_text(tabs($files['NAME']), 0, 25)?></font></b>
      </div>
      </div>
      </div>
      </a>
      <?
        
    }else{
      
      ?>
      <div class="list-menu">
      <?=lg('Файл был удален')?>
      </div>
      <?
      
    }
    
  }

}

if ($column > 0){ 
  
  ?></div><?
  
}

get_page('/account/mail/attachments/?id='.$account['ID'].'&get='.$root.'&'.TOKEN_URL.'&', $spage, $page, 'list');

back('/account/mail/messages/?id='.$account['ID'].'&'.TOKEN_URL, lg('Назад к переписке с %s', user::login_mini($account['ID'])));
acms_footer();