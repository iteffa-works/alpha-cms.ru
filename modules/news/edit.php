<?php  
$news = db::get_string("SELECT * FROM `NEWS` WHERE `ID` = ? LIMIT 1", [intval(get('id'))]);
html::title(lg('Редактировать - %s', tabs($news['NAME'])));
acms_header();
access('news');
get_check_valid();

if (!isset($news['ID'])) {
  
  error('Неверная директива');
  redirect('/m/news/');

}

if (config('PRIVATE_NEWS') == 0){
  
  error('Модуль отключен администратором');
  redirect('/');
  
}

if (post('ok_edit_news')){
  
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
  
  if ($news['MESSAGE'] != NEWS_MESSAGE && db::get_column("SELECT COUNT(*) FROM `NEWS` WHERE `MESSAGE` = ? LIMIT 1", [NEWS_MESSAGE]) > 0){
    
    error('Новость с таким содержимым уже существует');
    redirect('/m/news/edit/?id='.$news['ID'].'&'.TOKEN_URL);
    
  }
  
  if (ERROR_LOG == 1){
    
    redirect('/m/news/edit/?id='.$news['ID'].'&'.TOKEN_URL);
  
  }
  
  db::get_set("UPDATE `NEWS` SET `PRIVATE_COMMENTS` = ?, `NAME` = ?, `MESSAGE` = ?, `MAIN_TIME` = ? WHERE `ID` = ? LIMIT 1", [NEWS_PRIVATE_COMMENTS, NEWS_NAME, NEWS_MESSAGE, $main_time, $news['ID']]);
  
  logs('Новости - редактирование новости [url=/m/news/show/?id='.$news['ID'].']'.$news['NAME'].'[/url]', user('ID'));
  
  success('Изменения успешно приняты');
  redirect('/m/news/show/?id='.$news['ID']);
  
}

