<?php
  
if (MANAGEMENT == 1){ 
  
  $url_menu = '/admin/site/online/';
  if (url_request_validate($url_menu) == true){ $menu_active = 'menu-active'; }else{ $menu_active = null; }
  
  ?>
  <li class="has-children <?=$menu_active?>">
  <span><?=icons('sliders', 17)?></span> <a href="<?=$url_menu?>"><?=lg('Режим онлайн')?></a>
  </li>
  <?
  
}