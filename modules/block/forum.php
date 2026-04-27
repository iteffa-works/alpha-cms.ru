<?php
$them = db::get_string("SELECT `ID`,`USER_ID` FROM `FORUM_THEM` WHERE `ID` = ? LIMIT 1", [intval(get('id'))]);  
$account = db::get_string("SELECT `ID`,`LOGIN` FROM `USERS` WHERE `ID` = ? LIMIT 1", [$them['USER_ID']]);  
html::title(lg('Блокировка %s на форуме', $account['LOGIN']));
acms_header();
access('forum');
get_check_valid();

if (!isset($them['ID'])){
  
  error('Неверная директива');
  redirect('/');
  
}

?>
<div class='list'>
<?=lg('После блокировки автор не сможет посещать темы форума или создавать их. Остальные разделы сайта останутся для него доступными')?>
</div>
<?

if (post('ok_blocked')){
  
  valid::create(array(
    
    'BLOCKED_MESSAGE' => ['message', 'text', [0, 1000], 'Комментарий', 0],
    'BLOCKED_TIME' => ['time', 'number', [0, 999999999999], 'Время бана'],
    'BLOCKED_REASON' => ['reason', 'number', [0, 20], 'Причина бана']
  
  ));
  
  if (BLOCKED_TIME == 1){
    
    $nblock = 1;
  
  }else{
    
    $nblock = 0;
  
  }
  
  $tblock = TM + BLOCKED_TIME;
  
  if (ERROR_LOG == 1){
    
    redirect('/m/block/forum/?id='.$them['ID'].'&'.TOKEN_URL);
  
  }
  
  db::get_add("INSERT INTO `FORUM_BAN` (`BAN`, `BAN_TIME`, `REASON`, `ADM_ID`, `USER_ID`, `MESSAGE`, `TIME`) VALUES (?, ?, ?, ?, ?, ?, ?)", [$nblock, $tblock, BLOCKED_REASON, user('ID'), $account['ID'], BLOCKED_MESSAGE, TM]);
  
  $message = lg('Вы заблокированы на форуме за нарушение правил сайта').'. [url=/m/block/forum_list/?id='.$account['ID'].']'.lg('История блокировок').'[/url].';
  messages::get(intval(config('SYSTEM')), $account['ID'], $message);
  
  logs('Блокировка автора форуме - [url=/id='.$account['ID'].']'.$account['LOGIN'].'[/url]', user('ID'));
  
  success('Автор темы успешно заблокирован');
  redirect('/m/forum/show/?id='.$them['ID']);
  
}

?>    
<div class='list'>
<form method='post' class='ajax-form' action='/m/block/forum/?id=<?=$them['ID']?>&<?=TOKEN_URL?>'>
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
<?=html::button('button ajax-button', 'ok_blocked', 'ban', 'Заблокировать')?>  
<a class='button-o' href='/m/forum/show/?id=<?=$them['ID']?>'><?=lg('Отмена')?></a>
<form>
</div>
<?
  
back('/m/forum/show/?id='.$them['ID']);  
acms_footer();