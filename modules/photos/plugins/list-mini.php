<?php
  
$photos_list_mini = "  
<a href='".(intval(get('add_dl')) == 0 ? '/m/photos/show/?id='.$list['ID'] : '/m/downloads/?id_file='.$list['ID'].'&type=photos&'.TOKEN_URL)."'>
<div class='photos_list'>
".($list['ADULT'] == 1 ? '<b class="adult" style="position: absolute; bottom: 30px; left: 10px; z-index: 10">18+</b>' : null)."
<span style='z-index: 10'>".icons('image', 12, 'fa-fw')." ".tabs($list['EXT'])."</span>
<img src='/files/upload/photos/240x240/".$list['SHIF'].".jpg'".($list['ADULT'] == 1 ? " class='image_blur'" : null).">
<div>".crop_text(tabs($list['NAME']), 0, 16)."</div>
</div>
</a>
";