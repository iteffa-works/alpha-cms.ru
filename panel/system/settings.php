<?php
acms_header('Общие настройки', 'management');
  
?>
<div class='navigation'>
<a href='/admin/desktop/'><?=icons('home', 25)?></a>
<?=icons('angle-right', 25, 'fa-fw')?>
<a href='/admin/system/'><?=lg('Настройки системы')?></a>
<?=icons('angle-right', 25, 'fa-fw')?>
<?=lg('Общие настройки')?>
</div>
  
<?php
  
if (get('get') == 'set_null_ok'){

  get_check_valid();
  
  $data = parse_ini_file(ROOT."/system/config/global/factory_set.ini", true);
  foreach ($data as $key => $factory_set) {
    
    ini::upgrade(ROOT.'/system/config/global/settings.ini', $key, ini_data_check($factory_set));
  
  }
  
  success('Настройки успешно сброшены');
  redirect('/admin/system/settings/');

}

if (get('get') == 'set_null'){

  get_check_valid();

  ?>
  <div class='list-body6'>
  <div class='list-menu'>
  <?=lg('Вы действительно хотите вернуться к заводским настройкам системы? Отменить действие будет невозможно')?><br /><br />
  <a href='/admin/system/settings/?get=set_null_ok&<?=TOKEN_URL?>' class='button2'><?=icons('gears', 15, 'fa-fw')?> <?=lg('Сбросить')?></a>
  <a href='/admin/system/settings/' class='button-o'><?=lg('Отмена')?></a>
  </div>
  </div><br />
  <?
    
  back('/admin/system/settings/');
  acms_footer();

}
  
?>
  
<div class='list-body6'>
  
<div class='list-menu list-title'>
<?=lg('Пользовательские настройки')?>
</div>
  
<?php
  
if (post('ok_save_users')){
  
  db_filter();
  post_check_valid();
 
  $str = intval(post('str'));
  $access = intval(post('access'));  
  $system = intval(post('system'));
  $cookie = intval(post('cookie'));
  $javascript = intval(post('javascript'));
  $ctj = intval(post('ctj'));
  
  if ($str < 1){
    
    error('Количество пунктов на страницу для гостей должно быть не менее 1');
    redirect('/admin/system/settings/');
    
  }
  
  if (db::get_column("SELECT COUNT(*) FROM `USERS` WHERE `ID` = ? LIMIT 1", [$system]) == 0){
    
    error('Такого пользователя не существует');
    redirect('/admin/system/settings/');
    
  }

  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'STR_GUESTS', $str);
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'ACCESS', $access);
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'SYSTEM', $system);
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'JAVASCRIPT', $javascript);
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'COOKIE', $cookie);
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'CTJ', $ctj);
  
  success('Изменения успешно приняты');
  redirect('/admin/system/settings/');

}  
?>

<div class='list-menu'>
<form method='post' class='ajax-form' action='/admin/system/settings/'>
  
<?=html::select('access', array(
  1 => ['Все', (config('ACCESS') == 1 ? "selected" : null)], 
  0 => ['Только пользователи', (config('ACCESS') == 0 ? "selected" : null)]
), 'Доступ к сайту', 'form-control-100-modify-select', 'lock')?> 
  
<?php $system = DB::GET_STRING("SELECT `ID`,`LOGIN` FROM `USERS` WHERE `ID` = ? LIMIT 1", [esc(config('SYSTEM'))]); ?>  
<?=html::input('system', 'ID', 'ID системы:', null, config('SYSTEM'), 'form-control-30')?>
<?=lg('Системой на сайте является пользователь с логином')?> <a ajax='no' href='/id<?=$system['ID']?>'><?=$system['LOGIN']?></a><br /><br />
  
<?=html::input('str', '0', 'Количество пунктов на страницу для гостей:', null, config('STR_GUESTS'), 'form-control-30', null, null, 'bars')?> 
<?=html::checkbox('javascript', 'Предупреждение пользователям об отсутствии поддержки JavaScript', 1, config('JAVASCRIPT'))?><br /><br />
<?=html::checkbox('cookie', 'Предупреждение пользователям об использовании файлов cookies', 1, config('COOKIE'))?><br /><br />
<?=html::checkbox('ctj', 'Закрыть сайт на тех.работы', 1, config('CTJ'))?><br /><br />  
  
<?=html::button('button ajax-button', 'ok_save_users', 'save', 'Сохранить изменения')?>
  
</form>
</div>
  
</div>
  
<div class='list-body6'>
  
<div class='list-menu list-title'>
<?=lg('Системные настройки')?>
</div>
  
<?php
if (post('ok_save_system')){
  
  db_filter();
  post_check_valid();
  
  $ajax_interval_set = intval(post('ajax_interval_set'));
  $ajax_interval = intval(post('ajax_interval'));  
  $ajax = intval(post('ajax'));    
  $int = intval(post('int'));
  $ajax_timeout = intval(post('ajax_timeout'));  
  $csrf = intval(post('csrf'));
  $db_interval = intval(post('db_interval'));
  $timezone = ini_data_check(post('timezone'));

  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'AJAX_INTERVAL_SET', $ajax_interval_set);
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'AJAX_INTERVAL', $ajax_interval);
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'AJAX', $ajax);
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'INTERPRETATOR', $int);
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'CSRF', $csrf);  
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'AJAX_TIMEOUT', $ajax_timeout);
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'TIMEZONE', $timezone);
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'DB_INTERVAL', $db_interval);
  
  success('Изменения успешно приняты');
  redirect('/admin/system/settings/');

}  
?>
  
<div class='list-menu'>
<form method='post' class='ajax-form2' action='/admin/system/settings/'>
  
