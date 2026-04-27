<?php  
$game = db::get_string("SELECT * FROM `GAMES` WHERE `ID` = ? LIMIT 1", [intval(get('id'))]);
acms_header(lg('Редактировать игру %s', tabs($game['NAME'])), 'users');
is_active_module('PRIVATE_GAMES');

if (!isset($game['ID'])) {
  
  error('Неверная директива');
  redirect('/m/games/');

}

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
  
  if ($game['NAME'] != GAMES_NAME && db::get_column("SELECT COUNT(*) FROM `GAMES` WHERE `NAME` = ? LIMIT 1", [GAMES_NAME]) > 0){
    
    error('Игра с таким названием уже существует');
    redirect('/m/games/edit/?id='.$game['ID']);
    
  }
  
  if ($game['LINK'] != GAMES_LINK && db::get_column("SELECT COUNT(*) FROM `GAMES` WHERE `LINK` = ? LIMIT 1", [GAMES_LINK]) > 0){
    
    error('Игра с такой директорией уже существует');
    redirect('/m/games/edit/?id='.$game['ID']);
    
  }
  
  if (!preg_match("#^([A-z0-9\_])+$#ui", GAMES_LINK)) {
    
    error('В директории игры присутствуют запрещенные символы. Только латиница, символ "_" и цифры');
    redirect('/m/games/edit/?id='.$game['ID']);
  
  }
  
  if (!preg_match("~([A-z])~", GAMES_LINK)){
    
    error('В директории игры должна содержаться хотябы одна буква (только латиница)');
    redirect('/m/games/edit/?id='.$game['ID']);
  
  }
  
  if (ERROR_LOG == 1){
    
    redirect('/m/games/edit/?id='.$game['ID']);
  
  }
  
  db::get_set("UPDATE `GAMES` SET `NAME` = ?, `MESSAGE` = ?, `LINK` = ?, `IMG` = ? WHERE `ID` = ? LIMIT 1", [GAMES_NAME, GAMES_MESSAGE, GAMES_LINK, GAMES_IMG, $game['ID']]);
  
  logs('Онлайн игры - редактирование игры [url=/m/games/show/?id='.$game['ID'].']'.$game['NAME'].'[/url]', user('ID'));
  
  success('Изменения успешно приняты');
  redirect('/m/games/show/?id='.$game['ID']);
  
}

?>    
<div class='list'>
<form method='post' class='ajax-form' action='/m/games/edit/?id=<?=$game['ID']?>'>
<?=html::input('name', 'Название игры', null, null, tabs($game['NAME']), 'form-control-100', 'text', null, 'text-width', 'Введите название игры от 2 до 50 символов')?>
<?=html::input('message', 'Описание игры', null, null, tabs($game['MESSAGE']), 'form-control-100', 'text', null, 'text-width', 'Введите описание игры от 2 до 500 символов')?>
<?=html::input('link', 'Директория игры', null, null, tabs($game['LINK']), 'form-control-100', 'text', null, 'link', 'Введите название игры из папки /games/ (пример: taxi_money). От 2 до 40 символов')?>
<?=html::input('img', 'Логотип игры', null, null, tabs($game['IMG']), 'form-control-100', 'text', null, 'image', 'Введите название и расширение логотипа игры из папки /games/путь_к_игре/img/ (пример: logo.png). От 2 до 40 символов')?>
<?=html::button('button ajax-button', 'ok_edit_game', 'save', 'Сохранить изменения')?>
<a class='button-o' href='/m/games/'><?=lg('Отмена')?></a>
</form>
</div>
<?

back('/m/games/');
acms_footer();