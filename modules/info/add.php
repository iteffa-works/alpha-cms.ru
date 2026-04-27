<?php
html::title('Добавить информацию');
acms_header();
access('info');

if (post('ok_info')){
  
  valid::create(array(
    
    'NAME' => ['name', 'text', [1, 200], 'Название'],
    'MESSAGE' => ['message', 'text', [1, 3000], 'Сообщение']
  
  ));
  
  if (ERROR_LOG == 1){
    
    redirect('/m/info/add/');
  
  }
  
  db::get_add("INSERT INTO `INFO` (`MESSAGE`, `NAME`, `TIME`) VALUES (?, ?, ?)", [MESSAGE, NAME, TM]);
  
  redirect('/m/info/');
  
}

?>    
<div class='list'>
<form method='post' class='ajax-form' action='/m/info/add/'>
<?=html::input('name', 'Введите название', null, null, null, 'form-control-100', 'text', null, 'info-circle')?>
<?=html::textarea(null, 'message', 'Введите содержимое', null, 'form-control-textarea', 9, 0)?>
<br /><br />
<?=html::button('button ajax-button', 'ok_info', 'plus', 'Добавить')?> 
<a class='button-o' href='/m/info/'><?=lg('Отмена')?></a>
<form>
</div>
<?

back('/m/info/');
acms_footer();