<?=html::select('timezone', array(  
 'Africa/Abidjan' => ['Africa/Abidjan', (config('TIMEZONE') == 'Africa/Abidjan' ? "selected" : null)],
 'Africa/Asmara' => ['Africa/Asmara', (config('TIMEZONE') == 'Africa/Asmara' ? "selected" : null)],
 'Africa/Bissau' => ['Africa/Bissau', (config('TIMEZONE') == 'Africa/Bissau' ? "selected" : null)],
 'Africa/Cairo' => ['Africa/Cairo', (config('TIMEZONE') == 'Africa/Cairo' ? "selected" : null)],
 'Africa/Dakar' => ['Africa/Dakar', (config('TIMEZONE') == 'Africa/Dakar' ? "selected" : null)],
 'Africa/El_Aaiun' => ['Africa/El_Aaiun', (config('TIMEZONE') == 'Africa/El_Aaiun' ? "selected" : null)],
 'Africa/Johannesburg' => ['Africa/Johannesburg', (config('TIMEZONE') == 'Africa/Johannesburg' ? "selected" : null)],
 'Africa/Kigali' => ['Africa/Kigali', (config('TIMEZONE') == 'Africa/Kigali' ? "selected" : null)],
 'Africa/Lome' => ['Africa/Lome', (config('TIMEZONE') == 'Africa/Lome' ? "selected" : null)],
 'Africa/Malabo' => ['Africa/Malabo', (config('TIMEZONE') == 'Africa/Malabo' ? "selected" : null)],
 'Africa/Mogadishu' => ['Africa/Mogadishu', (config('TIMEZONE') == 'Africa/Mogadishu' ? "selected" : null)],
 'Africa/Niamey' => ['Africa/Niamey', (config('TIMEZONE') == 'Africa/Niamey' ? "selected" : null)],
 'Africa/Sao_Tome' => ['Africa/Sao_Tome', (config('TIMEZONE') == 'Africa/Sao_Tome' ? "selected" : null)],
 'Africa/Accra' => ['Africa/Accra', (config('TIMEZONE') == 'Africa/Accra' ? "selected" : null)],
 'Africa/Bamako' => ['Africa/Bamako', (config('TIMEZONE') == 'Africa/Bamako' ? "selected" : null)],
 'Africa/Blantyre' => ['Africa/Blantyre', (config('TIMEZONE') == 'Africa/Blantyre' ? "selected" : null)],
 'America/Adak' => ['America/Adak', (config('TIMEZONE') == 'America/Adak' ? "selected" : null)],
 'America/Araguaina' => ['America/Araguaina', (config('TIMEZONE') == 'America/Araguaina' ? "selected" : null)],
 'America/Argentina/Jujuy' => ['America/Argentina/Jujuy', (config('TIMEZONE') == 'America/Argentina/Jujuy' ? "selected" : null)],
 'America/Argentina/Salta' => ['America/Argentina/Salta', (config('TIMEZONE') == 'America/Argentina/Salta' ? "selected" : null)],
 'America/Argentina/Ushuaia' => ['America/Argentina/Ushuaia', (config('TIMEZONE') == 'America/Argentina/Ushuaia' ? "selected" : null)],
 'America/Bahia' => ['America/Bahia', (config('TIMEZONE') == 'America/Bahia' ? "selected" : null)],
 'America/Belize' => ['America/Belize', (config('TIMEZONE') == 'America/Belize' ? "selected" : null)],
 'America/Boise' => ['America/Boise', (config('TIMEZONE') == 'America/Boise' ? "selected" : null)],
 'America/Caracas' => ['America/Caracas', (config('TIMEZONE') == 'America/Caracas' ? "selected" : null)],
 'America/Chihuahua' => ['America/Chihuahua', (config('TIMEZONE') == 'America/Chihuahua' ? "selected" : null)],
 'America/Curacao' => ['America/Curacao', (config('TIMEZONE') == 'America/Curacao' ? "selected" : null)],
 'America/Denver' => ['America/Denver', (config('TIMEZONE') == 'America/Denver' ? "selected" : null)],
 'America/Eirunepe' => ['America/Eirunepe', (config('TIMEZONE') == 'America/Eirunepe' ? "selected" : null)],
 'America/Glace_Bay' => ['America/Glace_Bay', (config('TIMEZONE') == 'America/Glace_Bay' ? "selected" : null)],
 'America/Guadeloupe' => ['America/Guadeloupe', (config('TIMEZONE') == 'America/Guadeloupe' ? "selected" : null)],
 'America/Halifax' => ['America/Halifax', (config('TIMEZONE') == 'America/Halifax' ? "selected" : null)],
 'America/Indiana/Knox' => ['America/Indiana/Knox', (config('TIMEZONE') == 'America/Indiana/Knox' ? "selected" : null)],
 'America/Indiana/Vevay' => ['America/Indiana/Vevay', (config('TIMEZONE') == 'America/Indiana/Vevay' ? "selected" : null)],
 'America/Iqaluit' => ['America/Iqaluit', (config('TIMEZONE') == 'America/Iqaluit' ? "selected" : null)],
 'America/Kentucky/Monticello' => ['America/Kentucky/Monticello', (config('TIMEZONE') == 'America/Kentucky/Monticello' ? "selected" : null)],
 'America/Los_Angeles' => ['America/Los_Angeles', (config('TIMEZONE') == 'America/Los_Angeles' ? "selected" : null)],
 'Antarctica/Casey' => ['Antarctica/Casey', (config('TIMEZONE') == 'Antarctica/Casey' ? "selected" : null)],
 'Antarctica/Mawson' => ['Antarctica/Mawson', (config('TIMEZONE') == 'Antarctica/Mawson' ? "selected" : null)],
 'Antarctica/Syowa' => ['Antarctica/Syowa', (config('TIMEZONE') == 'Antarctica/Syowa' ? "selected" : null)],
 'Antarctica/Davis' => ['Antarctica/Davis', (config('TIMEZONE') == 'Antarctica/Davis' ? "selected" : null)],
 'Antarctica/McMurdo' => ['Antarctica/McMurdo', (config('TIMEZONE') == 'Antarctica/McMurdo' ? "selected" : null)],
 'Antarctica/Troll' => ['Antarctica/Troll', (config('TIMEZONE') == 'Antarctica/Troll' ? "selected" : null)],
 'Antarctica/DumontDUrville' => ['Antarctica/DumontDUrville', (config('TIMEZONE') == 'Antarctica/DumontDUrville' ? "selected" : null)],
 'Antarctica/Palmer' => ['Antarctica/Palmer', (config('TIMEZONE') == 'Antarctica/Palmer' ? "selected" : null)],
 'Antarctica/Vostok' => ['Antarctica/Vostok', (config('TIMEZONE') == 'Antarctica/Vostok' ? "selected" : null)],
 'Antarctica/Macquarie' => ['Antarctica/Macquarie', (config('TIMEZONE') == 'Antarctica/Macquarie' ? "selected" : null)],
 'Antarctica/Rothera' => ['Antarctica/Rothera', (config('TIMEZONE') == 'Antarctica/Rothera' ? "selected" : null)],
 'Arctic/Longyearbyen' => ['Arctic/Longyearbyen', (config('TIMEZONE') == 'Arctic/Longyearbyen' ? "selected" : null)],
 'Asia/Aden' => ['Asia/Aden', (config('TIMEZONE') == 'Asia/Aden' ? "selected" : null)],
 'Asia/Aqtau' => ['Asia/Aqtau', (config('TIMEZONE') == 'Asia/Aqtau' ? "selected" : null)],
 'Asia/Baghdad' => ['Asia/Baghdad', (config('TIMEZONE') == 'Asia/Baghdad' ? "selected" : null)],
 'Asia/Barnaul' => ['Asia/Barnaul', (config('TIMEZONE') == 'Asia/Barnaul' ? "selected" : null)],
 'Asia/Chita' => ['Asia/Chita', (config('TIMEZONE') == 'Asia/Chita' ? "selected" : null)],
 'Asia/Dhaka' => ['Asia/Dhaka', (config('TIMEZONE') == 'Asia/Dhaka' ? "selected" : null)],
 'Asia/Famagusta' => ['Asia/Famagusta', (config('TIMEZONE') == 'Asia/Famagusta' ? "selected" : null)],
 'Asia/Hong_Kong' => ['Asia/Hong_Kong', (config('TIMEZONE') == 'Asia/Hong_Kong' ? "selected" : null)],
 'Asia/Jayapura' => ['Asia/Jayapura', (config('TIMEZONE') == 'Asia/Jayapura' ? "selected" : null)],
 'Asia/Karachi' => ['Asia/Karachi', (config('TIMEZONE') == 'Asia/Karachi' ? "selected" : null)],
 'Asia/Krasnoyarsk' => ['Asia/Krasnoyarsk', (config('TIMEZONE') == 'Asia/Krasnoyarsk' ? "selected" : null)],
 'Asia/Macau' => ['Asia/Macau', (config('TIMEZONE') == 'Asia/Macau' ? "selected" : null)],
 'Asia/Muscat' => ['Asia/Muscat', (config('TIMEZONE') == 'Asia/Muscat' ? "selected" : null)],
 'Asia/Omsk' => ['Asia/Omsk', (config('TIMEZONE') == 'Asia/Omsk' ? "selected" : null)],
 'Asia/Pyongyang' => ['Asia/Pyongyang', (config('TIMEZONE') == 'Asia/Pyongyang' ? "selected" : null)],
 'Asia/Riyadh' => ['Asia/Riyadh', (config('TIMEZONE') == 'Asia/Riyadh' ? "selected" : null)],
 'Asia/Shanghai' => ['Asia/Shanghai', (config('TIMEZONE') == 'Asia/Shanghai' ? "selected" : null)],
 'Asia/Tashkent' => ['Asia/Tashkent', (config('TIMEZONE') == 'Asia/Tashkent' ? "selected" : null)],
 'Asia/Tokyo' => ['Asia/Tokyo', (config('TIMEZONE') == 'Asia/Tokyo' ? "selected" : null)],
 'Asia/Ust-Nera' => ['Asia/Ust-Nera', (config('TIMEZONE') == 'Asia/Ust-Nera' ? "selected" : null)],
 'Asia/Yangon' => ['Asia/Yangon', (config('TIMEZONE') == 'Asia/Yangon' ? "selected" : null)],
 'Asia/Almaty' => ['Asia/Almaty', (config('TIMEZONE') == 'Asia/Almaty' ? "selected" : null)],
 'Asia/Aqtobe' => ['Asia/Aqtobe', (config('TIMEZONE') == 'Asia/Aqtobe' ? "selected" : null)],
 'Asia/Bahrain' => ['Asia/Bahrain', (config('TIMEZONE') == 'Asia/Bahrain' ? "selected" : null)],
 'Asia/Beirut' => ['Asia/Beirut', (config('TIMEZONE') == 'Asia/Beirut' ? "selected" : null)],
 'Asia/Choibalsan' => ['Asia/Choibalsan', (config('TIMEZONE') == 'Asia/Choibalsan' ? "selected" : null)],
 'Asia/Dili' => ['Asia/Dili', (config('TIMEZONE') == 'Asia/Dili' ? "selected" : null)],
 'Asia/Gaza' => ['Asia/Gaza', (config('TIMEZONE') == 'Asia/Gaza' ? "selected" : null)],
 'Asia/Hovd' => ['Asia/Hovd', (config('TIMEZONE') == 'Asia/Hovd' ? "selected" : null)],
 'Asia/Jerusalem' => ['Asia/Jerusalem', (config('TIMEZONE') == 'Asia/Jerusalem' ? "selected" : null)],
 'Asia/Kathmandu' => ['Asia/Kathmandu', (config('TIMEZONE') == 'Asia/Kathmandu' ? "selected" : null)],
 'Asia/Kuala_Lumpur' => ['Asia/Kuala_Lumpur', (config('TIMEZONE') == 'Asia/Kuala_Lumpur' ? "selected" : null)],
 'Asia/Magadan' => ['Asia/Magadan', (config('TIMEZONE') == 'Asia/Magadan' ? "selected" : null)],
 'Asia/Nicosia' => ['Asia/Nicosia', (config('TIMEZONE') == 'Asia/Nicosia' ? "selected" : null)],
 'Asia/Oral' => ['Asia/Oral', (config('TIMEZONE') == 'Asia/Oral' ? "selected" : null)],
 'Asia/Qatar' => ['Asia/Qatar', (config('TIMEZONE') == 'Asia/Qatar' ? "selected" : null)],
 'Asia/Sakhalin' => ['Asia/Sakhalin', (config('TIMEZONE') == 'Asia/Sakhalin' ? "selected" : null)],
 'Asia/Singapore' => ['Asia/Singapore', (config('TIMEZONE') == 'Asia/Singapore' ? "selected" : null)],
 'Asia/Tbilisi' => ['Asia/Tbilisi', (config('TIMEZONE') == 'Asia/Tbilisi' ? "selected" : null)],
 'Asia/Tel_Aviv' => ['Asia/Tel_Aviv', (config('TIMEZONE') == 'Asia/Tel_Aviv' ? "selected" : null)], 
 'Asia/Tomsk' => ['Asia/Tomsk', (config('TIMEZONE') == 'Asia/Tomsk' ? "selected" : null)],
 'Asia/Vientiane' => ['Asia/Vientiane', (config('TIMEZONE') == 'Asia/Vientiane' ? "selected" : null)],
 'Asia/Yekaterinburg' => ['Asia/Yekaterinburg', (config('TIMEZONE') == 'Asia/Yekaterinburg' ? "selected" : null)],
 'Asia/Amman' => ['Asia/Amman', (config('TIMEZONE') == 'Asia/Amman' ? "selected" : null)],
 'Asia/Ashgabat' => ['Asia/Ashgabat', (config('TIMEZONE') == 'Asia/Ashgabat' ? "selected" : null)],
 'Asia/Baku' => ['Asia/Baku', (config('TIMEZONE') == 'Asia/Baku' ? "selected" : null)],
 'Asia/Bishkek' => ['Asia/Bishkek', (config('TIMEZONE') == 'Asia/Bishkek' ? "selected" : null)],
 'Asia/Colombo' => ['Asia/Colombo', (config('TIMEZONE') == 'Asia/Colombo' ? "selected" : null)],
 'Asia/Dubai' => ['Asia/Dubai', (config('TIMEZONE') == 'Asia/Dubai' ? "selected" : null)],
 'Asia/Hebron' => ['Asia/Hebron', (config('TIMEZONE') == 'Asia/Hebron' ? "selected" : null)],
 'Asia/Irkutsk' => ['Asia/Irkutsk', (config('TIMEZONE') == 'Asia/Irkutsk' ? "selected" : null)],
 'Asia/Kabul' => ['Asia/Kabul', (config('TIMEZONE') == 'Asia/Kabul' ? "selected" : null)],
 'Asia/Khandyga' => ['Asia/Khandyga', (config('TIMEZONE') == 'Asia/Khandyga' ? "selected" : null)],
 'Asia/Kuching' => ['Asia/Kuching', (config('TIMEZONE') == 'Asia/Kuching' ? "selected" : null)],
 'Asia/Makassar' => ['Asia/Makassar', (config('TIMEZONE') == 'Asia/Makassar' ? "selected" : null)],
 'Asia/Novokuznetsk' => ['Asia/Novokuznetsk', (config('TIMEZONE') == 'Asia/Novokuznetsk' ? "selected" : null)],
 'Asia/Phnom_Penh' => ['Asia/Phnom_Penh', (config('TIMEZONE') == 'Asia/Phnom_Penh' ? "selected" : null)],
 'Asia/Qostanay' => ['Asia/Qostanay', (config('TIMEZONE') == 'Asia/Qostanay' ? "selected" : null)],
 'Asia/Samarkand' => ['Asia/Samarkand', (config('TIMEZONE') == 'Asia/Samarkand' ? "selected" : null)],
 'Asia/Srednekolymsk' => ['Asia/Srednekolymsk', (config('TIMEZONE') == 'Asia/Srednekolymsk' ? "selected" : null)],
 'Asia/Tehran' => ['Asia/Tehran', (config('TIMEZONE') == 'Asia/Tehran' ? "selected" : null)],
 'Asia/Ulaanbaatar' => ['Asia/Ulaanbaatar', (config('TIMEZONE') == 'Asia/Ulaanbaatar' ? "selected" : null)],
 'Asia/Vladivostok' => ['Asia/Vladivostok', (config('TIMEZONE') == 'Asia/Vladivostok' ? "selected" : null)],
 'Asia/Yerevan' => ['Asia/Yerevan', (config('TIMEZONE') == 'Asia/Yerevan' ? "selected" : null)],
 'Asia/Anadyr' => ['Asia/Anadyr', (config('TIMEZONE') == 'Asia/Anadyr' ? "selected" : null)],
 'Asia/Atyrau' => ['Asia/Atyrau', (config('TIMEZONE') == 'Asia/Atyrau' ? "selected" : null)],
 'Asia/Bangkok' => ['Asia/Bangkok', (config('TIMEZONE') == 'Asia/Bangkok' ? "selected" : null)],
 'Asia/Brunei' => ['Asia/Brunei', (config('TIMEZONE') == 'Asia/Brunei' ? "selected" : null)],
 'Asia/Damascus' => ['Asia/Damascus', (config('TIMEZONE') == 'Asia/Damascus' ? "selected" : null)],
 'Asia/Dushanbe' => ['Asia/Dushanbe', (config('TIMEZONE') == 'Asia/Dushanbe' ? "selected" : null)],
 'Asia/Ho_Chi_Minh' => ['Asia/Ho_Chi_Minh', (config('TIMEZONE') == 'Asia/Ho_Chi_Minh' ? "selected" : null)],
 'Asia/Jakarta' => ['Asia/Jakarta', (config('TIMEZONE') == 'Asia/Jakarta' ? "selected" : null)],
 'Asia/Kamchatka' => ['Asia/Kamchatka', (config('TIMEZONE') == 'Asia/Kamchatka' ? "selected" : null)],
 'Asia/Kolkata' => ['Asia/Kolkata', (config('TIMEZONE') == 'Asia/Kolkata' ? "selected" : null)],
 'Asia/Kuwait' => ['Asia/Kuwait', (config('TIMEZONE') == 'Asia/Kuwait' ? "selected" : null)],
 'Asia/Manila' => ['Asia/Manila', (config('TIMEZONE') == 'Asia/Manila' ? "selected" : null)],
 'Asia/Novosibirsk' => ['Asia/Novosibirsk', (config('TIMEZONE') == 'Asia/Novosibirsk' ? "selected" : null)],
 'Asia/Pontianak' => ['Asia/Pontianak', (config('TIMEZONE') == 'Asia/Pontianak' ? "selected" : null)],
 'Asia/Qyzylorda' => ['Asia/Qyzylorda', (config('TIMEZONE') == 'Asia/Qyzylorda' ? "selected" : null)],
 'Asia/Seoul' => ['Asia/Seoul', (config('TIMEZONE') == 'Asia/Seoul' ? "selected" : null)],
 'Asia/Taipei' => ['Asia/Taipei', (config('TIMEZONE') == 'Asia/Taipei' ? "selected" : null)],
 'Asia/Thimphu' => ['Asia/Thimphu', (config('TIMEZONE') == 'Asia/Thimphu' ? "selected" : null)],
 'Asia/Urumqi' => ['Asia/Urumqi', (config('TIMEZONE') == 'Asia/Urumqi' ? "selected" : null)],
 'Asia/Yakutsk' => ['Asia/Yakutsk', (config('TIMEZONE') == 'Asia/Yakutsk' ? "selected" : null)],
 'Atlantic/Azores' => ['Atlantic/Azores', (config('TIMEZONE') == 'Atlantic/Azores' ? "selected" : null)],
 'Atlantic/Faroe' => ['Atlantic/Faroe', (config('TIMEZONE') == 'Atlantic/Faroe' ? "selected" : null)],
 'Atlantic/St_Helena' => ['Atlantic/St_Helena', (config('TIMEZONE') == 'Atlantic/St_Helena' ? "selected" : null)],
 'Atlantic/Bermuda' => ['Atlantic/Bermuda', (config('TIMEZONE') == 'Atlantic/Bermuda' ? "selected" : null)],
 'Atlantic/Madeira' => ['Atlantic/Madeira', (config('TIMEZONE') == 'Atlantic/Madeira' ? "selected" : null)],
 'Atlantic/Stanley' => ['Atlantic/Stanley', (config('TIMEZONE') == 'Atlantic/Stanley' ? "selected" : null)],
 'Atlantic/Canary' => ['Atlantic/Canary', (config('TIMEZONE') == 'Atlantic/Canary' ? "selected" : null)],
 'Atlantic/Reykjavik' => ['Atlantic/Reykjavik', (config('TIMEZONE') == 'Atlantic/Reykjavik' ? "selected" : null)],
 'Atlantic/Cape_Verde' => ['Atlantic/Cape_Verde', (config('TIMEZONE') == 'Atlantic/Cape_Verde' ? "selected" : null)],
 'Atlantic/South_Georgia' => ['Atlantic/South_Georgia', (config('TIMEZONE') == 'Atlantic/South_Georgia' ? "selected" : null)],
 'Australia/Adelaide' => ['Australia/Adelaide', (config('TIMEZONE') == 'Australia/Adelaide' ? "selected" : null)],
 'Australia/Darwin' => ['Australia/Darwin', (config('TIMEZONE') == 'Australia/Darwin' ? "selected" : null)],
 'Australia/Lord_Howe' => ['Australia/Lord_Howe', (config('TIMEZONE') == 'Australia/Lord_Howe' ? "selected" : null)],
 'Australia/Brisbane' => ['Australia/Brisbane', (config('TIMEZONE') == 'Australia/Brisbane' ? "selected" : null)],
 'Australia/Eucla' => ['Australia/Eucla', (config('TIMEZONE') == 'Australia/Eucla' ? "selected" : null)],
 'Australia/Melbourne' => ['Australia/Melbourne', (config('TIMEZONE') == 'Australia/Melbourne' ? "selected" : null)],
 'Australia/Broken_Hill' => ['Australia/Broken_Hill', (config('TIMEZONE') == 'Australia/Broken_Hill' ? "selected" : null)],
 'Australia/Hobart' => ['Australia/Hobart', (config('TIMEZONE') == 'Australia/Hobart' ? "selected" : null)],
 'Australia/Perth' => ['Australia/Perth', (config('TIMEZONE') == 'Australia/Perth' ? "selected" : null)],
 'Australia/Currie' => ['Australia/Currie', (config('TIMEZONE') == 'Australia/Currie' ? "selected" : null)],
 'Australia/Lindeman' => ['Australia/Lindeman', (config('TIMEZONE') == 'Australia/Lindeman' ? "selected" : null)],
 'Australia/Sydney' => ['Australia/Sydney', (config('TIMEZONE') == 'Australia/Sydney' ? "selected" : null)],
 'Europe/Amsterdam' => ['Europe/Amsterdam', (config('TIMEZONE') == 'Europe/Amsterdam' ? "selected" : null)],
 'Europe/Belgrade' => ['Europe/Belgrade', (config('TIMEZONE') == 'Europe/Belgrade' ? "selected" : null)],
 'Europe/Bucharest' => ['Europe/Bucharest', (config('TIMEZONE') == 'Europe/Bucharest' ? "selected" : null)],
 'Europe/Copenhagen' => ['Europe/Copenhagen', (config('TIMEZONE') == 'Europe/Copenhagen' ? "selected" : null)],
 'Europe/Helsinki' => ['Europe/Helsinki', (config('TIMEZONE') == 'Europe/Helsinki' ? "selected" : null)],
 'Europe/Kaliningrad' => ['Europe/Kaliningrad', (config('TIMEZONE') == 'Europe/Kaliningrad' ? "selected" : null)],
 'Europe/Ljubljana' => ['Europe/Ljubljana', (config('TIMEZONE') == 'Europe/Ljubljana' ? "selected" : null)],
 'Europe/Malta' => ['Europe/Malta', (config('TIMEZONE') == 'Europe/Malta' ? "selected" : null)],
 'Europe/Moscow' => ['Europe/Moscow', (config('TIMEZONE') == 'Europe/Moscow' ? "selected" : null)],
 'Europe/Prague' => ['Europe/Prague', (config('TIMEZONE') == 'Europe/Prague' ? "selected" : null)],
 'Europe/San_Marino' => ['Europe/San_Marino', (config('TIMEZONE') == 'Europe/San_Marino' ? "selected" : null)],
 'Europe/Skopje' => ['Europe/Skopje', (config('TIMEZONE') == 'Europe/Skopje' ? "selected" : null)],
 'Europe/Tirane' => ['Europe/Tirane', (config('TIMEZONE') == 'Europe/Tirane' ? "selected" : null)],
 'Europe/Vatican' => ['Europe/Vatican', (config('TIMEZONE') == 'Europe/Vatican' ? "selected" : null)],
 'Europe/Warsaw' => ['Europe/Warsaw', (config('TIMEZONE') == 'Europe/Warsaw' ? "selected" : null)],
 'Europe/Andorra' => ['Europe/Andorra', (config('TIMEZONE') == 'Europe/Andorra' ? "selected" : null)],
 'Europe/Berlin' => ['Europe/Berlin', (config('TIMEZONE') == 'Europe/Berlin' ? "selected" : null)],
 'Europe/Budapest' => ['Europe/Budapest', (config('TIMEZONE') == 'Europe/Budapest' ? "selected" : null)],
 'Europe/Dublin' => ['Europe/Dublin', (config('TIMEZONE') == 'Europe/Dublin' ? "selected" : null)],
 'Europe/Isle_of_Man' => ['Europe/Isle_of_Man', (config('TIMEZONE') == 'Europe/Isle_of_Man' ? "selected" : null)],
 'Europe/Kiev' => ['Europe/Kiev', (config('TIMEZONE') == 'Europe/Kiev' ? "selected" : null)],
 'Europe/London' => ['Europe/London', (config('TIMEZONE') == 'Europe/London' ? "selected" : null)],
 'Europe/Mariehamn' => ['Europe/Mariehamn', (config('TIMEZONE') == 'Europe/Mariehamn' ? "selected" : null)],
 'Europe/Oslo' => ['Europe/Oslo', (config('TIMEZONE') == 'Europe/Oslo' ? "selected" : null)],
 'Europe/Riga' => ['Europe/Riga', (config('TIMEZONE') == 'Europe/Riga' ? "selected" : null)],
 'Europe/Sarajevo' => ['Europe/Sarajevo', (config('TIMEZONE') == 'Europe/Sarajevo' ? "selected" : null)],
 'Europe/Sofia' => ['Europe/Sofia', (config('TIMEZONE') == 'Europe/Sofia' ? "selected" : null)],
 'Europe/Ulyanovsk' => ['Europe/Ulyanovsk', (config('TIMEZONE') == 'Europe/Ulyanovsk' ? "selected" : null)],
 'Europe/Vienna' => ['Europe/Vienna', (config('TIMEZONE') == 'Europe/Vienna' ? "selected" : null)],
 'Europe/Zagreb' => ['Europe/Zagreb', (config('TIMEZONE') == 'Europe/Zagreb' ? "selected" : null)],
 'Europe/Astrakhan' => ['Europe/Astrakhan', (config('TIMEZONE') == 'Europe/Astrakhan' ? "selected" : null)],
 'Europe/Bratislava' => ['Europe/Bratislava', (config('TIMEZONE') == 'Europe/Bratislava' ? "selected" : null)],
 'Europe/Busingen' => ['Europe/Busingen', (config('TIMEZONE') == 'Europe/Busingen' ? "selected" : null)],
 'Europe/Gibraltar' => ['Europe/Gibraltar', (config('TIMEZONE') == 'Europe/Gibraltar' ? "selected" : null)],
 'Europe/Istanbul' => ['Europe/Istanbul', (config('TIMEZONE') == 'Europe/Istanbul' ? "selected" : null)],
 'Europe/Kirov' => ['Europe/Kirov', (config('TIMEZONE') == 'Europe/Kirov' ? "selected" : null)],
 'Europe/Luxembourg' => ['Europe/Luxembourg', (config('TIMEZONE') == 'Europe/Luxembourg' ? "selected" : null)],
 'Europe/Minsk' => ['Europe/Minsk', (config('TIMEZONE') == 'Europe/Minsk' ? "selected" : null)],
 'Europe/Paris' => ['Europe/Paris', (config('TIMEZONE') == 'Europe/Paris' ? "selected" : null)],
 'Europe/Rome' => ['Europe/Rome', (config('TIMEZONE') == 'Europe/Rome' ? "selected" : null)],
 'Europe/Saratov' => ['Europe/Saratov', (config('TIMEZONE') == 'Europe/Saratov' ? "selected" : null)],
 'Europe/Stockholm' => ['Europe/Stockholm', (config('TIMEZONE') == 'Europe/Stockholm' ? "selected" : null)],
 'Europe/Uzhgorod' => ['Europe/Uzhgorod', (config('TIMEZONE') == 'Europe/Uzhgorod' ? "selected" : null)],
 'Europe/Vilnius' => ['Europe/Vilnius', (config('TIMEZONE') == 'Europe/Vilnius' ? "selected" : null)],
 'Europe/Zaporozhye' => ['Europe/Zaporozhye', (config('TIMEZONE') == 'Europe/Zaporozhye' ? "selected" : null)],
 'Europe/Athens' => ['Europe/Athens', (config('TIMEZONE') == 'Europe/Athens' ? "selected" : null)],
 'Europe/Brussels' => ['Europe/Brussels', (config('TIMEZONE') == 'Europe/Brussels' ? "selected" : null)],
 'Europe/Chisinau' => ['Europe/Chisinau', (config('TIMEZONE') == 'Europe/Chisinau' ? "selected" : null)],
 'Europe/Guernsey' => ['Europe/Guernsey', (config('TIMEZONE') == 'Europe/Guernsey' ? "selected" : null)],
 'Europe/Jersey' => ['Europe/Jersey', (config('TIMEZONE') == 'Europe/Jersey' ? "selected" : null)],
 'Europe/Lisbon' => ['Europe/Lisbon', (config('TIMEZONE') == 'Europe/Lisbon' ? "selected" : null)],
 'Europe/Madrid' => ['Europe/Madrid', (config('TIMEZONE') == 'Europe/Madrid' ? "selected" : null)],
 'Europe/Monaco' => ['Europe/Monaco', (config('TIMEZONE') == 'Europe/Monaco' ? "selected" : null)],
 'Europe/Podgorica' => ['Europe/Podgorica', (config('TIMEZONE') == 'Europe/Podgorica' ? "selected" : null)],
 'Europe/Samara' => ['Europe/Samara', (config('TIMEZONE') == 'Europe/Samara' ? "selected" : null)],
 'Europe/Simferopol' => ['Europe/Simferopol', (config('TIMEZONE') == 'Europe/Simferopol' ? "selected" : null)],
 'Europe/Tallinn' => ['Europe/Tallinn', (config('TIMEZONE') == 'Europe/Tallinn' ? "selected" : null)],
 'Europe/Vaduz' => ['Europe/Vaduz', (config('TIMEZONE') == 'Europe/Vaduz' ? "selected" : null)],
 'Europe/Volgograd' => ['Europe/Volgograd', (config('TIMEZONE') == 'Europe/Volgograd' ? "selected" : null)],
 'Europe/Zurich' => ['Europe/Zurich', (config('TIMEZONE') == 'Europe/Zurich' ? "selected" : null)]
), 'Временная зона по умолчанию', 'form-control-100-modify-select', 'clock-o')?> 
  
