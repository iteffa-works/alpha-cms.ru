<?php
$url_menu = '/admin/system/meta_tags/';
if (url_request_validate($url_menu) == true){ $menu_active = 'menu-active'; }else{ $menu_active = null; }
?>

<li class="has-children <?=$menu_active?>">
<span><?=icons('code', 20)?></span> <a href="<?=$url_menu?>"><?=lg('Мета теги')?></a>
</li>