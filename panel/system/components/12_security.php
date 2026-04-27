<?php
$url_menu = '/admin/system/security/';
if (url_request_validate($url_menu) == true){ $menu_active = 'menu-active'; }else{ $menu_active = null; }
?>

<li class="has-children <?=$menu_active?>">
<span><?=icons('lock', 20)?></span> <a href="<?=$url_menu?>"><?=lg('Доступ к панели')?></a>
</li>