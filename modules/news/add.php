<?php
html::title('Добавить новость');
acms_header();
access('news');

if (config('PRIVATE_NEWS') == 0){
  
  error('Модуль отключен администратором');
  redirect('/');
  
}

if (post('ok_news')){
  
  valid::create(array(
    
    'NEWS_NAME' => ['name', 'text', [2, 200], 'Название', 0],
    'NEWS_PRIVATE_COMMENTS' => ['private_comments', 'number', [0, 5], 'Приватность комментариев'],
    'NEWS_MESSAGE' => ['message', 'text', [10, 20000], 'Содержание', 0],
    'NEWS_MAIN_TIME' => ['main_time', 'number', [0, 99999999999999], 'Показ на главной']
  
  ));
  
  if (NEWS_MAIN_TIME == 0){
    
    $main_time = null;
    
  }else{
    
    $main_time = TM + NEWS_MAIN_TIME;
    
  }
  
  if (db::get_column("SELECT COUNT(*) FROM `NEWS` WHERE `MESSAGE` = ? LIMIT 1", [NEWS_MESSAGE]) > 0){
    
    error('Новость с таким содержимым уже существует');
    redirect('/m/news/add/');
    
  }
  
  if (ERROR_LOG == 1){
    
    redirect('/m/news/add/');
  
  }
  
  $ID = db::get_add("INSERT INTO `NEWS` (`NAME`, `PRIVATE_COMMENTS`, `USER_ID`, `MESSAGE`, `TIME`, `MAIN_TIME`) VALUES (?, ?, ?, ?, ?, ?)", [NEWS_NAME, NEWS_PRIVATE_COMMENTS, user('ID'), NEWS_MESSAGE, TM, $main_time]);
  
  if (db::get_column("SELECT COUNT(*) FROM `ATTACHMENTS` WHERE `TYPE_POST` = ? AND `ID_POST` = ? LIMIT 1", ['news', 0]) > 0){
    
    db::get_set("UPDATE `ATTACHMENTS` SET `ID_POST` = ?, `ACT` = '1' WHERE `USER_ID` = ? AND `ACT` = '0' AND `TYPE_POST` = ?", [$ID, user('ID'), 'news']);
  
  }
  
  success('Новость успешно создана');
  redirect('/m/news/show/?id='.$ID);
  
}

?>    
<div class='list'>
<form method='post' class='ajax-form' action='/m/news/add/'>
<?
html::input('name', 'Название', null, null, null, 'form-control-100', 'text', null, 'feed');
define('ACTION', '/m/news/add/');
define('TYPE', 'news');
define('ID', 0);
html::textarea(null, 'message', 'Введите содержимое', null, 'form-control-textarea', 9);  
?><br /><br /><? 
html::select('private_comments', array(
  0 => ['Всем', 0], 
  1 => ['Только членам администрации', 1], 
  2 => ['Только мне', 2]
), 'Комментирование', 'form-control-100-modify-select', 'comment');
html::select('main_time', array(
  0 => ['Не показывать', 0], 
  86400 => ['1 '.lg('день'), 86400], 
  172800 => ['2 '.lg('дня'), 172800],  
  259200 => ['3 '.lg('дня'), 259200], 
  345600 => ['4 '.lg('дня'), 345600], 
  432000 => ['5 '.lg('дней'), 432000], 
  518400 => ['6 '.lg('дней'), 518400], 
  604800 => ['7 '.lg('дней'), 604800], 
  691200 => ['8 '.lg('дней'), 691200],   
  777600 => ['9 '.lg('дней'), 777600], 
  864000 => ['10 '.lg('дней'), 864000], 
  950400 => ['11 '.lg('дней'), 950400], 
  1036800 => ['12 '.lg('дней'), 1036800], 
  1123200 => ['13 '.lg('дней'), 1123200], 
  1209600 => ['14 '.lg('дней'), 1209600], 
  1296000 => ['15 '.lg('дней'), 1296000],  
  1382400 => ['16 '.lg('дней'), 1382400], 
  1468800 => ['17 '.lg('дней'), 1468800], 
  1555200 => ['18 '.lg('дней'), 1555200], 
  1641600 => ['19 '.lg('дней'), 1641600], 
  1728000 => ['20 '.lg('дней'), 1728000], 
  1814400 => ['21 '.lg('дней'), 1814400], 
  1900800 => ['22 '.lg('дня'), 1900800], 
  1987200 => ['23 '.lg('дня'), 1987200], 
  2073600 => ['24 '.lg('дня'), 2073600],  
  2160000 => ['25 '.lg('дней'), 2160000], 
  2246400 => ['26 '.lg('дней'), 2246400], 
  2332800 => ['27 '.lg('дней'), 2332800], 
  2419200 => ['28 '.lg('дней'), 2419200], 
  2505600 => ['29 '.lg('дней'), 2505600], 
  2592000 => ['30 '.lg('дней'), 2592000],  
  5184000 => ['2 '.lg('месяца'), 5184000], 
  7776000 => ['3 '.lg('месяца'), 7776000], 
  10368000 => ['4 '.lg('месяца'), 10368000],  
  12960000 => ['5 '.lg('месяцев'), 12960000], 
  15552000 => ['6 '.lg('месяцев'), 15552000], 
  31536000 => ['1 '.lg('год'), 31536000]
), 'Показ на главной', 'form-control-100-modify-select', 'clock-o');
html::button('button ajax-button', 'ok_news', 'plus', 'Добавить');  
?>
<a class='button-o' href='/m/news/'><?=lg('Отмена')?></a>
<form>
</div>
<?

back('/m/news/');
acms_footer();