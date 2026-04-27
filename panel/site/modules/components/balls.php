<?php
$url_menu = '/admin/site/modules/?mod=balls';
if (url_request_validate($url_menu) == true){ $menu_active = 'list-menu-active'; }else{ $menu_active = null; }
?>

<a href='<?=$url_menu?>'><div class='list-menu hover <?=$menu_active?>'>
<?=icons('angle-double-right', 16, 'fa-fw')?> <?=lg('Баллы')?>
</div></a>