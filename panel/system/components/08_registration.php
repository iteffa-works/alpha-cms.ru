<?php
$url_menu = '/admin/system/registration/';
if (url_request_validate($url_menu) == true){ $menu_active = 'menu-active'; }else{ $menu_active = null; }
?>

<li class="has-children <?=$menu_active?>">
<span><?=icons('user-plus', 20)?></span> <a href="<?=$url_menu?>"><?=lg('Настройки регистрации')?></a>
</li>