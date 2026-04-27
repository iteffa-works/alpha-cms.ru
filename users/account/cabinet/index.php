<?php  
acms_header('Кабинет', 'users');

if (direct::e_file('style/version/'.version('DIR').'/includes/cabinet.php') == true) {
  
  require (ROOT.'/style/version/'.version('DIR').'/includes/cabinet.php');
  acms_footer();
  
}

?>  
  
<div class='message cabinet-phone'>
<div class='mess-circle1'></div> 
<div class='mess-circle2'></div>
</div>  
  
<center>
<div class='list-tr cabinet-tr'>
<div class='list-tr-avatar cabinet-avatar'>
<a href='/id<?=user('ID')?>'><?=user::avatar(user('ID'), 100)?></a>
</div>
                              
<div class='cabinet-login'><?=user::login_mini(user('ID'))?></div>
                              
<div class='cabinet-menu'>
<a href='/id<?=user('ID')?>'><?=icons('user', 19)?></a>
<span><?=lg('Страница')?></span>
</div>

<div class='cabinet-menu'>
<a href='/shopping/'><?=icons('shopping-basket', 19)?></a>
<span><?=lg('Магазин')?></span>
</div>                              

<div class='cabinet-menu'>
<a href='/account/settings/'><?=icons('gear', 19)?></a>  
<span><?=lg('Настройки')?></span>                             
</div>

<div class='cabinet-menu'>
<a href='/exit/' ajax='no'><?=icons('power-off', 19)?></a> 
<span><?=lg('Выход')?></span>                             
</div>
                              
<div>
</center>
  
<?=hooks::challenge('cabinet_top', 'cabinet_top')?>
<?=hooks::run('cabinet_top')?>  
  
<div class="menu-container" style="margin-top: -2px">
<div class="menu-wrapper-center">
<?=direct::components(ROOT.'/users/account/cabinet/components/menu_big/', 0)?>
</div>
</div>
  
<?=hooks::challenge('cabinet_bottom', 'cabinet_bottom')?>
<?=hooks::run('cabinet_bottom')?>
                              
<?
                              
if (access('administration_show', null) == true) {
  
  ?>
  <a href='/admin/' ajax='no' style='color: #45545B'>
  <div class='list hover'>
  <?=m_icons('gear', 12, '#7B868A', 0)?> <?=lg('Панель управления')?> (<b><?=lg('Версия')?> AlphaCMS social: <?=config('ACMS_VERSION')?></b>)
  </div>
  </a>  
  <?
  
}

acms_footer();