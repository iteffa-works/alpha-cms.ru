<!-- Левая панель --!>
  
<div class="sidebar_hide sidebar_hide_hidden" id="sidebar_hide"></div>
  
<div class="sidebar_wrap sidebar_wrap_hidden">

<div class='panel-left-top'>
<a href='/'>
<img src='/style/version/<?=version('DIR')?>/logo/<?=version('LOGO')?>' style='max-width: <?=version('LOGO_MAX')?>px'>
</a>  
<div id="sidebar_hide"><?=icons('times', 30)?></div>
</div>
  
<div class='pfm'>
  
<?php if (user('ID') > 0) { ?>  
  
<div class='panel-left-phone'>
<div class='message cabinet-phone'>
<div class='mess-circle1'></div> 
<div class='mess-circle2'></div>
</div>
</div>
 
<center style='margin-bottom: 10px'>
<div class='list-tr-avatar panel-left-avatar'>
<a href='/id<?=user('ID')?>'><?=user::avatar(user('ID'), 80)?></a>
</div>
<div class='panel-left-login'><?=user::login_mini(user('ID'))?></div>
  
<div class='panel-left-menu-nav'>
  
<div class='cabinet-menu'>
<a href='/account/cabinet/'><?=icons('th-large', 19)?></a>
<span><?=lg('Кабинет')?></span>
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
  
</div>  
</center>
  
<?php if (access('administration_show', null, 1) == true) { ?>
<a href='/admin/' ajax='no' class='panel-left-menu pfm_gray hover'>
<?=m_icons('gear', 12, '#2F454F', 0)?> <?=lg('Панель управления')?> <b>(<?=config('ACMS_VERSION')?>)</b>
</a> 
<?php } ?>
  
<a class='panel-left-menu pfm_gray hover' href='/id<?=user('ID')?>'><?=m_icons('user', 11, '#2F454F', 0)?> <span><?=lg('Моя страница')?></span></a> 
  
<?php }else{ ?> 
  
<div class='panel-left-menu pfm_name'>
<?=lg('Авторизация')?>
</div>
  
<div class='panel-left-menu' style='box-sizing: border-box; border-bottom: 1px #EBF2F7 solid'>
  
<form method='post' action='/login/'>
  
<?=html::input('login', 'Логин', null, config('REG_STR'), null, 'form-control-100', 'text', null, 'user')?>
<?=html::input('password', 'Пароль', null, 24, null, 'form-control-100', 'password', null, 'lock')?>
  
<?php
  
if (session('captcha') == 1 && url_request_validate('/login') == false && url_request_validate('/registration') == false && url_request_validate('/password') == false){
  
  html::captcha('Введите числа');
  
}

html::button('button', 'ok_aut', null, 'Войти');
  
?>
  
<br />
  
<a href='/password/' class='aut'><?=lg('Забыли пароль?')?></a>
<a href='/registration/' style='float: right;' class='aut'><?=lg('Регистрация')?></a>
  
</form>
  
</div>
  
<?php } ?>
  
<div class='panel-left-menu pfm_name'>
<?=lg('Разделы сайта')?>
</div>
  
<?=direct::components(ROOT.'/main/components/main_menu_small/', 0)?>

<button class='panel-left-menu pfm_gray hover' onclick="modal_center('version', 'open', '/system/AJAX/php/version.php', 'ver_upload')"><?=m_icons('desktop', 11, '#2F454F', 0)?> <span><?=lg('Версия')?>: <?=VERSION?></span></button>
  
<button class='panel-left-menu pfm_gray hover' onclick="modal_center('languages', 'open', '/system/AJAX/php/languages.php', 'lang_upload')"><?=m_icons('globe', 12, '#2F454F', 0)?> <span><?=lg('Язык')?>: <?=LANGUAGE?></span></button>
  
<div class='panel-left-menu'>
<center>
<span class='time'>
© <?=tabs(HTTP_HOST)?> - <?=date('Y')?>
</span>
</center>
</div>
  
<div class='panel-top-optimize3'></div>
  
</div>  
</div>