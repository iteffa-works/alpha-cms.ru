<?php if (access('management', null) == true){ ?>

<?php
$url_menu = '/admin/notifications/updates/';
if (url_request_validate($url_menu) == true){ $menu_active = 'menu-active'; }else{ $menu_active = null; }
?>

<li class="has-children <?=$menu_active?>">
<span><font color='#8EE7FF'><?=icons('flag', 18)?></font></span> <a href="<?=$url_menu?>"><font color='#8EE7FF'><?=lg('Центр обновлений')?></font> <span style=' position: absolute; right: 7px; bottom: 8px;'>  
<span class='count' style='background-color: #8EE7FF; color: black;'>0</span>
</span></a>
</li>
  
<?php } ?>