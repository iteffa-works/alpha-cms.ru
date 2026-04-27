<?php
$url_menu = '/admin/system/info/';
if (url_request_validate($url_menu) == true){ $menu_active = 'menu-active'; }else{ $menu_active = null; }
?>

<li class="has-children <?=$menu_active?>">
<span><?=icons('address-book-o', 20)?></span> <a href="<?=$url_menu?>"><?=lg('Руководитель')?></a>
</li>