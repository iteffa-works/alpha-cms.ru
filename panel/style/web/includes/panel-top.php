<header class="cd-main-header"><div style='position: relative; height: 55px;'>
  
<a href='/admin/desktop/' class="cd-logo"><span></span></a>
  
<div class="panel-top-optimize">    
<a href='/' ajax='no' class='panel-top-site-button'><?=icons('globe', 18, 'fa-fw')?> <?=lg('Сайт')?></a>    
<span class='panel-top-admin-button'><?=icons('gear', 18, 'fa-fw')?> <?=lg('Управление')?></span>      
</div>
  
<nav class="cd-nav"><ul class="cd-top-nav">

<li><a ajax="no" href="https://alpha-cms.ru/m/news/"><?=lg('Новости')?></a></li>
<li><a ajax="no" href="https://alpha-cms.ru/m/support/"><?=lg('Поддержка')?></a></li>
<li><a ajax="no" href="https://alpha-cms.ru/m/shop/"><?=lg('Магазин')?></a></li>

<li class="account" onclick="open_or_close('panel-top-modal')">
<button class='acimg'> 
<span class='acimg-span'><?=user::avatar(user('ID'), 28)?></span> 
<div><?=user('LOGIN')?></div>
<div id='panel-top-modal-c'><?=icons('angle-down', 20)?></div>
</button>
</li>

</ul></nav>
  
<div id='panel-top-modal' style='display: none;'>

<a href='/id<?=user('ID')?>' ajax='no'>
<?=icons('user', 20, 'fa-fw')?> <?=lg('Профиль')?>
</a>
  
<a href='/account/settings/' ajax='no'>
<?=icons('gear', 20, 'fa-fw')?> <?=lg('Настройки')?>
</a>
  
<a href='/exit/' ajax='no'>
<?=icons('power-off', 20, 'fa-fw')?> <?=lg('Выход')?>
</a>  

</div>
	
</div></header>