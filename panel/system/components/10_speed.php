<?php
$url_menu = '/admin/system/speed/';
if (url_request_validate($url_menu) == true){ $menu_active = 'menu-active'; }else{ $menu_active = null; }
?>

<li class="has-children <?=$menu_active?>">
<span><?=icons('dashboard', 18)?></span> <a href="<?=$url_menu?>"><?=lg('Генерация страниц')?></a>
</li>