<?php
  
if (post('ok_type')){
  
  valid::create(array(
    
    'TYPE_BODY' => ['body', 'number', [0, 100], 'Телосложение', 0],
    'TYPE_NATURE' => ['nature', 'text', [0, 300], 'Характер', 0],
    'TYPE_HAIR' => ['hair', 'text', [0, 300], 'Цвет волос', 0],
    'TYPE_EYE' => ['eye', 'text', [0, 300], 'Цвет глаз', 0],
    'TYPE_WIDTH' => ['width', 'number', [0, 250], 'Вес'],
    'TYPE_HEIGHT' => ['height', 'number', [0, 250], 'Рост']
  
  ));
  
  if (ERROR_LOG == 1){
    
    redirect('/account/form/?id='.$account['ID'].'&get=type&'.TOKEN_URL);
  
  }
  
  db::get_set("UPDATE `USERS_SETTINGS` SET `MY_BODY` = ?, `MY_NATURE` = ?, `COLOR_HAIR` = ?, `COLOR_EYE` = ?, `HEIGHT` = ?, `WIDTH` = ? WHERE `USER_ID` = ? LIMIT 1", [TYPE_BODY, TYPE_NATURE, TYPE_HAIR, TYPE_EYE, TYPE_HEIGHT, TYPE_WIDTH, $account['ID']]);
  
  success('Изменения успешно приняты');
  redirect('/account/form/?id='.$account['ID']);
  
}
  
?>

<div class='list'>  
<form method='post' class='ajax-form' action='/account/form/?id=<?=$account['ID']?>&get=type&<?=TOKEN_URL?>'>
<?=html::input('height', 'Рост', null, null, tabs($settings['HEIGHT']), 'form-control-100', 'type', null, 'arrows-v')?>
<?=html::input('width', 'Вес', null, null, tabs($settings['WIDTH']), 'form-control-100', 'type', null, 'arrows-h')?>  
<?=html::input('eye', 'Цвет глаз', null, null, tabs($settings['COLOR_EYE']), 'form-control-100', 'type', null, 'eye')?>
<?=html::input('hair', 'Цвет волос', null, null, tabs($settings['COLOR_HAIR']), 'form-control-100', 'type', null, 'user')?>  
<?=html::input('nature', 'Характер', null, null, tabs($settings['MY_NATURE']), 'form-control-100', 'type', null, 'book')?> 
<?=html::select('body', array(
  1 => ['Не важно', ($settings['MY_BODY'] == 1 ? "selected" : null)],
  2 => ['Обычное', ($settings['MY_BODY'] == 2 ? "selected" : null)], 
  3 => ['Худощавое', ($settings['MY_BODY'] == 3 ? "selected" : null)],
  4 => ['Спортивное', ($settings['MY_BODY'] == 4 ? "selected" : null)], 
  5 => ['Мускулистое', ($settings['MY_BODY'] == 5 ? "selected" : null)],
  6 => ['Плотное', ($settings['MY_BODY'] == 6 ? "selected" : null)],
  7 => ['Полное', ($settings['MY_BODY'] == 7 ? "selected" : null)]
), 'Телосложение', 'form-control-100-modify-select', 'user')?> 
<?=html::button('button ajax-button', 'ok_type', 'save', 'Сохранить изменения')?> 
</form>  
</div>