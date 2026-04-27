<?php
html::title('Контакты');
acms_header();

$contact = db::get_string("SELECT * FROM `CONTACTS` ORDER BY `TIME` DESC LIMIT 1");

if (access('contacts', null) == true && !isset($contact['ID'])){
  
  ?>
  <div class='list'>
  <a href='/m/contacts/add/' class='btn'><?=icons('pencil', 15, 'fa-fw')?> <?=lg('Добавить контактную информацию')?></a>
  </div>
  <?
  
}elseif (access('contacts', null) == true){
  
  ?>
  <div class='list'>
  <a href='/m/contacts/edit/' class='btn'><?=icons('pencil', 15, 'fa-fw')?> <?=lg('Редактировать контактную информацию')?></a>
  </div>
  <?
  
}

if (str($contact['EMAIL']) > 0 || str($contact['PHONE']) > 0 || str($contact['TELEGRAM']) > 0 || str($contact['WHATSAPP']) > 0 || str($contact['VIBER']) > 0 || str($contact['VK']) > 0 || str($contact['OK']) > 0 || str($contact['FACEBOOK']) > 0 || str($contact['TWITTER']) > 0 || str($contact['INSTAGRAM']) > 0 || str($contact['YOUTUBE']) > 0 || str($contact['TIKTOK']) > 0 || str($contact['MESSAGE']) > 0 || str($contact['ADRESS']) > 0){
  
  ?><div class='list-body'><?
    
  if (str($contact['ADRESS']) > 0){
    
    ?>
    <div class='list-menu'>
    <b><?=lg('Адрес')?>:</b> <?=tabs($contact['ADRESS'])?>
    </div>
    <?
  
  }
  
  if (str($contact['EMAIL']) > 0){
    
    ?>
    <div class='list-menu'>
    <b>Email:</b> <a href='mailto:<?=tabs($contact['EMAIL'])?>' ajax='no'><?=tabs($contact['EMAIL'])?></a>
    </div>
    <?
  
  }
  
  if (str($contact['PHONE']) > 0){
    
    ?>
    <div class='list-menu'>
    <b><?=lg('Телефон')?>:</b> <a href='tel:<?=tabs($contact['PHONE'])?>' ajax='no'><?=tabs($contact['PHONE'])?></a>
    </div>
    <?
  
  }

  if (str($contact['TELEGRAM']) > 0){
    
    ?>
    <div class='list-menu'>
    <b>Telegram:</b> <a href='<?=tabs($contact['TELEGRAM'])?>' ajax='no'><?=tabs($contact['TELEGRAM'])?></a>
    </div>
    <?
  
  }

  if (str($contact['WHATSAPP']) > 0){
    
    ?>
    <div class='list-menu'>
    <b>WhatsApp:</b> <?=tabs($contact['WHATSAPP'])?>
    </div>
    <?
  
  }

  if (str($contact['VIBER']) > 0){
    
    ?>
    <div class='list-menu'>
    <b>Viber:</b> <?=tabs($contact['VIBER'])?>
    </div>
    <?
  
  }

  if (str($contact['VK']) > 0){
    
    ?>
    <div class='list-menu'>
    <b><?=lg('ВКонтакте')?>:</b> <a href='<?=tabs($contact['VK'])?>' ajax='no'><?=tabs($contact['VK'])?></a>
    </div>
    <?
  
  }

  if (str($contact['OK']) > 0){
    
    ?>
    <div class='list-menu'>
    <b><?=lg('Одноклассники')?>:</b> <a href='<?=tabs($contact['OK'])?>' ajax='no'><?=tabs($contact['OK'])?></a>
    </div>
    <?
  
  }

  if (str($contact['FACEBOOK']) > 0){
    
    ?>
    <div class='list-menu'>
    <b>Facebook:</b> <a href='<?=tabs($contact['FACEBOOK'])?>' ajax='no'><?=tabs($contact['FACEBOOK'])?></a>
    </div>
    <?
  
  }

  if (str($contact['TWITTER']) > 0){
    
    ?>
    <div class='list-menu'>
    <b>Twitter:</b> <a href='<?=tabs($contact['TWITTER'])?>' ajax='no'><?=tabs($contact['TWITTER'])?></a>
    </div>
    <?
  
  }

  if (str($contact['INSTAGRAM']) > 0){
    
    ?>
    <div class='list-menu'>
    <b>Instagram:</b> <a href='<?=tabs($contact['INSTAGRAM'])?>' ajax='no'><?=tabs($contact['INSTAGRAM'])?></a>
    </div>
    <?
  
  }

  if (str($contact['YOUTUBE']) > 0){
    
    ?>
    <div class='list-menu'>
    <b>YouTube:</b> <a href='<?=tabs($contact['YOUTUBE'])?>' ajax='no'><?=tabs($contact['YOUTUBE'])?></a>
    </div>
    <?
  
  }

  if (str($contact['TIKTOK']) > 0){
    
    ?>
    <div class='list-menu'>
    <b>TikTok:</b> <a href='<?=tabs($contact['TIKTOK'])?>' ajax='no'><?=tabs($contact['TIKTOK'])?></a>
    </div>
    <?
  
  }
  
  if (str($contact['MESSAGE']) > 0){
    
    ?>
    <div class='list-menu'>
    <?=tabs($contact['MESSAGE'])?>
    </div>
    <?
  
  }
  
  ?></div><?
  
}else{
  
  html::empty('Пока пусто');
  
}

back('/', 'На главную');
acms_footer();