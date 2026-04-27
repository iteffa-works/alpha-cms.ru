<?php  
acms_header('Магазин услуг', 'users');

?>
<div class='list-body'>
<div class='list-menu list-menu-title'><?=icons('money', 16, 'fa-fw')?> <?=lg('Ресурсы')?></div>  
<div class='list-menu'>
<font size='+1'><?=lg('Ваш счет')?>: <?=money(user('MONEY'), 3)?></font>
<br /><br />
<?php if (config('SHOP_REP') == 1) : ?>
<a href='/shopping/rep/' class='btn'><?=icons('plus', 15, 'fa-fw')?> <?=lg('Пополнить')?></a>
<?php endif ?>
<a href='/shopping/info/' class='btn'><?=icons('history', 15, 'fa-fw')?> <?=lg('История операций')?></a>
</div>
<?php if (config('BALLS') == 1) : ?>
<div class='list-menu'>
<font size='+1'><?=lg('Ваши баллы')?>: <?=user('BALLS')?></font><br /><br />
<a href='/shopping/exchange/' class='btn'><?=icons('refresh', 15, 'fa-fw')?> <?=lg('Обменять')?></a>
<?php if (MANAGEMENT == 1) : ?>
<a href='/admin/site/modules/?mod=balls' ajax='no' class='btn'><?=icons('gear', 15, 'fa-fw')?> <?=lg('Настройки')?></a>
<?php endif ?>
</div>
<?php endif ?>
</div>

<div class='list-body'> 
<div class='list-menu list-menu-title'><?=icons('bars', 16, 'fa-fw')?> <?=lg('Услуги')?></div>
<?=direct::components(ROOT.'/users/shop_service/components/')?>
</div>

<?php if (MANAGEMENT == 1) : ?>
<?=message('Сообщение для создателя сайта', lg('Вы также можете добавлять новые платные услуги для вашего проекта с официального магазина движка')." <a href='https://alpha-cms.ru' ajax='no'>Alpha-CMS.Ru</a>", 'shop')?>
<?php endif ?>
<?

back(user::url(user('ID')), 'К аккаунту');
forward('/account/cabinet/', 'В кабинет');
acms_footer();