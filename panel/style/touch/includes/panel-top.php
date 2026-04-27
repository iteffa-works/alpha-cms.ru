<div class="panel-top"> 
  
<div class="panel-top-menu" id="sidebar_show">
<?=icons('bars', 25)?>
</div>

<div class="panel-top-account" onclick="open_or_close('panel-top-modal')">
<span><?=user::avatar(user('ID'), 27)?></span> <b><?=user('LOGIN')?></b> <div id='panel-top-modal-c'><?=icons('angle-down', 20)?></div>
</div>
  
<div id='panel-top-modal' style='display: none;'>
  
<a href='/' ajax='no'>
<?=icons('globe', 20, 'fa-fw')?> <?=lg('На сайт')?>
</a>  

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
	
</div>