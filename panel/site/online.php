<?php  
html::title('Режим онлайн');
acms_header();
access('management');
  
?>
<div class='navigation'>
<a href='/admin/desktop/'><?=icons('home', 25)?></a>
<?=icons('angle-right', 25, 'fa-fw')?>
<a href='/admin/site/'><?=lg('Настройки сайта')?></a>
<?=icons('angle-right', 25, 'fa-fw')?>
<?=lg('Режим онлайн')?>
</div>
<?

if (post('ok_edit_online')){
  
  valid::create(array(
    
    'ONLINE_USERS' => ['users', 'number', [0, 9999999999], 'Пользователи'],
    'ONLINE_GUESTS' => ['guests', 'number', [0, 9999999999], 'Гости']
  
  ));
  
  if (ERROR_LOG == 1){
    
    redirect('/admin/site/online/');
  
  }
  
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'ONLINE_TIME_USERS', ONLINE_USERS);
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'ONLINE_TIME_GUESTS', ONLINE_GUESTS);
  
  success('Изменения успешно приняты');
  redirect('/admin/site/online/');

}
  
?>
<div class='list-body6'>
<div class='list-menu list-title'>
<?=lg('Редактировать режим онлайн')?>
</div>
<div class='list-menu'>
<form method='post' class='ajax-form' action='/admin/site/online/'>    
<?=html::select('users', array(
  0 => ['0 '.lg('минут'), 0], 
  60 => ['1 '.lg('минуту'), (config('ONLINE_TIME_USERS') == 60 ? "selected" : null)], 
  120 => ['2 '.lg('минуты'), (config('ONLINE_TIME_USERS') == 120 ? "selected" : null)],  
  180 => ['3 '.lg('минуты'), (config('ONLINE_TIME_USERS') == 180 ? "selected" : null)], 
  240 => ['4 '.lg('минуты'), (config('ONLINE_TIME_USERS') == 240 ? "selected" : null)], 
  300 => ['5 '.lg('минут'), (config('ONLINE_TIME_USERS') == 300 ? "selected" : null)], 
  360 => ['6 '.lg('минут'), (config('ONLINE_TIME_USERS') == 360 ? "selected" : null)], 
  420 => ['7 '.lg('минут'), (config('ONLINE_TIME_USERS') == 420 ? "selected" : null)], 
  480 => ['8 '.lg('минут'), (config('ONLINE_TIME_USERS') == 480 ? "selected" : null)],   
  540 => ['9 '.lg('минут'), (config('ONLINE_TIME_USERS') == 540 ? "selected" : null)], 
  600 => ['10 '.lg('минут'), (config('ONLINE_TIME_USERS') == 600 ? "selected" : null)], 
  660 => ['11 '.lg('минут'), (config('ONLINE_TIME_USERS') == 660 ? "selected" : null)], 
  720 => ['12 '.lg('минут'), (config('ONLINE_TIME_USERS') == 720 ? "selected" : null)], 
  780 => ['13 '.lg('минут'), (config('ONLINE_TIME_USERS') == 780 ? "selected" : null)], 
  840 => ['14 '.lg('минут'), (config('ONLINE_TIME_USERS') == 840 ? "selected" : null)], 
  900 => ['15 '.lg('минут'), (config('ONLINE_TIME_USERS') == 900 ? "selected" : null)],  
  960 => ['16 '.lg('минут'), (config('ONLINE_TIME_USERS') == 960 ? "selected" : null)], 
  1020 => ['17 '.lg('минут'), (config('ONLINE_TIME_USERS') == 1020 ? "selected" : null)], 
  1080 => ['18 '.lg('минут'), (config('ONLINE_TIME_USERS') == 1080 ? "selected" : null)], 
  1140 => ['19 '.lg('минут'), (config('ONLINE_TIME_USERS') == 1140 ? "selected" : null)], 
  1200 => ['20 '.lg('минут'), (config('ONLINE_TIME_USERS') == 1200 ? "selected" : null)], 
  1260 => ['21 '.lg('минута'), (config('ONLINE_TIME_USERS') == 1260 ? "selected" : null)], 
  1320 => ['22 '.lg('минуты'), (config('ONLINE_TIME_USERS') == 1320 ? "selected" : null)], 
  1380 => ['23 '.lg('минуты'), (config('ONLINE_TIME_USERS') == 1380 ? "selected" : null)], 
  1440 => ['24 '.lg('минуты'), (config('ONLINE_TIME_USERS') == 1440 ? "selected" : null)],  
  1500 => ['25 '.lg('минут'), (config('ONLINE_TIME_USERS') == 1500 ? "selected" : null)], 
  1800 => ['30 '.lg('минут'), (config('ONLINE_TIME_USERS') == 1800 ? "selected" : null)], 
  2100 => ['35 '.lg('минут'), (config('ONLINE_TIME_USERS') == 2100 ? "selected" : null)], 
  2400 => ['40 '.lg('минут'), (config('ONLINE_TIME_USERS') == 2400 ? "selected" : null)], 
  2700 => ['45 '.lg('минут'), (config('ONLINE_TIME_USERS') == 2700 ? "selected" : null)], 
  3000 => ['50 '.lg('минут'), (config('ONLINE_TIME_USERS') == 3000 ? "selected" : null)],  
  3300 => ['55 '.lg('минут'), (config('ONLINE_TIME_USERS') == 3300 ? "selected" : null)], 
  3600 => ['1 '.lg('час'), (config('ONLINE_TIME_USERS') == 3600 ? "selected" : null)], 
  7200 => ['2 '.lg('часа'), (config('ONLINE_TIME_USERS') == 7200 ? "selected" : null)]
), 'Интервал онлайна для пользователей', 'form-control-100-modify-select', 'user')?>
<?=html::select('guests', array(
  0 => ['0 '.lg('минут'), 0], 
  60 => ['1 '.lg('минуту'), (config('ONLINE_TIME_GUESTS') == 60 ? "selected" : null)], 
  120 => ['2 '.lg('минуты'), (config('ONLINE_TIME_GUESTS') == 120 ? "selected" : null)],  
  180 => ['3 '.lg('минуты'), (config('ONLINE_TIME_GUESTS') == 180 ? "selected" : null)], 
  240 => ['4 '.lg('минуты'), (config('ONLINE_TIME_GUESTS') == 240 ? "selected" : null)], 
  300 => ['5 '.lg('минут'), (config('ONLINE_TIME_GUESTS') == 300 ? "selected" : null)], 
  360 => ['6 '.lg('минут'), (config('ONLINE_TIME_GUESTS') == 360 ? "selected" : null)], 
  420 => ['7 '.lg('минут'), (config('ONLINE_TIME_GUESTS') == 420 ? "selected" : null)], 
  480 => ['8 '.lg('минут'), (config('ONLINE_TIME_GUESTS') == 480 ? "selected" : null)],   
  540 => ['9 '.lg('минут'), (config('ONLINE_TIME_GUESTS') == 540 ? "selected" : null)], 
  600 => ['10 '.lg('минут'), (config('ONLINE_TIME_GUESTS') == 600 ? "selected" : null)], 
  660 => ['11 '.lg('минут'), (config('ONLINE_TIME_GUESTS') == 660 ? "selected" : null)], 
  720 => ['12 '.lg('минут'), (config('ONLINE_TIME_GUESTS') == 720 ? "selected" : null)], 
  780 => ['13 '.lg('минут'), (config('ONLINE_TIME_GUESTS') == 780 ? "selected" : null)], 
  840 => ['14 '.lg('минут'), (config('ONLINE_TIME_GUESTS') == 840 ? "selected" : null)], 
  900 => ['15 '.lg('минут'), (config('ONLINE_TIME_GUESTS') == 900 ? "selected" : null)],  
  960 => ['16 '.lg('минут'), (config('ONLINE_TIME_GUESTS') == 960 ? "selected" : null)], 
  1020 => ['17 '.lg('минут'), (config('ONLINE_TIME_GUESTS') == 1020 ? "selected" : null)], 
  1080 => ['18 '.lg('минут'), (config('ONLINE_TIME_GUESTS') == 1080 ? "selected" : null)], 
  1140 => ['19 '.lg('минут'), (config('ONLINE_TIME_GUESTS') == 1140 ? "selected" : null)], 
  1200 => ['20 '.lg('минут'), (config('ONLINE_TIME_GUESTS') == 1200 ? "selected" : null)], 
  1260 => ['21 '.lg('минута'), (config('ONLINE_TIME_GUESTS') == 1260 ? "selected" : null)], 
  1320 => ['22 '.lg('минуты'), (config('ONLINE_TIME_GUESTS') == 1320 ? "selected" : null)], 
  1380 => ['23 '.lg('минуты'), (config('ONLINE_TIME_GUESTS') == 1380 ? "selected" : null)], 
  1440 => ['24 '.lg('минуты'), (config('ONLINE_TIME_GUESTS') == 1440 ? "selected" : null)],  
  1500 => ['25 '.lg('минут'), (config('ONLINE_TIME_GUESTS') == 1500 ? "selected" : null)], 
  1800 => ['30 '.lg('минут'), (config('ONLINE_TIME_GUESTS') == 1800 ? "selected" : null)], 
  2100 => ['35 '.lg('минут'), (config('ONLINE_TIME_GUESTS') == 2100 ? "selected" : null)], 
  2400 => ['40 '.lg('минут'), (config('ONLINE_TIME_GUESTS') == 2400 ? "selected" : null)], 
  2700 => ['45 '.lg('минут'), (config('ONLINE_TIME_GUESTS') == 2700 ? "selected" : null)], 
  3000 => ['50 '.lg('минут'), (config('ONLINE_TIME_GUESTS') == 3000 ? "selected" : null)],  
  3300 => ['55 '.lg('минут'), (config('ONLINE_TIME_GUESTS') == 3300 ? "selected" : null)], 
  3600 => ['1 '.lg('час'), (config('ONLINE_TIME_GUESTS') == 3600 ? "selected" : null)], 
  7200 => ['2 '.lg('часа'), (config('ONLINE_TIME_GUESTS') == 7200 ? "selected" : null)]
), 'Интервал онлайна для гостей', 'form-control-100-modify-select', 'user-secret')?>
<?=html::button('ajax-button button', 'ok_edit_online', 'plus', 'Сохранить')?>
<a class='button-o' href='/admin/site/'><?=lg('Отмена')?></a>
</form>
</div>
</div>
<br />
<?  

back('/admin/site/');
acms_footer();