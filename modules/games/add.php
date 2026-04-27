<?php  
acms_header('Добавить игру', 'users');
is_active_module('PRIVATE_GAMES');

if (access('games', null) == false){
  
  error('Нет прав');
  redirect('/m/games/');
  
}

if (post('ok_edit_game')){
  
  valid::create(array(
    
    'GAMES_NAME' => ['name', 'text', [2, 50], 'Название', 0],
    'GAMES_MESSAGE' => ['message', 'text', [2, 500], 'Описание', 0],
    'GAMES_LINK' => ['link', 'text', [2, 40], 'Директория'],
    'GAMES_IMG' => ['img', 'text', [2, 40], 'Логотип']
  
  ));
  
  if (db::get_column("SELECT COUNT(*) FROM `GAMES` WHERE `NAME` = ? LIMIT 1", [GAMES_NAME]) > 0){
    
    error('Игра с таким названием уже существует');
    redirect('/m/games/add/');
    
  }
  
  if (db::get_column("SELECT COUNT(*) FROM `GAMES` WHERE `LINK` = ? LIMIT 1", [GAMES_LINK]) > 0){
    
    error('Игра с такой директорией уже существует');
    redirect('/m/games/add/');
    
  }
  
  if (!preg_match("#^([A-z0-9\_])+$#ui", GAMES_LINK)) {
    
    error('В директории игры присутствуют запрещенные символы. Только латиница, символ "_" и цифры');
    redirect('/m/games/add/');
  
  }
  
  if (!preg_match("~([A-z])~", GAMES_LINK)){
    
    error('В директории игры должна содержаться хотябы одна буква (только латиница)');
    redirect('/m/games/add/');
  
  }
  
  if (ERROR_LOG == 1){
    
    redirect('/m/games/add/');
  
  }
  
  $ID = db::get_add("INSERT INTO `GAMES` (`NAME`, `MESSAGE`, `LINK`, `IMG`) VALUES (?, ?, ?, ?)", [GAMES_NAME, GAMES_MESSAGE, GAMES_LINK, GAMES_IMG]);
  
  logs('Онлайн игры - добавление игры [url=/m/games/show/?id='.$ID.']'.GAMES_NAME.'[/url]', user('ID'));
  
  success('Изменения успешно приняты');
  redirect('/m/games/show/?id='.$ID);
  
}

?>    
<div class='list'>
<form method='post' class='ajax-form' action='/m/games/add/'>
<?=html::input('name', 'Название игры', null, null, null, 'form-control-100', 'text', null, 'text-width', 'Введите название игры от 2 до 50 символов')?>
<?=html::input('message', 'Описание игры', null, null, null, 'form-control-100', 'text', null, 'text-width', 'Введите описание игры от 2 до 500 символов')?>
<?=html::input('link', 'Директория игры', null, null, null, 'form-control-100', 'text', null, 'link', 'Введите название игры из папки /games/ (пример: taxi_money). От 2 до 40 символов')?>
<?=html::input('img', 'Логотип игры', null, null, null, 'form-control-100', 'text', null, 'image', 'Введите название и расширение логотипа игры из папки /games/путь_к_игре/img/ (пример: logo.png). От 2 до 40 символов')?>
<?=html::button('button ajax-button', 'ok_edit_game', 'plus', 'Добавить')?>
<a class='button-o' href='/m/games/'><?=lg('Отмена')?></a>
</form>
</div>
<?

back('/m/games/');
acms_footer();