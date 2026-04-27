<?php
  
if (post('ok_abm')){
  
  valid::create(array(
    
    'ABM_ABOUT_ME' => ['about_me', 'text', [0, 300], 'О себе', 0],
    'ABM_INTERESTS' => ['interests', 'text', [0, 300], 'Интересы', 0],
    'ABM_MUSIC' => ['music', 'text', [0, 300], 'Любимая музыка', 0],
    'ABM_FILMS' => ['films', 'text', [0, 300], 'Любимые фильмы', 0],
    'ABM_BOOKS' => ['books', 'text', [0, 300], 'Любимые книги', 0],
    'ABM_PROFESSION' => ['profession', 'text', [0, 300], 'Профессия', 0],
    'ABM_POLITIC' => ['politic', 'number', [1, 100], 'Полит. взгляды'],
    'ABM_FAITH' => ['faith', 'number', [1, 100], 'Мировоззрение'],
    'ABM_ALKOHOL' => ['alkohol', 'number', [0, 100], 'Отношение к алкоголю'],
    'ABM_SMOKING' => ['smoking', 'number', [0, 100], 'Отношение к курению'],
    'ABM_FAMILY' => ['family', 'number', [0, 100], 'Семейное положение']
  
  ));
  
  if (ERROR_LOG == 1){
    
    redirect('/account/form/?id='.$account['ID'].'&get=about_me&'.TOKEN_URL);
  
  }
  
  db::get_set("UPDATE `USERS_SETTINGS` SET `ABOUT_ME` = ?, `MY_INTERESTS` = ?, `MY_MUSIC` = ?, `MY_FILMS` = ?, `MY_BOOKS` = ?, `MY_POLITIC` = ?, `MY_FAITH` = ?, `MY_PROFESSION` = ?, `ALKOHOL` = ?, `SMOKING` = ?, `FAMILY` = ? WHERE `USER_ID` = ? LIMIT 1", [ABM_ABOUT_ME, ABM_INTERESTS, ABM_MUSIC, ABM_FILMS, ABM_BOOKS, ABM_POLITIC, ABM_FAITH, ABM_PROFESSION, ABM_ALKOHOL, ABM_SMOKING, ABM_FAMILY, $account['ID']]);
  
  success('Изменения успешно приняты');
  redirect('/account/form/?id='.$account['ID']);
  
}
  
?>

