<?php
html::title('Добавить контактную информацию');
acms_header();
access('contacts');

$contact = db::get_string("SELECT * FROM `CONTACTS` ORDER BY `TIME` DESC LIMIT 1");

if (isset($contact['ID'])){
  
  error('Неверная директива');
  redirect('/m/contacts/');
  
}

if (post('ok_contacts')){
  
  valid::create(array(
    
    'EMAIL' => ['email', 'email', [0, 200], 'E-mail', 2],
    'VK' => ['vk', 'link', [0, 200], 'Ссылка на страницу ВКонатакте'],
    'YOUTUBE' => ['youtube', 'link', [0, 200], 'Ссылка на страницу YouTube'],
    'OK' => ['ok', 'link', [0, 200], 'Ссылка на страницу Одноклассники'],
    'TWITTER' => ['twitter', 'link', [0, 200], 'Ссылка на страницу Twitter'],
    'FACEBOOK' => ['facebook', 'link', [0, 200], 'Ссылка на страницу Facebook'],
    'TELEGRAM' => ['telegram', 'link', [0, 200], 'Ссылка на страницу Telegram'],
    'ADRESS' => ['adress', 'text', [0, 200], 'Адрес', 0],
    'PHONE' => ['phone', 'text', [0, 200], 'Телефон', 0],
    'WHATSAPP' => ['whatsapp', 'text', [0, 200], 'WhatsApp', 0],
    'VIBER' => ['viber', 'text', [0, 200], 'Viber', 0],
    'MESSAGE' => ['message', 'text', [0, 3000], 'Дополнительная информация', 0]
  
  ));
  
  if (ERROR_LOG == 1){
    
    redirect('/m/contacts/add/');
  
  }
  
  db::get_add("INSERT INTO `CONTACTS` (`MESSAGE`, `EMAIL`, `YOUTUBE`, `OK`, `TWITTER`, `FACEBOOK`, `TELEGRAM`, `ADRESS`, `PHONE`, `WHATSAPP`, `VIBER`, `VK`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", [MESSAGE, EMAIL, YOUTUBE, OK, TWITTER, FACEBOOK, TELEGRAM, ADRESS, PHONE, WHATSAPP, VIBER, VK]);
  
  redirect('/m/contacts/');
  
}

?>    
<div class='list'>
<form method='post' class='ajax-form' action='/m/contacts/add/'>
<?=html::input('email', 'Email', null, null, null, 'form-control-100', 'text', null, 'at')?>
<?=html::input('phone', 'Телефон', null, null, null, 'form-control-100', 'text', null, 'mobile')?>
<?=html::input('adress', 'Адрес', null, null, null, 'form-control-100', 'text', null, 'map-marker')?>  
<?=html::input('whatsapp', 'WhatsApp', null, null, null, 'form-control-100', 'text', null, 'whatsapp')?>
<?=html::input('viber', 'Viber', null, null, null, 'form-control-100', 'text', null, 'whatsapp')?> 
<?=html::input('telegram', 'Ссылка на Telegram', null, null, null, 'form-control-100', 'text', null, 'telegram')?>
<?=html::input('youtube', 'Ссылка на YouTube', null, null, null, 'form-control-100', 'text', null, 'youtube')?>
<?=html::input('ok', 'Ссылка на Одноклассники', null, null, null, 'form-control-100', 'text', null, 'odnoklassniki')?>
<?=html::input('twitter', 'Ссылка на Twitter', null, null, null, 'form-control-100', 'text', null, 'twitter')?>
<?=html::input('vk', 'Ссылка на ВКонтакте', null, null, null, 'form-control-100', 'text', null, 'vk')?>  
<?=html::input('facebook', 'Ссылка на Facebook', null, null, null, 'form-control-100', 'text', null, 'facebook')?>
<?=html::textarea(null, 'message', 'Дополнительная информация', null, 'form-control-textarea', 9, 0)?>
<br /><br />
<?=html::button('button ajax-button', 'ok_contacts', 'plus', 'Добавить')?> 
<a class='button-o' href='/m/contacts/'><?=lg('Отмена')?></a>
<form>
</div>
<?

back('/m/contacts/');
acms_footer();