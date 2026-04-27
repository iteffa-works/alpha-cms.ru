<?php
$url_menu = '/admin/system/system_info/';
if (url_request_validate($url_menu) == true){ $menu_active = 'menu-active'; }else{ $menu_active = null; }
?>

<li class="has-children <?=$menu_active?>">
<span><?=icons('info-circle', 20)?></span> <a href="<?=$url_menu?>"><?=lg('О системе')?></a>
</li>