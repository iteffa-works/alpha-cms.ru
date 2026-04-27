<?php
$comm = db::get_string("SELECT * FROM `COMMUNITIES` WHERE `ID` = ? LIMIT 1", [intval(get('id'))]);  
acms_header(lg('Блокировка сообщества %s', tabs($comm['NAME'])), 'communities');
get_check_valid();

if (!isset($comm['ID'])) {
  
  error('Неверная директива');
  redirect('/');
  
}

if (post('ok_comm_blocked')){
  
  valid::create(array(
    
    'BLOCKED_MESSAGE' => ['message', 'text', [0, 1000], 'Комментарий', 0],
    'BLOCKED_TIME' => ['time', 'number', [0, 999999999999], 'Время бана'],
    'BLOCKED_REASON' => ['reason', 'number', [0, 20], 'Причина бана']
  
  ));
  
  $nblock = (BLOCKED_TIME == 1 ? 1 : 0); 
  $tblock = TM + BLOCKED_TIME;
  
  if (ERROR_LOG == 1){
    
    redirect('/m/block/comm/?id='.$comm['ID'].'&'.TOKEN_URL);
  
  }
  
  db::get_set("UPDATE `COMMUNITIES` SET `BAN` = ? WHERE `ID` = ? LIMIT 1", [$nblock, $comm['ID']]);  
  db::get_add("INSERT INTO `COMMUNITIES_BAN` (`BAN`, `BAN_TIME`, `REASON`, `ADM_ID`, `COMMUNITY_ID`, `MESSAGE`, `TIME`) VALUES (?, ?, ?, ?, ?, ?, ?)", [$nblock, $tblock, BLOCKED_REASON, user('ID'), $comm['ID'], BLOCKED_MESSAGE, TM]);
  
  $message = lg('Ваше сообщество %s получило блокировку за нарушение правил', '[url=/public/'.$comm['URL'].']'.$comm['NAME'].'[/url]').'. [url=/m/block/comm_list/?id='.$comm['ID'].']'.lg('История блокировок').'[/url]';
  messages::get(intval(config('SYSTEM')), $comm['USER_ID'], $message);
  
  logs('Блокировка сообществ - блокировка сообщества [url=/public/'.$comm['URL'].']'.$comm['NAME'].'[/url]', user('ID'));
  
  success('Сообщество успешно заблокировано');
  redirect('/public/'.$comm['URL']);
  
}

?>    
<div class='list'>
<form method='post' class='ajax-form' action='/m/block/comm/?id=<?=$comm['ID']?>&<?=TOKEN_URL?>'>
<?=html::select('reason', array(
  1 => ['СПАМ, реклама', 1], 
  2 => ['Мошенничество', 2], 
  3 => ['Нецензурная брань, оскорбления', 3], 
  4 => ['Разжигание ненависти', 4], 
  5 => ['Пропаганда нацизма', 5], 
  6 => ['Пропаганда наркотиков', 6], 
  7 => ['Систематические нарушения', 7], 
  0 => ['Иная', 0]
), 'Причина бана', 'form-control-100-modify-select', 'ban')?> 
<?=html::select('time', array(
  1 => ['Бан навсегда', 1], 
  60 => ['1 '.lg('минута'), 60], 
  300 => ['5 '.lg('минут'), 300], 
  600 => ['10 '.lg('минут'), 600],  
  1200 => ['20 '.lg('минут'), 1200], 
  1800 => ['30 '.lg('минут'), 1800], 
  3600 => ['1 '.lg('час'), 3600], 
  7200 => ['2 '.lg('часа'), 7200], 
  10800 => ['3 '.lg('часа'), 10800], 
  14400 => ['4 '.lg('часа'), 14400], 
  18000 => ['5 '.lg('часов'), 18000], 
  36000 => ['10 '.lg('часов'), 36000],    
  86400 => ['1 '.lg('день'), 86400], 
  172800 => ['2 '.lg('дня'), 172800], 
  259200 => ['3 '.lg('дня'), 259200], 
  345600 => ['4 '.lg('дня'), 345600], 
  432000 => ['5 '.lg('дней'), 432000], 
  864000 => ['10 '.lg('дней'), 864000],   
  1728000 => ['20 '.lg('дней'), 1728000], 
  2592000 => ['1 '.lg('месяц'), 2592000], 
  5184000 => ['2 '.lg('месяца'), 5184000], 
  7776000 => ['3 '.lg('месяца'), 7776000], 
  10368000 => ['4 '.lg('месяца'), 10368000], 
  12960000 => ['5 '.lg('месяцев'), 12960000],    
  15552000 => ['6 '.lg('месяцев'), 15552000], 
  31104000 => ['1 '.lg('год'), 31104000]  
), 'Время бана', 'form-control-100-modify-select', 'clock-o')?>
<?=html::textarea(null, 'message', 'Комментарий', null, 'form-control-textarea', 7, 0)?>
<br /><br />
<?=html::button('button ajax-button', 'ok_comm_blocked', 'ban', 'Заблокировать')?>  
<a class='button-o' href='/public/<?=$comm['URL']?>'><?=lg('Отмена')?></a>
</form>
</div>
<?
  
back('/public/'.$comm['URL']);  
acms_footer();