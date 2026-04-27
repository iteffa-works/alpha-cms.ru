<?php

  
$url_menu = '/admin/site/users/';
if (url_request_validate($url_menu) == true){ $menu_active = 'menu-active'; }else{ $menu_active = null; }
  
?>
<li class="has-children <?=$menu_active?>">
<span><?=icons('users', 17)?></span> <a href="<?=$url_menu?>"><?=lg('Члены администрации')?></a>
</li>
<?