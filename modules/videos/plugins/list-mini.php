<?php
  
if (str($list['DURATION']) > 0) {
  
  $duration = "<span>".$list['DURATION']."</span>";
  
}else{
  
  $duration = null;
  
}
  
$video_list_mini = "
<a href='".(intval(get('add_dl')) == 0 ? '/m/videos/show/?id='.$list['ID'] : '/m/downloads/?id_file='.$list['ID'].'&type=videos&'.TOKEN_URL)."'>
<div class='list-menu hover'>      
<div class='videos-img'>
".$duration."  
<img src='/video/".$list['ID']."/?type=screen'>
</div>      
<div class='videos-info'>
<div>".crop_text(tabs($list['NAME']), 0, 40)."</div>
<br />
<span>".icons('eye', 16, 'fa-fw')." ".db::get_column("SELECT COUNT(`ID`) FROM `EYE` WHERE `OBJECT_ID` = ? AND `TYPE` = ? LIMIT 1", [$list['ID'], 'videos'])."</span>
<span>".icons('comment', 15, 'fa-fw')." ".db::get_column("SELECT COUNT(`ID`) FROM `COMMENTS` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ? LIMIT 1", [$list['ID'], 'videos_comments'])."</span>  
</div>
</div>
</a>
";