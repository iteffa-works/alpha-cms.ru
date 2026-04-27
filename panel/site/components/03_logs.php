<?php
  
$url_menu = '/admin/site/logs/';
if (url_request_validate($url_menu) == true){ $menu_active = 'menu-active'; }else{ $menu_active = null; }

?>
<li class="has-children <?=$menu_active?>">
<span><?=icons('binoculars', 17)?></span> <a href="<?=$url_menu?>"><?=lg('Логи администрации')?></a>
</li>
<?