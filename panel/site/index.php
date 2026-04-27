<?php  
html::title('Настройки сайта');
acms_header();
access('management');
  
?>
<div class='navigation'>
<a href='/admin/desktop/'><?=icons('home', 25)?></a>
<?=icons('angle-right', 25, 'fa-fw')?>
<?=lg('Настройки сайта')?>
</div>
  
<ul class='list-body6'>
<?
  
direct::components(ROOT.'/panel/site/components/', 0);

?></ul><br /><?

back('/admin/desktop/');
acms_footer();