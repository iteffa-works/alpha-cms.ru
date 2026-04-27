<?php  
acms_header('Поднять рейтинг', 'users');
$rating = @parse_ini_file(ROOT."/system/config/rating.ini", false);
resources(config('TITLE'), $rating['MONEY'], 'за 1 ед. рейтинга');

if (post('ok_rt')){
  
  $rat1 = abs(intval(post('rating')));  
  $rat = $rating['MONEY'] * $rat1;
  
  if (user('MONEY') < $rat){
    
    error('Недостаточно денег на счету');
    redirect('/shopping/rating/');
  
  }
  
  db::get_set("UPDATE `USERS` SET `RATING` = ?, `MONEY` = ? WHERE `ID` = ? LIMIT 1", [(user('RATING') + $rat1), (user('MONEY') - $rat), user('ID')]);
  money_data(user('ID'), $rat, 0, lg('Покупка единиц рейтинга'), 2);
  
  success('Рейтинг успешно поднят');
  redirect('/shopping/rating/');

}

?>  
<div class='list-body'>
<div class='list-menu'>
<form method='post' class='ajax-form' action='/shopping/rating/'>
<b><?=lg('Введите количество единиц рейтинга')?>:</b><br />
<?=html::input('rating', 0, null, null, null, 'form-control-50', 'number', null, 'line-chart')?>
<?=html::button('button ajax-button', 'ok_rt', 'exchange', 'Обменять')?>
</form>
</div>  
<div class='list-menu'>
<?=lg('Текущий рейтинг')?>: <b><?=user('RATING')?></b><br />
<?=lg('На вашем счету')?>: <b><?=money(user('MONEY'), 2)?></b><br />
</div>
</div>
<?

back('/shopping/');
acms_footer();