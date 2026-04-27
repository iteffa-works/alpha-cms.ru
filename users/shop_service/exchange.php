<?php
acms_header('Обмен баллов на деньги', 'users');
$balls_ini = @parse_ini_file(ROOT."/system/config/balls.ini", false);
$balls = intval($balls_ini['EXCHANGE']);

if (config('BALLS') != 1) {
  
  error('Сервис временно недоступен');
  redirect('/shopping/');

}

if (post('ok_ex')){
  
  $money = abs(post('money')); 
  $balls_data = $money * $balls;
  
  if ($balls_data > user('BALLS')){
    
    error('У вас недостаточно баллов');
    redirect('/shopping/exchange/');
  
  }
  
  db::get_set("UPDATE `USERS` SET `BALLS` = ?, `MONEY` = ? WHERE `ID` = ? LIMIT 1", [(user('BALLS') - $balls_data), (user('MONEY') + $money), user('ID')]);
  money_data(user('ID'), $money, 1, lg('Обмен баллов на деньги'), 2);
  
  success('Обмен прошел успешно');
  redirect('/shopping/');

}

$balls_set = 0;
if (user('BALLS') >= $balls) { $balls_set = intval(user('BALLS') / $balls); }

?>
<div class='list'>
<center>
<?=icons('refresh', 60, 'fa-fw')?><br /><br />
<font size='+1'><?=lg('Вы можете обменять свои баллы на деньги и перевести их на счет аккаунта с помощью данного сервиса')?></font>
</center>
<br />
<b><?=lg('Предварительный расчет')?>:</b><br /><br />
<?=lg('Курс обмена')?>: <b><?=num_decline($balls, ['балл', 'балла', 'баллов'], 1)?> = <?=money(1, 2)?></b><br />
<?=lg('У Вас')?> <b><?=num_decline(user('BALLS'), ['балл', 'балла', 'баллов'], 1)?></b><br /><br />
<?php if ($balls_set == 0) : ?>
<font color='#FF4574'><?=lg('Сожалеем, но у вас недостаточно баллов для обмена')?></font>
<?php else : ?>
<font color='#2FCF81'><?=lg('Доступно для вывода')?> <b><?=money($balls_set, 2)?></b></font>
<?php endif ?>
<?php if ($balls_set != 0) : ?>
<br /><br />
<form method='post' class='ajax-form' action='/shopping/exchange/'>
<b><?=lg('Введите сумму денег, которую желаете получить за баллы')?>:</b><br />
<?=html::input('money', 0, null, null, $balls_set, 'form-control-50', 'number', null, 'money')?>
<?=html::button('button ajax-button', 'ok_ex', 'refresh', 'Обменять')?>
</form>
<?php endif ?>
</div>
<?

back('/shopping/');
forward('/account/cabinet/', 'В кабинет');
forward(user::url(user('ID')), 'На страницу');
acms_footer();