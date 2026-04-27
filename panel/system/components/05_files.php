<?php
$url_menu = '/admin/system/files/';
if (url_request_validate($url_menu) == true){ $menu_active = 'menu-active'; }else{ $menu_active = null; }
?>

<li class="has-children <?=$menu_active?>">
<span><?=icons('file', 18)?></span> <a href="<?=$url_menu?>"><?=lg('Файловая среда')?></a>
</li>