<?php
html::title('Добавить раздел правил');
acms_header();
access('rules');

if (post('ok_rules')){
  
  valid::create(array(
    
    'NAME' => ['name', 'text', [1, 200], 'Название'],
    'MESSAGE' => ['message', 'text', [1, 10000], 'Сообщение']
  
  ));
  
  if (ERROR_LOG == 1){
    
    redirect('/m/rules/add/');
  
  }
  
  db::get_add("INSERT INTO `RULES` (`MESSAGE`, `NAME`, `TIME`) VALUES (?, ?, ?)", [MESSAGE, NAME, TM]);
  
  redirect('/m/rules/');
  
}

?>    
<div class='list'>
<form method='post' class='ajax-form' action='/m/rules/add/'>
<?=html::input('name', 'Введите название', null, null, null, 'form-control-100', 'text', null, 'info-circle')?>
<?=html::textarea(null, 'message', 'Введите содержимое', null, 'form-control-textarea', 9, 0)?>
<br /><br />
<?=html::button('button ajax-button', 'ok_rules', 'plus', 'Добавить')?> 
<a class='button-o' href='/m/rules/'><?=lg('Отмена')?></a>
<form>
</div>
<?

back('/m/rules/');
acms_footer();