<div class='desktop-vidget'>
  
<div class='desktop-vidget-title'><?=icons('info-circle', 25, 'fa-fw')?> <?=lg('О системе')?></div>
  
<div class='list-menu'>
<?=lg('Версия')?>: <span class='info gray'><?=tabs(config('ACMS_VERSION'))?></span><br />
<?=lg('Тип')?>: <span class='info gray'><?=tabs(config('ACMS_TYPE'))?></span><br />
<?=lg('Посл. обновление')?>: <span class='info gray'><?=tabs(config('ACMS_UPDATE'))?></span><br />
<?=lg('Полное наименование')?>: <span class='info gray'><?=tabs(config('ACMS_NAME'))?></span><br />
<?=lg('Официальный сайт поддержки')?>: <a class='info blue' href='https://alpha-cms.ru' ajax='no'>alpha-cms.ru</a><br />
<?=lg('Руководитель проекта')?>: <a class='info blue' href='https://alpha-cms.ru/id1' ajax='no'>adm (ID: 1)</a><br /><br />

<?php if (access('management', null) == true){ ?>  
<a href='/admin/system/system_info/' class='button'><?=icons('info-circle', 15, 'fa-fw')?> <?=lg('Полная информация')?></a>
<?php } ?>  
  
</div>

</div>