<?=html::checkbox('ajax', 'AJAX переходы по страницам и отправка форм', 1, config('AJAX'))?><br /><br />
<?=html::checkbox('ajax_interval_set', 'AJAX обновление контента по интервалу', 1, config('AJAX_INTERVAL_SET'))?><br /><br />  

<?=html::select('ajax_interval', array(
  1000 => [lg('%d сек.', 1), (config('AJAX_INTERVAL') == 1000 ? "selected" : null)], 
  2000 => [lg('%d сек.', 2), (config('AJAX_INTERVAL') == 2000 ? "selected" : null)],
  3000 => [lg('%d сек.', 3), (config('AJAX_INTERVAL') == 3000 ? "selected" : null)], 
  4000 => [lg('%d сек.', 4), (config('AJAX_INTERVAL') == 4000 ? "selected" : null)],
  5000 => [lg('%d сек.', 5), (config('AJAX_INTERVAL') == 5000 ? "selected" : null)], 
  6000 => [lg('%d сек.', 6), (config('AJAX_INTERVAL') == 6000 ? "selected" : null)],
  7000 => [lg('%d сек.', 7), (config('AJAX_INTERVAL') == 7000 ? "selected" : null)], 
  8000 => [lg('%d сек.', 8), (config('AJAX_INTERVAL') == 8000 ? "selected" : null)],
  9000 => [lg('%d сек.', 9), (config('AJAX_INTERVAL') == 9000 ? "selected" : null)], 
  10000 => [lg('%d сек.', 10), (config('AJAX_INTERVAL') == 10000 ? "selected" : null)],
  11000 => [lg('%d сек.', 11), (config('AJAX_INTERVAL') == 11000 ? "selected" : null)], 
  12000 => [lg('%d сек.', 12), (config('AJAX_INTERVAL') == 12000 ? "selected" : null)],
  13000 => [lg('%d сек.', 13), (config('AJAX_INTERVAL') == 13000 ? "selected" : null)], 
  14000 => [lg('%d сек.', 14), (config('AJAX_INTERVAL') == 14000 ? "selected" : null)],
  15000 => [lg('%d сек.', 15), (config('AJAX_INTERVAL') == 15000 ? "selected" : null)], 
  16000 => [lg('%d сек.', 16), (config('AJAX_INTERVAL') == 16000 ? "selected" : null)],
  17000 => [lg('%d сек.', 17), (config('AJAX_INTERVAL') == 17000 ? "selected" : null)], 
  18000 => [lg('%d сек.', 18), (config('AJAX_INTERVAL') == 18000 ? "selected" : null)],
  19000 => [lg('%d сек.', 19), (config('AJAX_INTERVAL') == 19000 ? "selected" : null)], 
  20000 => [lg('%d сек.', 20), (config('AJAX_INTERVAL') == 20000 ? "selected" : null)],
  21000 => [lg('%d сек.', 21), (config('AJAX_INTERVAL') == 21000 ? "selected" : null)],
  22000 => [lg('%d сек.', 22), (config('AJAX_INTERVAL') == 22000 ? "selected" : null)], 
  23000 => [lg('%d сек.', 23), (config('AJAX_INTERVAL') == 23000 ? "selected" : null)],
  24000 => [lg('%d сек.', 24), (config('AJAX_INTERVAL') == 24000 ? "selected" : null)], 
  25000 => [lg('%d сек.', 25), (config('AJAX_INTERVAL') == 25000 ? "selected" : null)],
  26000 => [lg('%d сек.', 26), (config('AJAX_INTERVAL') == 26000 ? "selected" : null)], 
  27000 => [lg('%d сек.', 27), (config('AJAX_INTERVAL') == 27000 ? "selected" : null)],
  28000 => [lg('%d сек.', 28), (config('AJAX_INTERVAL') == 28000 ? "selected" : null)], 
  29000 => [lg('%d сек.', 29), (config('AJAX_INTERVAL') == 29000 ? "selected" : null)],
  30000 => [lg('%d сек.', 30), (config('AJAX_INTERVAL') == 30000 ? "selected" : null)]
), 'Интервал AJAX обновления контента', 'form-control-100-modify-select', 'history')?>
  
