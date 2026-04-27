<?php
$url_menu = '/admin/system/alpha_installer/';
if (url_request_validate($url_menu) == true){ $menu_active = 'menu-active'; }else{ $menu_active = null; }
?>

<li class="has-children <?=$menu_active?>">
<span><?=icons('code', 18)?></span> <a href="<?=$url_menu?>"><?=lg('Альфа установщик')?></a>
</li>