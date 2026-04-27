<?php
  
if (get('id') == user('ID')) {
  
  ?>
  <a href="/account/cabinet/">
  <?=icons('user', 18, 'fa-fw')?> <?=lg('Личный кабинет')?>
  </a>
  <a href="/account/settings/">
  <?=icons('gear', 18, 'fa-fw')?> <?=lg('Настройки аккаунта')?>
  </a>
  <a href="/shop/">
  <?=icons('shopping-basket', 18, 'fa-fw')?> <?=lg('Магазин услуг')?>
  </a>
  <a href="/exit/" ajax="no">
  <?=icons('power-off', 18, 'fa-fw')?> <?=lg('Выход')?>
  </a>
  <?
  
}else{
  
  ?>
  <a href="/account/form/?id=<?=intval(get('id'))?>">
  <?=icons('info', 18, 'fa-fw')?> <?=lg('Об аккаунте')?>
  </a>
  <?
  
  if (access('users_blocked', null, 1) == true) {
    
    ?>
    <a href="/m/block/user/?id=<?=intval(get('id'))?>&<?=TOKEN_URL?>">
    <?=icons('ban', 18, 'fa-fw')?> <?=lg('Заблокировать')?>
    </a>
    <?
      
  }
  
  if (MANAGEMENT == 1) {
    
    ?>
    <a href="/admin/site/users/?get=add&id=<?=intval(get('id'))?>" ajax="no">
    <?=icons('unlock', 18, 'fa-fw')?> <?=lg('Права на сайте')?>
    </a>
    <?
      
  }
  
  if (access('users_edit', null, 1) == true) {
    
    ?>
    <a href="/account/page/edit/?id=<?=intval(get('id'))?>&<?=TOKEN_URL?>">
    <?=icons('edit', 18, 'fa-fw')?> <?=lg('Редактировать аккаунт')?>
    </a>
    <?
    
  }
  
}

hooks::challenge('nav_account_menu', 'nav_account_menu');
hooks::run('nav_account_menu');

?>
  
<?php if (user('ID') > 0 && get('id') != user('ID')) : ?>
<a href="/m/abuse/users/?id=<?=intval(get('id'))?>">
<?=icons('flag', 18, 'fa-fw')?> <?=lg('Пожаловаться')?>
</a>
<a href="/shopping/translation/?id=<?=intval(get('id'))?>">
<?=icons('money', 18, 'fa-fw')?> <?=lg('Перевести деньги')?>
</a>
<?php endif ?>

<a href="/m/block/user_list/?id=<?=intval(get('id'))?>">
<?=icons('ban', 18, 'fa-fw')?> <?=lg('История блокировок')?>
</a>
  
<span onclick="open_or_close('panel-top-modal2')">
<?=icons('times', 18, 'fa-fw')?> <?=lg('Закрыть')?>
</span>