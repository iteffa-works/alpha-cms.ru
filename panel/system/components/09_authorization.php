<?php
$url_menu = '/admin/system/authorization/';
if (url_request_validate($url_menu) == true){ $menu_active = 'menu-active'; }else{ $menu_active = null; }
?>

<li class="has-children <?=$menu_active?>">
<span><?=icons('key', 20)?></span> <a href="<?=$url_menu?>"><?=lg('Настройки авторизации')?></a>
</li>