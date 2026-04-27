<a href="/account/mail/messages/?id=<?=ACCOUNT_ID?>&get=delete_all&<?=TOKEN_URL?>">
<?=icons('trash', 18, 'fa-fw')?> <?=lg('Удалить переписку')?>
</a>
  
<a href="/account/mail/attachments/?id=<?=ACCOUNT_ID?>&<?=TOKEN_URL?>">
<?=icons('paperclip', 18, 'fa-fw')?> <?=lg('Вложения')?>
</a>
  
<a href="/account/mail/messages_search/?id=<?=ACCOUNT_ID?>&<?=TOKEN_URL?>">
<?=icons('search', 18, 'fa-fw')?> <?=lg('Поиск сообщений')?>
</a>  
  
<?=hooks::challenge('nav_mess_menu', 'nav_mess_menu')?>
<?=hooks::run('nav_mess_menu')?>
  
<a href="/id<?=user('ID')?>">
<?=icons('user', 18, 'fa-fw')?> <?=lg('Моя страница')?>
</a>
  
<a href="/">
<?=icons('home', 18, 'fa-fw')?> <?=lg('На главную')?>
</a>
  
<span onclick="open_or_close('panel-top-modal2')">
<?=icons('times', 18, 'fa-fw')?> <?=lg('Закрыть')?>
</span>