<?php
  
if (MANAGEMENT == 1){ 
  
  $url_menu = '/admin/site/access/';
  if (url_request_validate($url_menu) == true){ $menu_active = 'menu-active'; }else{ $menu_active = null; }
  
  ?>
  <li class="has-children <?=$menu_active?>">
  <span><?=icons('lock', 22)?></span> <a href="<?=$url_menu?>"><?=lg('Пользовательские права')?></a>
  </li>
  <?
  
}