<?=html::select('ajax_timeout', array(
  300 => [lg('Каждые %d минут', 5), (config('AJAX_TIMEOUT') == 300 ? "selected" : null)], 
  600 => [lg('Каждые %d минут', 10), (config('AJAX_TIMEOUT') == 600 ? "selected" : null)],
  900 => [lg('Каждые %d минут', 15), (config('AJAX_TIMEOUT') == 900 ? "selected" : null)], 
  1200 => [lg('Каждые %d минут', 20), (config('AJAX_TIMEOUT') == 1200 ? "selected" : null)],
  1500 => [lg('Каждые %d минут', 25), (config('AJAX_TIMEOUT') == 1500 ? "selected" : null)], 
  1800 => [lg('Каждые %d минут %s', 30, '(рекомендуется)'), (config('AJAX_TIMEOUT') == 1800 ? "selected" : null)],
  3600 => ['Каждый час', (config('AJAX_TIMEOUT') == 3600 ? "selected" : null)], 
), 'Остановка AJAX запросов при неактивности', 'form-control-100-modify-select', 'history')?>   
  
<?=html::checkbox('int', 'Ошибки интерпретатора', 1, config('INTERPRETATOR'))?><br /><br />   
<?=html::checkbox('csrf', 'Токены для get/post запросов', 1, config('CSRF'))?><br /><br />
  
