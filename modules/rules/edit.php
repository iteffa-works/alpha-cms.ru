<?php
$rules = db::get_string("SELECT * FROM `RULES` WHERE `ID` = ? LIMIT 1", [intval(get('id'))]);
html::title('Редактировать раздел правил');
acms_header();
access('rules');

if (!isset($rules['ID'])) {
  
  error('Неверная директива');
  redirect('/m/rules/');

}

if (post('ok_rules')){
  
  valid::create(array(
    
    'NAME' => ['name', 'text', [1, 200], 'Название'],
    'MESSAGE' => ['message', 'text', [1, 10000], 'Сообщение']
  
  ));
  
  if (ERROR_LOG == 1){
    
    redirect('/m/rules/edit/?id='.$rules['ID']);
  
  }
  
  db::get_set("UPDATE `RULES` SET `MESSAGE` = ?, `NAME` = ?, `TIME` = ? WHERE `ID` = ? LIMIT 1", [MESSAGE, NAME, TM, $rules['ID']]);

  redirect('/m/rules/show/?id='.$rules['ID']);
  
}

?>    
<div class='list'>
<form method='post' class='ajax-form' action='/m/rules/edit/?id=<?=$rules['ID']?>'>
<?=html::input('name', 'Введите название', null, null, tabs($rules['NAME']), 'form-control-100', 'text', null, 'info-circle')?>
<?=html::textarea(tabs($rules['MESSAGE']), 'message', 'Введите содержимое', null, 'form-control-textarea', 9, 0)?>
<br /><br />
<?=html::button('button ajax-button', 'ok_rules', 'plus', 'Сохранить')?> 
<a class='button-o' href='/m/rules/show/?id=<?=$rules['ID']?>'><?=lg('Отмена')?></a>
<form>
</div>
<?

back('/m/rules/show/?id='.$rules['ID']);
acms_footer();