?>    
<div class='list'>
<form method='post' class='ajax-form' action='/m/news/edit/?id=<?=$news['ID']?>&<?=TOKEN_URL?>'>
<?
html::input('name', 'Название', null, null, tabs($news['NAME']), 'form-control-100', 'text', null, 'feed');
define('ACTION', '/m/news/edit/?id='.$news['ID'].'&'.TOKEN_URL);
define('TYPE', 'news');
define('ID', $news['ID']);
html::textarea(tabs($news['MESSAGE']), 'message', 'Введите содержимое', null, 'form-control-textarea', 9); 
?><br /><br /><?
html::select('private_comments', array(
  0 => ['Всем', ($news['PRIVATE_COMMENTS'] == 0 ? "selected" : null)], 
  1 => ['Только членам администрации', ($news['PRIVATE_COMMENTS'] == 1 ? "selected" : null)], 
  2 => ['Только мне', ($news['PRIVATE_COMMENTS'] == 2 ? "selected" : null)]
), 'Комментирование', 'form-control-100-modify-select', 'comment');
html::select('main_time', array(
  0 => ['Не показывать', 0], 
  86400 => ['1 '.lg('день'), ($news['PRIVATE_COMMENTS'] == 86400 ? "selected" : null)], 
  172800 => ['2 '.lg('дня'), ($news['PRIVATE_COMMENTS'] == 172800 ? "selected" : null)],  
  259200 => ['3 '.lg('дня'), ($news['PRIVATE_COMMENTS'] == 259200 ? "selected" : null)], 
  345600 => ['4 '.lg('дня'), ($news['PRIVATE_COMMENTS'] == 345600 ? "selected" : null)], 
  432000 => ['5 '.lg('дней'), ($news['PRIVATE_COMMENTS'] == 432000 ? "selected" : null)], 
  518400 => ['6 '.lg('дней'), ($news['PRIVATE_COMMENTS'] == 518400 ? "selected" : null)], 
  604800 => ['7 '.lg('дней'), ($news['PRIVATE_COMMENTS'] == 604800 ? "selected" : null)], 
  691200 => ['8 '.lg('дней'), ($news['PRIVATE_COMMENTS'] == 691200 ? "selected" : null)],   
  777600 => ['9 '.lg('дней'), ($news['PRIVATE_COMMENTS'] == 777600 ? "selected" : null)], 
  864000 => ['10 '.lg('дней'), ($news['PRIVATE_COMMENTS'] == 864000 ? "selected" : null)], 
  950400 => ['11 '.lg('дней'), ($news['PRIVATE_COMMENTS'] == 950400 ? "selected" : null)], 
  1036800 => ['12 '.lg('дней'), ($news['PRIVATE_COMMENTS'] == 1036800 ? "selected" : null)], 
  1123200 => ['13 '.lg('дней'), ($news['PRIVATE_COMMENTS'] == 1123200 ? "selected" : null)], 
  1209600 => ['14 '.lg('дней'), ($news['PRIVATE_COMMENTS'] == 1209600 ? "selected" : null)], 
  1296000 => ['15 '.lg('дней'), ($news['PRIVATE_COMMENTS'] == 1296000 ? "selected" : null)],  
  1382400 => ['16 '.lg('дней'), ($news['PRIVATE_COMMENTS'] == 1382400 ? "selected" : null)], 
  1468800 => ['17 '.lg('дней'), ($news['PRIVATE_COMMENTS'] == 1468800 ? "selected" : null)], 
  1555200 => ['18 '.lg('дней'), ($news['PRIVATE_COMMENTS'] == 1555200 ? "selected" : null)], 
  1641600 => ['19 '.lg('дней'), ($news['PRIVATE_COMMENTS'] == 1641600 ? "selected" : null)], 
  1728000 => ['20 '.lg('дней'), ($news['PRIVATE_COMMENTS'] == 1728000 ? "selected" : null)], 
  1814400 => ['21 '.lg('дней'), ($news['PRIVATE_COMMENTS'] == 1814400 ? "selected" : null)], 
  1900800 => ['22 '.lg('дня'), ($news['PRIVATE_COMMENTS'] == 1900800 ? "selected" : null)], 
  1987200 => ['23 '.lg('дня'), ($news['PRIVATE_COMMENTS'] == 1987200 ? "selected" : null)], 
  2073600 => ['24 '.lg('дня'), ($news['PRIVATE_COMMENTS'] == 2073600 ? "selected" : null)],  
  2160000 => ['25 '.lg('дней'), ($news['PRIVATE_COMMENTS'] == 2160000 ? "selected" : null)], 
  2246400 => ['26 '.lg('дней'), ($news['PRIVATE_COMMENTS'] == 2246400 ? "selected" : null)], 
  2332800 => ['27 '.lg('дней'), ($news['PRIVATE_COMMENTS'] == 2332800 ? "selected" : null)], 
  2419200 => ['28 '.lg('дней'), ($news['PRIVATE_COMMENTS'] == 2419200 ? "selected" : null)], 
  2505600 => ['29 '.lg('дней'), ($news['PRIVATE_COMMENTS'] == 2505600 ? "selected" : null)], 
  2592000 => ['30 '.lg('дней'), ($news['PRIVATE_COMMENTS'] == 2592000 ? "selected" : null)],  
  5184000 => ['2 '.lg('месяца'), ($news['PRIVATE_COMMENTS'] == 5184000 ? "selected" : null)], 
  7776000 => ['3 '.lg('месяца'), ($news['PRIVATE_COMMENTS'] == 7776000 ? "selected" : null)], 
  10368000 => ['4 '.lg('месяца'), ($news['PRIVATE_COMMENTS'] == 10368000 ? "selected" : null)],  
  12960000 => ['5 '.lg('месяцев'), ($news['PRIVATE_COMMENTS'] == 12960000 ? "selected" : null)], 
  15552000 => ['6 '.lg('месяцев'), ($news['PRIVATE_COMMENTS'] == 15552000 ? "selected" : null)], 
  31536000 => ['1 '.lg('год'), ($news['PRIVATE_COMMENTS'] == 31536000 ? "selected" : null)]
), 'Показ на главной', 'form-control-100-modify-select', 'clock-o');
html::button('button ajax-button', 'ok_edit_news', 'save', 'Сохранить');  
?>
<a class='button-o' href='/m/news/show/?id=<?=$news['ID']?>'><?=lg('Отмена')?></a>
<form>
</div>
<?

back('/m/news/show/?id='.$news['ID']);
acms_footer();