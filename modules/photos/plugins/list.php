<?php
  
$adult = db::get_column("SELECT `ADULT` FROM `PHOTOS` WHERE `ID` = ? LIMIT 1", [$list['ID']]);

$photos_list = '
<a href="/m/photos/show/?id='.$list['ID'].'">
<div class="list-menu hover">
<div class="files-info-list">
<div class="files-ext">
<img src="/files/upload/photos/240x240/'.$list['SHIF'].'.jpg" style="max-width: 60px" class="img '.($adult == 1 ? 'image_blur' : null).'">
</div>
<div class="files-info">
<b><font color="#484F54">'.crop_text(tabs($list['NAME']), 0, 25).'</font></b> '.($adult == 1 ? '<b class="adult">18+</b>' : null).'
<br /><br />
<span>'.icons('eye', 16, 'fa-fw').' '.db::get_column("SELECT COUNT(`ID`) FROM `EYE` WHERE `OBJECT_ID` = ? AND `TYPE` = ? LIMIT 1", [$list['ID'], 'photos']).'</span>
<span>'.icons('comment', 15, 'fa-fw').' '.db::get_column("SELECT COUNT(`ID`) FROM `COMMENTS` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ? LIMIT 1", [$list['ID'], 'photos_comments']).'</span>
</div>
</div>
</div>
</a>
';