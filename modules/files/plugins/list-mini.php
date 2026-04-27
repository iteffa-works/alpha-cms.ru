<?php
  
$screen = db::get_string("SELECT `ID` FROM `ATTACHMENTS` WHERE `ID_POST` = ? AND `TYPE_POST` = ? ORDER BY `TIME` DESC LIMIT 1", [$list['ID'], 'files_screen']);
if (isset($screen['ID'])) {
  
  $sc = '<img src="/files/upload/files/screen/'.$screen['ID'].'.jpg" style="max-width: 65px">';
  
}else{
  
  $sc = file::ext($list['EXT']);
  
}

$adult = db::get_column("SELECT `ADULT` FROM `FILES` WHERE `ID` = ? LIMIT 1", [$list['ID']]);

$files_list_mini = '
<a href="'.(intval(get('add_dl')) == 0 ? '/m/files/show/?id='.$list['ID'] : '/m/downloads/?id_file='.$list['ID'].'&type=files&'.TOKEN_URL).'">
<div class="list-menu hover">
<div class="files-info-list">
<div class="files-ext">
'.$sc.'
</div>
<div class="files-info">
<b><font color="#484F54">'.crop_text(tabs($list['NAME']), 0, 25).'</font></b> '.($adult == 1 ? '<b class="adult">18+</b>' : null).'
<br /><br />
<span>'.icons('eye', 16, 'fa-fw').' '.db::get_column("SELECT COUNT(`ID`) FROM `EYE` WHERE `OBJECT_ID` = ? AND `TYPE` = ? LIMIT 1", [$list['ID'], 'files']).'</span>
<span>'.icons('comment', 15, 'fa-fw').' '.db::get_column("SELECT COUNT(`ID`) FROM `COMMENTS` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ? LIMIT 1", [$list['ID'], 'files_comments']).'</span>
</div>
</div>
</div>
</a>
';