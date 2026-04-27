<?php
  
if (MANAGEMENT == 1){ 
  
  $url_menu = '/admin/site/modules/';
  if (url_request_validate($url_menu) == true){ $menu_active = 'menu-active'; }else{ $menu_active = null; }
  
  ?>
  <li class="has-children <?=$menu_active?>">
  <span><?=icons('gear', 20)?></span> <a href="<?=$url_menu?>?get=main"><?=lg('Управление модулями')?></a>
  </li>
  <?
  
}