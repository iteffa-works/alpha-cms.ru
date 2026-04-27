<!-- Левая панель --!> 
  
<div class="sidebar_hide sidebar_hide_hidden" id="sidebar_hide"></div>
  
<div class="sidebar_wrap sidebar_wrap_hidden">
  
<div class='logo'>
<img src='/panel/style/touch/images/logo.png'>
<div id="sidebar_hide"><?=icons('times', 25)?></div>
</div>
  
<div class="cd-side-nav">
<div class="cd-side-nav-optimize">

<ul>
<li class="cd-label"><?=lg('Уведомления')?></li>
<?=direct::components(ROOT.'/panel/notifications/components/', 0, 12)?>
</ul> 
  
<?php if (access('management', null) == true){ ?>
<ul>
<li class="cd-label"><?=lg('Управление системой')?></li>
<?=direct::components(ROOT.'/panel/system/components/', 0, 7)?>
<li class='has-children has-children-active'>
<span><?=icons('angle-right', 25)?></span><a href="/admin/system/"><?=lg('Все разделы')?></a>
</li>
</ul>
<?php } ?>

<ul>  
<li class="cd-label"><?=lg('Управление сайтом')?></li>  
<?=direct::components(ROOT.'/panel/site/components/', 0, 7)?>
<li class='has-children has-children-active'>
<span><?=icons('angle-right', 25)?></span><a href="/admin/site/"><?=lg('Все разделы')?></a>
</li>
</ul>

<ul>      
  
<li class='has-children has-children-active'>
<span><?=icons('desktop', 17)?></span> <button onclick="modal_center('version', 'open', '/system/AJAX/php/panel_version.php', 'ver_upload')"><?=lg('Версия панели')?>: <?=VERSION?></button>
</li>
<li class='has-children has-children-active'>
<span><?=icons('globe', 20)?></span> <button onclick="modal_center('languages', 'open', '/system/AJAX/php/languages.php', 'lang_upload')"><?=lg('Язык')?>: <?=LANGUAGE?></button>
</li>
</ul>

<br /><br /><br /><br />
  
</div>
</div>  
  
</div>