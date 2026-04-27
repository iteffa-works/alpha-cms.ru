<?php  
acms_header('Пополнить счет', 'users');
is_active_module('SHOP_REP');

?>
<div class='list-body'>
<div class='list-menu'><b><?=lg('Выберите способ оплаты')?>:</b></div>  
<?=direct::components(ROOT.'/users/shop_service/components/rep/')?>
</div>
<?
  
if (MANAGEMENT == 1) {
  
  message('Сообщение для создателя сайта', lg('Вы можете подключить способы оплат с официального магазина движка')." <a href='https://alpha-cms.ru' ajax='no'>Alpha-CMS.Ru</a>", 'rep');

}
  
back('/shopping/');
forward('/account/cabinet/', 'В кабинет');
forward(user::url(user('ID')), 'На страницу');
acms_footer();