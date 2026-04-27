<?php  
acms_header('Перевод денег', 'users');
$id = intval(get('id'));
is_user($id);

if (user('ID') == $id) {
  
  error('Нельзя отправлять деньги самому себе');
  redirect('/id'.$id);
  
}

if (post('ok_tr')){
  
  valid::create(array(
    
    'TR_SUM' => ['sum', 'number_abs', [1, 100000], 'Сумма']
  
  ));
  
  if (user('MONEY') < TR_SUM){
    
    error('Недостаточно денег на счету');
    redirect('/shopping/translation/?id='.$id);
  
  }
  
  if (ERROR_LOG == 1){

    redirect('/shopping/translation/?id='.$id);
  
  }
  
  money_data($id, TR_SUM, 1, lg('Перевод от пользователя').' [url='.user::url(user('ID')).']'.user::login_mini(user('ID')).'[/url]', 1);
  money_data(user('ID'), TR_SUM, 0, lg('Перевод пользователю').' [url='.user::url($id).']'.user::login_mini($id).'[/url]', 0);
  
  success('Деньги успешно переведены');
  redirect('/id'.$id);

}

?>  
<div class='list-body'>
<div class='list-menu'>
<form method='post' class='ajax-form' action='/shopping/translation/?id=<?=$id?>'>
<b><?=lg('Введите сумму перевода')?>:</b><br />
<?=html::input('sum', 0, null, null, null, 'form-control-30', 'number', null, 'money')?>
<?=html::button('button ajax-button', 'ok_tr', 'mail-forward', 'Перевести деньги')?>
</form>
</div> 
<div class='list-menu'>
<?=lg('У Вас на счету')?>: <b><?=money(user('MONEY'), 2)?></b> 
</div>
<div class='list-menu'>
<?=lg('Деньги будут переведены пользователю %s, комиссии нет. От %s до %s', user::login($id, 0, 1), '<b>'.money(1, 2).'</b>', '<b>'.money(100000, 2).'</b>')?>
</div>
</div>
<?

back('/shopping/');
acms_footer();