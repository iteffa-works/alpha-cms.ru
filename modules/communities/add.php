<?php
acms_header('Создать сообщество', 'users');
is_active_module('PRIVATE_COMMUNITIES');

if (post('ok_comm')){
  
  valid::create(array(
    
    'COMM_NAME' => ['name', 'text', [5, 40], 'Название', 0],
    'COMM_PRIVATE' => ['private', 'number', [0, 5], 'Приватность'],
    'COMM_ID_CATEGORY' => ['id_cat', 'number', [0, 99999], 'Категория'],
    'COMM_MESSAGE' => ['message', 'text', [0, 80], 'Содержание', 0]
  
  ));
  
  $url = 'id'.rand(111111111,999999999);
  
  if (db::get_column("SELECT COUNT(*) FROM `COMMUNITIES` WHERE `NAME` = ? LIMIT 1", [COMM_NAME]) > 0){
    
    error('Сообщество с таким именем уже существует');
    redirect('/m/communities/add/');
    
  }
  
  if (db::get_column("SELECT COUNT(*) FROM `COMMUNITIES` WHERE `USER_ID` = ? LIMIT 1", [user('ID')]) >= 10){
    
    error('Вы не можете быть владельцем более 10 сообществ');
    redirect('/m/communities/add/');
    
  }
  
  if (ERROR_LOG == 1){
    
    redirect('/m/communities/add/');
  
  }
  
  $ID = db::get_add("INSERT INTO `COMMUNITIES` (`NAME`, `PRIVATE`, `USER_ID`, `ID_CATEGORY`, `MESSAGE`, `TIME`, `URL`) VALUES (?, ?, ?, ?, ?, ?, ?)", [COMM_NAME, COMM_PRIVATE, user('ID'), COMM_ID_CATEGORY, COMM_MESSAGE, TM, $url]);
  db::get_add("INSERT INTO `COMMUNITIES_PAR` (`USER_ID`, `ADMINISTRATION`, `COMMUNITY_ID`) VALUES (?, ?, ?)", [user('ID'), 1, $ID]);
  
  success('Сообщество успешно создано');
  redirect('/public/'.$url);
  
}

?>    
<div class='list'>
<form method='post' class='ajax-form' action='/m/communities/add/'>
<?
html::input('name', 'Название сообщества', null, null, null, 'form-control-100', 'text', null, 'text-width');
html::input('message', 'Описание сообщества', null, null, null, 'form-control-100', 'text', null, 'text-width');
$array = array();
$array[0] = ['Без категории'];
$data = db::get_string_all("SELECT * FROM `COMMUNITIES_CATEGORIES` ORDER BY `ID` DESC");  
while ($list = $data->fetch()){
  
  $array[$list['ID']] = [$list['NAME'], (0 == $list['ID'] ? "selected" : null)];

}
html::select('id_cat', $array, 'Категория', 'form-control-100-modify-select', 'list-ul'); 
html::select('private', array(
  0 => ['Открытое сообщество', 0], 
  1 => ['Анонимное сообщество', 1], 
  2 => ['Сообщество по интересам', 2]
), 'Тип сообщества', 'form-control-100-modify-select', 'users');
html::button('button ajax-button', 'ok_comm', 'plus', 'Добавить');  
?>
<a class='button-o' href='/m/communities/users/?id=<?=user('ID')?>'><?=lg('Отмена')?></a>
</form>
</div>
  
<div class='list-body'>
<div class='list-menu'>
<center><b><?=lg('В ЧЕМ РАЗНИЦА МЕЖДУ ТИПАМИ СООБЩЕСТВ')?>?</b></center>
</div>  
<div class='list-menu'>
<div class='communities-type-icons'>
<b><?=icons('user-plus', 50)?></b>
</div>
<div class='communities-type-info'>  
<b><?=lg('Открытое сообщество')?></b> - <?=lg('сообщество с возможностью открытого вступления без приглашения или одобрения администрации')?>
</div>
</div>
<div class='list-menu'>
<div class='communities-type-icons'>
<b><?=icons('user-secret', 50)?></b>
</div>
<div class='communities-type-info'>  
<b><?=lg('Анонимное сообщество')?></b> - <?=lg('закрытый клуб с возможностью вступления только через приглашение по почте')?>
</div>
</div>
<div class='list-menu'>
<div class='communities-type-icons'>
<b><?=icons('handshake-o', 45)?></b>
</div>
<div class='communities-type-info'>  
<b><?=lg('Сообщество по интересам')?></b> - <?=lg('сообщество с возможностью вступления после одобрения заявки администрацией')?>
</div>  
</div>
</div>
<?

back('/m/communities/users/?id='.user('ID'));
acms_footer();