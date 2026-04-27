<div class='desktop-vidget'>
  
<div class='desktop-vidget-title'><?=icons('address-book-o', 25, 'fa-fw')?> <?=lg('Руководитель проекта')?></div>
  
<div class='list-menu'>  
<b><?=lg('Имя')?>:</b> <?=tabs(config('ADM_NAME'))?><br /><br />
<b><?=LG('Фамилия')?>:</b> <?=tabs(config('ADM_SURNAME'))?><br /><br />
<b>E-mail:</b> <?=tabs(config('ADM_EMAIL'))?><br /><br />
<b><?=LG('Сайт')?>:</b> <?=HTTP_HOST?><br /><br />
  
<?php if (access('management', null) == true){ ?>   
<a href='/admin/system/info/' class='button'><?=icons('pencil', 15, 'fa-fw')?> <?=lg('Редактировать')?></a>
<?php } ?>  
  
</div>

</div>