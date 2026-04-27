<?php
$info = db::get_string("SELECT * FROM `INFO` WHERE `ID` = ? LIMIT 1", [intval(get('id'))]);
html::title('Редактировать информацию');
acms_header();
access('info');

if (!isset($info['ID'])) {
  
  error('Неверная директива');
  redirect('/m/info/');

}

if (post('ok_info')){
  
  valid::create(array(
    
    'NAME' => ['name', 'text', [1, 200], 'Название'],
    'MESSAGE' => ['message', 'text', [1, 3000], 'Сообщение']
  
  ));
  
  if (ERROR_LOG == 1){
    
    redirect('/m/info/edit/?id='.$info['ID']);
  
  }
  
  db::get_set("UPDATE `INFO` SET `MESSAGE` = ?, `NAME` = ?, `TIME` = ? WHERE `ID` = ? LIMIT 1", [MESSAGE, NAME, TM, $info['ID']]);

  redirect('/m/info/show/?id='.$info['ID']);
  
}

?>    
<div class='list'>
<form method='post' class='ajax-form' action='/m/info/edit/?id=<?=$info['ID']?>'>
<?=html::input('name', 'Введите название', null, null, tabs($info['NAME']), 'form-control-100', 'text', null, 'info-circle')?>
<?=html::textarea(tabs($info['MESSAGE']), 'message', 'Введите содержимое', null, 'form-control-textarea', 9, 0)?>
<br /><br />
<?=html::button('button ajax-button', 'ok_info', 'plus', 'Сохранить')?> 
<a class='button-o' href='/m/info/show/?id=<?=$info['ID']?>'><?=lg('Отмена')?></a>
<form>
</div>
<?

back('/m/info/show/?id='.$info['ID']);
acms_footer();