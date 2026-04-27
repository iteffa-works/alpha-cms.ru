<?php  
html::title('Языки');
acms_header();
access('management');
  
?>
<div class='navigation'>
<a href='/admin/desktop/'><?=icons('home', 25)?></a>
<?=icons('angle-right', 25, 'fa-fw')?>
<a href='/admin/system/'><?=lg('Настройки системы')?></a>
<?=icons('angle-right', 25, 'fa-fw')?>
<?=lg('Валюта сайта')?>
</div>  
<?
  
if (post('ok_money')){
  
  valid::create(array(
    
    'MONEY' => ['money', 'text', [0, 50], 'Валюта', 0],
    'MONEY_SET' => ['money_set', 'number', [0, 2], 'Формат валюты'],
    'MY_MONEY' => ['my_money', 'number_abs', [0, 9999999999999], 'Личный счет']
  
  ));
  
  if (ERROR_LOG == 1) {
    
    redirect('/admin/system/money/');
    
  }
  
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'MONEY', MONEY);
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'MONEY_SET', MONEY_SET);
  
  db::get_set("UPDATE `USERS` SET `MONEY` = ? WHERE `ID` = ? LIMIT 1", [MY_MONEY, user('ID')]);
  
  success('Изменения успешно приняты');
  redirect('/admin/system/money/');

}

?>
<div class='list-body6'>
<div class='list-menu'>
<?=lg('Ваш счет')?>: <b><?=money(user('MONEY'), 3)?></b>
</div>

<div class='list-menu'>
<form method='post' action='/admin/system/money/' class='ajax-form'>
  
<?=html::select('money', array(
  'RUB' => [lg('Российские рубли').' (RUB)', (config('MONEY') == 'RUB' ? "selected" : null)], 
  'BYN' => [lg('Белорусские рубли').' (BYN)', (config('MONEY') == 'BYN' ? "selected" : null)], 
  'UAH' => [lg('Украинские гривны').' (UAH)', (config('MONEY') == 'UAH' ? "selected" : null)], 
  'KZT' => [lg('Казахские тенге').' (KZT)', (config('MONEY') == 'KZT' ? "selected" : null)], 
  'UZS' => [lg('Узбекские сумы').' (UZS)', (config('MONEY') == 'UZS' ? "selected" : null)], 
  'AZN' => [lg('Азербайджанские манаты').' (AZN)', (config('MONEY') == 'AZN' ? "selected" : null)], 
  'GEL' => [lg('Грузинские лари').' (GEL)', (config('MONEY') == 'GEL' ? "selected" : null)], 
  'AMD' => [lg('Армянские драмы').' (AMD)', (config('MONEY') == 'AMD' ? "selected" : null)], 
  'TJS' => [lg('Таджикские сомони').' (TJS)', (config('MONEY') == 'TJS' ? "selected" : null)], 
  'KGS' => [lg('Киргизские сомы').' (KGS)', (config('MONEY') == 'KGS' ? "selected" : null)], 
  'TMT' => [lg('Туркменские манаты').' (TMT)', (config('MONEY') == 'TMT' ? "selected" : null)], 
  'MDL' => [lg('Молдавские леи').' (MDL)', (config('MONEY') == 'MDL' ? "selected" : null)], 
  'USD' => [lg('Доллары').' (USD)', (config('MONEY') == 'USD' ? "selected" : null)], 
  'EUR' => [lg('Евро').' (EUR)', (config('MONEY') == 'EUR' ? "selected" : null)],  
  'SLV' => [lg('Серебро').' (SLV)', (config('MONEY') == 'SLV' ? "selected" : null)], 
  'RBN' => [lg('Рубины').' (RBN)', (config('MONEY') == 'RBN' ? "selected" : null)], 
  'MON' => [lg('Монеты').' (MON)', (config('MONEY') == 'MON' ? "selected" : null)], 
  'VCS' => [lg('Голоса').' (VCS)', (config('MONEY') == 'VCS' ? "selected" : null)]
), 'Выберите валюту сайта', 'form-control-100-modify-select', 'money')?> 
  
<?=html::select('money_set', array(
  0 => ['Обычный', (config('MONEY_SET') == 0 ? "selected" : null)], 
  1 => ['Десятичный', (config('MONEY_SET') == 1 ? "selected" : null)]
), 'Формат валюты', 'form-control-100-modify-select', 'money')?>

<?=html::input('my_money', 'Мой счет', null, null, money(user('MONEY'), 0), 'form-control-30')?>
<?=html::button('button ajax-button', 'ok_money', 'save', 'Сохранить изменения')?>

</form>

</div>
</div>
<br />
<?
  
back('/admin/system/');
acms_footer();