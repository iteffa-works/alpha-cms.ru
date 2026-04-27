<?php
$url_menu = '/admin/system/email/';
if (url_request_validate($url_menu) == true){ $menu_active = 'menu-active'; }else{ $menu_active = null; }
?>

<li class="has-children <?=$menu_active?>">
<span><?=icons('at', 20)?></span> <a href="<?=$url_menu?>"><?=lg('Настройки E-mail')?></a>
</li>