<div class='list'>  
<form method='post' class='ajax-form' action='/account/form/?id=<?=$account['ID']?>&get=about_me&<?=TOKEN_URL?>'>
<?=html::input('about_me', 'О себе', null, null, tabs($settings['ABOUT_ME']), 'form-control-100', 'type', null, 'address-card')?>
<?=html::input('interests', 'Мои интересы', null, null, tabs($settings['MY_INTERESTS']), 'form-control-100', 'type', null, 'soccer-ball-o')?>  
<?=html::input('music', 'Любимая музыка', null, null, tabs($settings['MY_MUSIC']), 'form-control-100', 'type', null, 'music')?>
<?=html::input('films', 'Любимые фильмы', null, null, tabs($settings['MY_FILMS']), 'form-control-100', 'type', null, 'film')?>  
<?=html::input('books', 'Любимые книги', null, null, tabs($settings['MY_BOOKS']), 'form-control-100', 'type', null, 'book')?>
<?=html::input('profession', 'Профессия', null, null, tabs($settings['MY_PROFESSION']), 'form-control-100', 'type', null, 'wrench')?>    
<?=html::select('politic', array(
  1 => ['Не важно', ($settings['MY_POLITIC'] == 1 ? "selected" : null)],
  2 => ['Коммунистические', ($settings['MY_POLITIC'] == 2 ? "selected" : null)], 
  3 => ['Социалистические', ($settings['MY_POLITIC'] == 3 ? "selected" : null)],
  4 => ['Умеренные', ($settings['MY_POLITIC'] == 4 ? "selected" : null)], 
  5 => ['Либеральные', ($settings['MY_POLITIC'] == 5 ? "selected" : null)],
  6 => ['Консервативные', ($settings['MY_POLITIC'] == 6 ? "selected" : null)], 
  7 => ['Монархические', ($settings['MY_POLITIC'] == 7 ? "selected" : null)],
  8 => ['Ультраконсеративные', ($settings['MY_POLITIC'] == 8 ? "selected" : null)], 
  9 => ['Либертарианские', ($settings['MY_POLITIC'] == 9 ? "selected" : null)],
  10 => ['Индифферентные', ($settings['MY_POLITIC'] == 10 ? "selected" : null)]
), 'Полит. взгляды', 'form-control-100-modify-select', 'eye')?>
<?=html::select('faith', array( 
  1 => ['Не важно', ($settings['MY_FAITH'] == 1 ? "selected" : null)],
  2 => ['Православие', ($settings['MY_FAITH'] == 2 ? "selected" : null)], 
  3 => ['Католицизм', ($settings['MY_FAITH'] == 3 ? "selected" : null)],
  4 => ['Иудаизм', ($settings['MY_FAITH'] == 4 ? "selected" : null)], 
  5 => ['Ислам', ($settings['MY_FAITH'] == 5 ? "selected" : null)],
  6 => ['Протестантизм', ($settings['MY_FAITH'] == 6 ? "selected" : null)], 
  7 => ['Буддизм', ($settings['MY_FAITH'] == 7 ? "selected" : null)],
  8 => ['Конфуцианство', ($settings['MY_FAITH'] == 8 ? "selected" : null)], 
  9 => ['Светский гуманизм', ($settings['MY_FAITH'] == 9 ? "selected" : null)],
  10 => ['Атеизм', ($settings['MY_FAITH'] == 10 ? "selected" : null)], 
  11 => ['Пастафарианство', ($settings['MY_FAITH'] == 11 ? "selected" : null)], 
  12 => ['Агностицизм', ($settings['MY_FAITH'] == 12 ? "selected" : null)]
), 'Мировоззрение', 'form-control-100-modify-select', 'users')?>
<?=html::select('alkohol', array(
  1 => ['Не важно', ($settings['ALKOHOL'] == 1 ? "selected" : null)], 
  2 => ['Негативное', ($settings['ALKOHOL'] == 2 ? "selected" : null)],
  3 => ['Нейтральное', ($settings['ALKOHOL'] == 3 ? "selected" : null)], 
  4 => ['Положительное', ($settings['ALKOHOL'] == 4 ? "selected" : null)]
), 'Отношение к алкоголю', 'form-control-100-modify-select', 'glass')?>
<?=html::select('smoking', array(
  1 => ['Не важно', ($settings['SMOKING'] == 1 ? "selected" : null)], 
  2 => ['Негативное', ($settings['SMOKING'] == 2 ? "selected" : null)],
  3 => ['Нейтральное', ($settings['SMOKING'] == 3 ? "selected" : null)], 
  4 => ['Положительное', ($settings['SMOKING'] == 4 ? "selected" : null)]
), 'Отношение к курению', 'form-control-100-modify-select', 'fire')?>
<?=html::select('family', array(
  1 => ['Не важно', ($settings['FAMILY'] == 1 ? "selected" : null)], 
  2 => [($account['SEX'] == 2 ? 'Не замужем' : 'Не женат'), ($settings['FAMILY'] == 2 ? "selected" : null)],
  3 => [($account['SEX'] == 2 ? 'Разведена' : 'Разведен'), ($settings['FAMILY'] == 3 ? "selected" : null)], 
  4 => [($account['SEX'] == 2 ? 'Влюблена' : 'Влюблен'), ($settings['FAMILY'] == 4 ? "selected" : null)],
  5 => [($account['SEX'] == 2 ? 'Помолвлена' : 'Помолвлен'), ($settings['FAMILY'] == 5 ? "selected" : null)], 
  6 => ['Всё сложно', ($settings['FAMILY'] == 6 ? "selected" : null)],
  7 => [($account['SEX'] == 2 ? 'Замужем' : 'Женат'), ($settings['FAMILY'] == 7 ? "selected" : null)],
), 'Семейное положение', 'form-control-100-modify-select', 'heart')?>  
<?=html::button('button ajax-button', 'ok_abm', 'save', 'Сохранить изменения')?> 
</form>  
</div>