<?=html::select('db_interval', array(
  1 => [lg('%d сек.', 1), (config('DB_INTERVAL') == 1 ? "selected" : null)], 
  2 => [lg('%d сек. %s', 2, '(рекомендуется)'), (config('DB_INTERVAL') == 2 ? "selected" : null)],
  3 => [lg('%d сек.', 3), (config('DB_INTERVAL') == 3 ? "selected" : null)], 
  4 => [lg('%d сек.', 4), (config('DB_INTERVAL') == 4 ? "selected" : null)],
  5 => [lg('%d сек.', 5), (config('DB_INTERVAL') == 5 ? "selected" : null)], 
  6 => [lg('%d сек.', 6), (config('DB_INTERVAL') == 6 ? "selected" : null)],
  7 => [lg('%d сек.', 7), (config('DB_INTERVAL') == 7 ? "selected" : null)], 
  8 => [lg('%d сек.', 8), (config('DB_INTERVAL') == 8 ? "selected" : null)], 
  9 => [lg('%d сек.', 9), (config('DB_INTERVAL') == 9 ? "selected" : null)],
  10 => [lg('%d сек.', 10), (config('DB_INTERVAL') == 10 ? "selected" : null)],
  11 => [lg('%d сек.', 11), (config('DB_INTERVAL') == 11 ? "selected" : null)],
  12 => [lg('%d сек.', 12), (config('DB_INTERVAL') == 12 ? "selected" : null)],
  13 => [lg('%d сек.', 13), (config('DB_INTERVAL') == 13 ? "selected" : null)],
  14 => [lg('%d сек.', 14), (config('DB_INTERVAL') == 14 ? "selected" : null)],
  15 => [lg('%d сек.', 15), (config('DB_INTERVAL') == 15 ? "selected" : null)],
  16 => [lg('%d сек.', 16), (config('DB_INTERVAL') == 16 ? "selected" : null)],
  17 => [lg('%d сек.', 17), (config('DB_INTERVAL') == 17 ? "selected" : null)],
  18 => [lg('%d сек.', 18), (config('DB_INTERVAL') == 18 ? "selected" : null)],
  19 => [lg('%d сек.', 19), (config('DB_INTERVAL') == 19 ? "selected" : null)],
  20 => [lg('%d сек.', 20), (config('DB_INTERVAL') == 20 ? "selected" : null)]
), 'Интервал отправки POST и GET запросов', 'form-control-100-modify-select', 'history')?>  

<?=html::button('button ajax-button', 'ok_save_system', 'save', 'Сохранить изменения', '2')?>
  
</form>
</div>
  
<div class='list-menu'>
<b><?=lg('Сброс настроек')?></b><br /><br />
<?=lg('Вы можете вернуться до заводских настроек системы')?><br /><br />
<a href='/admin/system/settings/?get=set_null&<?=TOKEN_URL?>' class='button2'><?=icons('gears', 15, 'fa-fw')?> <?=lg('Сбросить настройки')?></a> 
</div>
  
</div>
<?

back('/admin/system/');
acms_footer();