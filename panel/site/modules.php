<?php  
html::title('Управление модулями');
acms_header();
access('management');
  
?>
<div class='navigation'>
<a href='/admin/desktop/'><?=icons('home', 25)?></a>
<?=icons('angle-right', 25, 'fa-fw')?>
<a href='/admin/site/'><?=lg('Настройки сайта')?></a>
<?=icons('angle-right', 25, 'fa-fw')?>
<?=lg('Управление модулями')?>
</div>
<?
  
/*
----------
WEB версия
----------
*/
  
if (VERSION == 'web'){
  
  ?><div class='list-optimize'><?

  $url_menu = '/admin/site/modules/?get=main';
  if (url_request_validate($url_menu) == true){ $menu_active = 'list-menu-active'; }else{ $menu_active = null; }
    
  ?>
  <a href='/admin/site/modules/?get=main'><div class='list-body4 hover <?=$menu_active?>'>
  <?=icons('gear', 16, 'fa-fw')?> <?=lg('Доступ к модулям')?>
  </div></a>
    
  <div class='list-body3'>
  <?=direct::components(ROOT.'/panel/site/modules/components/', 0)?>
  </div>
    
  </div>
  
  <div class='list-body2'>
  <?
    
  if (direct::e_file('panel/site/modules/'.direct::get('mod').'.php')){
    
    require (ROOT.'/panel/site/modules/'.direct::get('mod').'.php');
    
  }else{
    
    require (ROOT.'/panel/site/modules/index.php');
    
  }
  
  back('/admin/site/');
    
  ?></div><?
    
}

/*
------------
Touch версия
------------
*/

if (VERSION == 'touch'){
  
  $menu_active = null;
  
  if (get('mod')){
    
    if (direct::e_file('panel/site/modules/'.direct::get('mod').'.php')){
      
      require (ROOT.'/panel/site/modules/'.direct::get('mod').'.php');
    
    }else{
      
      require (ROOT.'/panel/site/modules/index.php');
    
    }
    
    back('/admin/site/modules/?get=main');
    acms_footer();
    
  }
  
  ?>
  <a href='/admin/site/modules/?get=main&mod=index'><div class='list hover'>
  <?=icons('gear', 16, 'fa-fw')?> <?=lg('Доступ к модулям')?>
  </div></a> 
  
  <div class='list-body'>
  <?=direct::components(ROOT.'/panel/site/modules/components/', 0)?>
  </div>
  <?
    
  back('/admin/site/');
    
}

acms_footer();