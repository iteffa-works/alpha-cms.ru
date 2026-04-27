<?php
if (url_request_validate('/admin') == true){
  
  $ar = 'ajax="no"';

}else{
  
  $ar = null;

}  
?>

<div id='imgsh_phone' style='display: none'>
<a href="" class="img_name" <?=$ar?>></a>
<div class='imgsh_obj_optimize'>
<div>
<span class="imgsh_obj_close" onclick="img_show()"><i class='fa fa-times fa-lg'></i></span>
<div id='imgsh_obj'>
</div>
</div>
</div>
</div>